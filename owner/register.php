<?php
require_once('../inc/function.php');
require_once('process/registerOwner.php');

// Sample function to sanitize and validate inputs
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));  // Prevent XSS
}

// Sample SQL Injection prevention: Use prepared statements (example using MySQLi)
function registerOwner($conn, $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $password) {
    // SQL injection prevention using prepared statements
    $stmt = $conn->prepare("INSERT INTO owners (firstname, middlename, lastname, email, restobar, contact_num, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, password_hash($password, PASSWORD_BCRYPT));
    $stmt->execute();
    $stmt->close();
}

// Validate the data for XSS and SQL injection before saving
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerOwner'])) {
    $firstname = sanitizeInput($_POST['firstname']);
    $middlename = sanitizeInput($_POST['middlename']);
    $lastname = sanitizeInput($_POST['lastname']);
    $email = sanitizeInput($_POST['email']);
    $restobar = sanitizeInput($_POST['restobar']);
    $contact_num = sanitizeInput($_POST['contact_num']);
    $address = sanitizeInput($_POST['address']);
    $password = sanitizeInput($_POST['password']);
    $cpassword = sanitizeInput($_POST['cpassword']);
    $gcash_num = sanitizeInput($_POST['gcash_num']);

    // Check if passwords match
    if ($password === $cpassword) {
        // Register the owner with the sanitized data
        registerOwner($conn, $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $password);
        $msgAlert = "Registration successful!";
    } else {
        $msgAlert = "Passwords do not match!";
    }

    if (isset($_FILES['restoPhoto']) && $_FILES['restoPhoto']['error'] === UPLOAD_ERR_OK) {
        $restoPhoto = $_FILES['restoPhoto'];
        $restoPhotoName = $restoPhoto['name'];
        $restoPhotoTmpName = $restoPhoto['tmp_name'];
        $restoPhotoSize = $restoPhoto['size'];
        $restoPhotoType = mime_content_type($restoPhotoTmpName);

        // Validate image file type (only allow images)
        if (strpos($restoPhotoType, 'image/') === false) {
            $msgAlert = "The uploaded file is not a valid image. Please upload an image file.";
        } else {
            // Move the file to the desired directory
            $uploadDir = 'uploads/restobar_images/';
            $restoPhotoPath = $uploadDir . basename($restoPhotoName);
            if (move_uploaded_file($restoPhotoTmpName, $restoPhotoPath)) {
                // Successfully uploaded the photo
            } else {
                $msgAlert = "Error uploading the restobar photo.";
            }
        }
    }

    // Handle Gcash QR code upload
    if (isset($_FILES['gcash_qr']) && $_FILES['gcash_qr']['error'] === UPLOAD_ERR_OK) {
        $gcashQr = $_FILES['gcash_qr'];
        $gcashQrName = $gcashQr['name'];
        $gcashQrTmpName = $gcashQr['tmp_name'];
        $gcashQrSize = $gcashQr['size'];
        $gcashQrType = mime_content_type($gcashQrTmpName);

        // Validate image file type (only allow images)
        if (strpos($gcashQrType, 'image/') === false) {
            $msgAlert = "The uploaded file is not a valid image. Please upload an image file.";
        } else {
            // Move the file to the desired directory
            $uploadDir = 'uploads/gcash_qr_codes/';
            $gcashQrPath = $uploadDir . basename($gcashQrName);
            if (move_uploaded_file($gcashQrTmpName, $gcashQrPath)) {
                // Successfully uploaded the Gcash QR code
            } else {
                $msgAlert = "Error uploading the Gcash QR code.";
            }
        }
    }

    // If validation passes, continue with the registration
    if (empty($msgAlert)) {
        registerOwner($conn, $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $password);
        $msgAlert = "Registration successful!";
    }
}

    $request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
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
