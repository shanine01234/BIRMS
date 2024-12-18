<?php
require_once('../inc/function.php');
require_once('process/registerOwner.php');

// Enhanced input sanitization and validation function
function sanitizeInput($data, $type = 'string') {
    // Trim whitespace
    $data = trim($data);
    
    // Remove backslashes
    $data = stripslashes($data);
    
    // Encode special characters for HTML output
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    // Additional type-specific validation
    switch ($type) {
        case 'email':
            $data = filter_var($data, FILTER_SANITIZE_EMAIL);
            if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format");
            }
            break;
        
        case 'phone':
            // Remove non-digit characters
            $data = preg_replace('/[^0-9]/', '', $data);
            
            // Validate Philippine mobile number format
            if (!preg_match('/^(09|\+639)\d{9}$/', $data)) {
                throw new Exception("Invalid phone number format");
            }
            break;
        
        case 'name':
            // Allow only letters, spaces, and hyphens
            if (!preg_match('/^[A-Za-z\s\-\']+$/', $data)) {
                throw new Exception("Invalid name format");
            }
            break;
        
        case 'text':
            // Remove any potentially dangerous HTML or script tags
            $data = strip_tags($data);
            break;
    }
    
    return $data;
}

// Secure file upload function with enhanced validation
function uploadFile($file, $targetDir, $fileNamePrefix, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) {
    // Ensure directory exists and is writable
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    // Validate file upload
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload failed. Error code: " . $file['error']);
    }
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Invalid file type. Only JPEG, PNG, and GIF images are allowed.");
    }
    
    // Generate a secure filename
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $secureFileName = $fileNamePrefix . '_' . bin2hex(random_bytes(8)) . '.' . $fileExt;
    $targetPath = $targetDir . $secureFileName;
    
    // Move uploaded file securely
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception("Failed to move uploaded file");
    }
    
    return $targetPath;
}

// Secure owner registration function
function registerOwner($conn, $data) {
    // Prepare SQL statement with parameterized queries
    $stmt = $conn->prepare("INSERT INTO owners (
        firstname, middlename, lastname, email, restobar, 
        contact_num, address, password, gcash_num, 
        restobar_photo, gcash_qr_path
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Hash password with strong algorithm
    $hashedPassword = password_hash($data['password'], PASSWORD_ARGON2ID);
    
    // Bind parameters
    $stmt->bind_param(
        "sssssssssss", 
        $data['firstname'], 
        $data['middlename'], 
        $data['lastname'], 
        $data['email'], 
        $data['restobar'], 
        $data['contact_num'], 
        $data['address'], 
        $hashedPassword,
        $data['gcash_num'],
        $data['restobar_photo'],
        $data['gcash_qr_path']
    );
    
    // Execute and check for errors
    if (!$stmt->execute()) {
        throw new Exception("Registration failed: " . $stmt->error);
    }
    
    $stmt->close();
}

// Main form handling with comprehensive error handling
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerOwner'])) {
    try {
        // Sanitize and validate all inputs
        $formData = [
            'firstname' => sanitizeInput($_POST['firstname'], 'name'),
            'middlename' => !empty($_POST['middlename']) ? sanitizeInput($_POST['middlename'], 'name') : '',
            'lastname' => sanitizeInput($_POST['lastname'], 'name'),
            'email' => sanitizeInput($_POST['email'], 'email'),
            'restobar' => sanitizeInput($_POST['restobar'], 'text'),
            'contact_num' => sanitizeInput($_POST['contact_num'], 'phone'),
            'address' => sanitizeInput($_POST['address'], 'text'),
            'gcash_num' => sanitizeInput($_POST['gcash_num'], 'phone')
        ];

        // Password validation
        if ($_POST['password'] !== $_POST['cpassword']) {
            throw new Exception("Passwords do not match");
        }
        $formData['password'] = $_POST['password']; // Store for hashing later

        // File uploads with validation
        $formData['restobar_photo'] = uploadFile(
            $_FILES['restoPhoto'], 
            'uploads/restobar_images/', 
            'restobar'
        );

        $formData['gcash_qr_path'] = uploadFile(
            $_FILES['gcash_qr'], 
            'uploads/gcash_qr_codes/', 
            'gcash_qr'
        );

        // Register owner
        registerOwner($conn, $formData);
        
        $msgAlert = "Registration successful!";
    } catch (Exception $e) {
        // Log the full error for admin (in a real-world scenario)
        error_log($e->getMessage());
        
        // Show a generic error to the user
        $msgAlert = "Registration failed. Please check your inputs and try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BIRMS | Owner Register</title>
    <link rel="icon" type="image/png" href="../img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .password-container {
            position: relative;
        }

        .password-container .far.fa-eye {
            position: absolute;
            top: 50%; /* Vertically center the icon */
            right: 20px; /* Adjust right margin */
            transform: translateY(-50%); /* Ensure vertical centering */
            cursor: pointer;
        }
    </style>

</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row w-100">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account as Resto Owner!</h1>
                                <?php echo htmlspecialchars($msgAlert); // XSS prevention ?>
                            </div>
                            <form class="user" method="POST" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-4 mb-3 mb-sm-0">
                                        <input type="text" name="firstname" class="form-control form-control-user" id="exampleFirstName"
                                            placeholder="First Name" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="middlename" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Middle Name (Optional)">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="lastname" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Last Name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <input type="text" name="restobar" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Restobar Name" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="contact_num" class="form-control form-control-user" id="exampleLastName" 
                                            placeholder="Restobar Contact #"  maxlength="11" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="address" class="form-control form-control-user" id="exampleLastName"
                                            placeholder="Restobar Location" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span>Restobar Photo</span>
                                    <input type="file" name="restoPhoto" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Restobar Photo" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0 password-container">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password" required>
                                        <i class="far fa-eye" id="togglePassword"></i>
                                    </div>
                                    <div class="col-sm-6 password-container">
                                        <input type="password" name="cpassword" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password" required>
                                        <i class="far fa-eye" id="toggleRepeatPassword"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span>Gcash Number</span>
                                    <input type="tel" name="gcash_num" pattern="[0-9]{11}" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="09000000000" minlength="11" maxlength="11" required>
                                </div>
                                <div class="form-group">
                                    <span>Gcash QR Code</span>
                                    <input type="file" name="gcash_qr" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Gcash QR Code" required>
                                </div>
                                <button type="submit" name="registerOwner" class="btn btn-primary btn-user btn-block">Register Account</button>
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <script>
        document.getElementById("togglePassword").addEventListener("click", function () {
            var password = document.getElementById("exampleInputPassword");
            var type = password.type === "password" ? "text" : "password";
            password.type = type;
            this.classList.toggle("fa-eye-slash");
        });

        document.getElementById("toggleRepeatPassword").addEventListener("click", function () {
            var password = document.getElementById("exampleRepeatPassword");
            var type = password.type === "password" ? "text" : "password";
            password.type = type;
            this.classList.toggle("fa-eye-slash");
        });
    </script>
    <script>
    document.getElementById("restoPhoto").addEventListener("change", function() {
        var file = this.files[0];
        if (file) {
            var fileType = file.type;
            if (!fileType.startsWith('image/')) {
                alert("Please upload a valid image file (JPG, PNG, etc.).");
                this.value = ""; // Clear the input field
            }
        }
    });

    document.getElementById("gcash_qr").addEventListener("change", function() {
        var file = this.files[0];
        if (file) {
            var fileType = file.type;
            if (!fileType.startsWith('image/')) {
                alert("Please upload a valid image file (JPG, PNG, etc.).");
                this.value = ""; // Clear the input field
            }
        }
    });
</script>

</body>

</html>
