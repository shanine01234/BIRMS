<?php
require_once('../inc/function.php');
require_once('process/registerOwner.php');

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Ensure directory exists and has proper permissions
function ensureUploadDir($directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true); // Recursively create the directory
    }
}

// Function to handle file uploads safely
function uploadFile($file, $targetDir, $fileNamePrefix) {
    ensureUploadDir($targetDir); // Ensure directory exists

    $tmpName = $file['tmp_name'];
    $originalName = basename($file['name']);
    $newFileName = $fileNamePrefix . "_" . time() . "_" . $originalName;
    $targetPath = $targetDir . $newFileName;

    // Validate if the file is an image
    $mimeType = @mime_content_type($tmpName);
    if ($mimeType === false || strpos($mimeType, 'image/') === false) {
        throw new Exception("Invalid file type. Only image files are allowed.");
    }

    // Move the file to the target directory
    if (!move_uploaded_file($tmpName, $targetPath)) {
        throw new Exception("Failed to upload file: " . $originalName);
    }

    return $targetPath;
}

// Function to register owner securely
function registerOwner($conn, $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $password) {
    $stmt = $conn->prepare("INSERT INTO owners (firstname, middlename, lastname, email, restobar, contact_num, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $hashedPassword);
    $stmt->execute();
    $stmt->close();
}

// Main form handling
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

    try {
        if ($password === $cpassword) {
            // Handle Restobar Photo Upload
            if (isset($_FILES['restoPhoto']) && $_FILES['restoPhoto']['error'] === UPLOAD_ERR_OK) {
                $restoPhotoPath = uploadFile($_FILES['restoPhoto'], 'uploads/restobar_images/', 'restoPhoto');
            }

            // Handle Gcash QR Upload
            if (isset($_FILES['gcash_qr']) && $_FILES['gcash_qr']['error'] === UPLOAD_ERR_OK) {
                $gcashQrPath = uploadFile($_FILES['gcash_qr'], 'uploads/gcash_qr_codes/', 'gcashQR');
            }

            // Register Owner
            registerOwner($conn, $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $password);
            $msgAlert = "Registration successful!";
        } else {
            $msgAlert = "Passwords do not match!";
        }
    } catch (Exception $e) {
        $msgAlert = $e->getMessage(); // Display any errors encountered during file upload or registration
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['restoPhoto'])) {
        $file = $_FILES['restoPhoto'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $fileType = mime_content_type($file['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File!',
                    text: 'Unsupported file format. Please upload images in JPG, JPEG, or PNG format only.',
                    confirmButtonText: 'OK'
                });
            </script>";
            exit();
        }

        // Move uploaded file if valid
        $uploadDir = "uploads/";
        move_uploaded_file($file['tmp_name'], $uploadDir . basename($file['name']));
        echo "File uploaded successfully!";
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
    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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
<!-- Include SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="col-sm-4 mb-3 mb-sm-0">
    <input type="text" name="firstname" class="form-control form-control-user" id="exampleFirstName"
        placeholder="First Name" required oninput="validateInput(this)">
</div>
<div class="col-sm-4">
    <input type="text" name="middlename" class="form-control form-control-user" id="exampleMiddleName"
        placeholder="Middle Name (Optional)" oninput="validateInput(this)">
</div>
<div class="col-sm-4">
    <input type="text" name="lastname" class="form-control form-control-user" id="exampleLastName"
        placeholder="Last Name" required oninput="validateInput(this)">
</div>

<script>
    function validateInput(input) {
        // Allow letters (including ñ, accented characters) and spaces, but block symbols and numbers
        const validCharacters = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+$/;

        if (!validCharacters.test(input.value)) {
            // Use SweetAlert to show a warning instead of a default alert
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Input',
                text: 'Please use only letters (including ñ and accented characters) and spaces.',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-warning'
                }
            }).then(() => {
                // Clear the input field after the alert is closed
                input.value = '';
            });
        }
    }
</script>

                                </div>
<!-- Include SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.3/dist/sweetalert2.min.css" rel="stylesheet">

<div class="form-group">
    <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail"
        placeholder="Email Address" required>
</div>

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.3/dist/sweetalert2.all.min.js"></script>

<script>
    const emailInput = document.getElementById('exampleInputEmail');

    emailInput.addEventListener('input', function() {
        const email = emailInput.value;
        // Check if the email ends with '@gmail.com'
        if (email && !email.endsWith('@gmail.com')) {
            // Show a SweetAlert2 warning if the email is not a Gmail address
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Email Format',
                text: 'Make sure to enter a valid Gmail address (e.g., user@gmail.com).',
                confirmButtonText: 'OK'
            }).then(() => {
                // Clear the input field after the alert is closed
                emailInput.value = '';
            });
        }
    });
</script>

                                <div class="form-group row">
                                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <div class="col-sm-4">
        <input type="text" name="restobar" class="form-control form-control-user" id="restobarName"
            placeholder="Restobar Name" required>
    </div>
    <div class="col-sm-4">
        <input type="text" name="contact_num" class="form-control form-control-user" id="restobarContact" 
            placeholder="Restobar Contact #" maxlength="13" required>
    </div>
    <div class="col-sm-4">
        <input type="text" name="address" class="form-control form-control-user" id="restobarLocation"
            placeholder="Restobar Location" required>
    </div>

    <script>
        let showWarning = false; // Flag to prevent repeated SweetAlert triggers

        // Restobar Name Validation
        document.getElementById('restobarName').addEventListener('input', function () {
            const regex = /^[a-zA-ZñÑ\s]+$/;
            if (!regex.test(this.value)) {
                if (!showWarning) {
                    showWarning = true;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Input!',
                        text: 'Oops! The restobar name can only contain letters and spaces – no symbols or numbers allowed!',
                    }).then(() => {
                        this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, ''); // Remove invalid characters
                        showWarning = false;
                    });
                }
            }
        });

        // Restobar Contact Validation
        document.getElementById('restobarContact').addEventListener('input', function () {
            const regex = /^\+63[0-9]{0,10}$/;
            if (!regex.test(this.value)) {
                if (!showWarning) {
                    showWarning = true;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Contact!',
                        text: 'Please enter a valid restobar number starting with +63 and followed by 10 digits.',
                    }).then(() => {
                        this.value = this.value.replace(/[^\d\+]/g, '').substring(0, 13); // Enforce valid format
                        showWarning = false;
                    });
                }
            }
        });

        // Restobar Location Validation
        document.getElementById('restobarLocation').addEventListener('input', function () {
            const commaCount = this.value.split(',').length - 1;
            if (commaCount > 3 || this.value.includes(',,,')) {
                if (!showWarning) {
                    showWarning = true;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Format!',
                        text: 'Please enter a valid location with up to three commas (e.g., City, District, Street).',
                    }).then(() => {
                        const parts = this.value.split(',').slice(0, 4).join(','); // Keep valid format
                        this.value = parts;
                        showWarning = false;
                    });
                }
            }
        });
    </script>
</body>                                </div>
                               <!-- Include SweetAlert CDN -->
<!-- Include SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="form-group">
    <span>Restobar Photo</span>
    <!-- Use the 'accept' attribute to allow only jpg, jpeg, and png files -->
    <input 
        type="file" 
        name="restoPhoto" 
        class="form-control form-control-user" 
        id="restoPhoto" 
        accept="image/jpeg, image/jpg, image/png" 
        required>
</div>

<script>
    document.getElementById("restoPhoto").addEventListener("change", function () {
        const fileInput = this;
        const file = fileInput.files[0]; // Get the file
        const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];

        // Validate file type
        if (file && !allowedTypes.includes(file.type)) {
            // Show warning using SweetAlert
            Swal.fire({
                icon: "warning",
                title: "Invalid File!",
                text: "Unsupported file format. Please upload images in JPG, JPEG, or PNG format only.",
                confirmButtonText: "OK"
            });

            // Clear the file input
            fileInput.value = "";
        }
    });
</script>

                                <div class="form-group row">
                                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                    <!-- Password Field -->
                                    <div class="col-sm-6 mb-3 mb-sm-0 password-container">
                                        <input type="password" name="password" class="form-control form-control-user"
                                            id="exampleInputPassword" placeholder="Password" required>
                                        <i class="far fa-eye" id="togglePassword"></i>
                                        <div id="strengthBar" class="progress">
                                            <div class="progress-bar" id="passwordStrengthBar"></div>
                                        </div>
                                        <small id="passwordStrengthText"></small>
                                    </div>
                                    <!-- Repeat Password Field -->
                                    <div class="col-sm-6 password-container">
                                        <input type="password" name="cpassword" class="form-control form-control-user"
                                            id="exampleRepeatPassword" placeholder="Repeat Password" required>
                                        <i class="far fa-eye" id="toggleRepeatPassword"></i>
                                        <div id="matchBar" class="progress">
                                            <div class="progress-bar bg-success" id="passwordMatchBar"></div>
                                        </div>
                                        <small id="passwordMatchText"></small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span>GCash Number</span>
                                    <input type="tel" name="gcash_num" pattern="[0-9]{11}" class="form-control form-control-user" 
                                        id="gcashInput" placeholder="09000000000" minlength="11" maxlength="11" required>
                                </div>
                                
                                <div class="form-group">
    <span>Gcash QR Code</span>
    <!-- Accept only image files (PNG, JPG, JPEG) -->
    <input type="file" name="gcash_qr" class="form-control form-control-user" id="gcashQr" placeholder="Gcash QR Code" accept="image/png, image/jpeg, image/jpg" required>
</div>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
    <style>
        .password-container {
            position: relative;
        }
        .password-container i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        #strengthBar {
            height: 8px;
            margin-top: 5px;
        }
        /* Basic form styling */
        .form-group {
            margin: 20px;
            font-family: Arial, sans-serif;
        }

        .form-group span {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            font-size: 16px;
        }
    </style>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <script>
document.getElementById('gcashQr').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const validFileName = /gcash_qr/i; // Regex pattern to match "gcash_qr" in the filename
    const validFileTypes = ['image/jpeg', 'image/jpg', 'image/png']; // Allowed MIME types

    if (!file) return; // No file selected

    // Check if the file name contains "gcash_qr"
    if (!validFileName.test(file.name)) {
        e.target.value = ''; // Clear the input field
        Swal.fire({
            icon: 'error',
            title: 'Invalid file!',
            text: 'Only files with "gcash_qr" in the name are allowed. Please upload a valid QR code image.',
        });
        return;
    }

    // Check if the file type is valid
    if (!validFileTypes.includes(file.type)) {
        e.target.value = ''; // Clear the input field
        Swal.fire({
            icon: 'error',
            title: 'Invalid file type!',
            text: 'Only image files (JPG, JPEG, PNG) are allowed. Please upload a valid QR code image.',
        });
        return;
    }

    // Success logic if needed
    Swal.fire({
        icon: 'success',
        title: 'File accepted!',
        text: 'Your GCash QR code image has been successfully uploaded.',
    });
});
</script>


    <script>
        const gcashInput = document.getElementById('gcashInput');

        gcashInput.addEventListener('input', function () {
            const value = this.value;

            // Allow only numbers (sanitize input)
            this.value = value.replace(/[^0-9]/g, '');

            // Check if the value starts with "09" and is up to 11 digits
            if (this.value.length >= 2 && !this.value.startsWith('09')) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid GCash Number',
                    text: 'GCash number must start with "09"!',
                    confirmButtonColor: '#3085d6',
                });

                // Clear the input field
                this.value = '';
            }
        });
    </script>

    <script>
        const passwordInput = document.getElementById('exampleInputPassword');
    const repeatPasswordInput = document.getElementById('exampleRepeatPassword');
    const togglePassword = document.getElementById('togglePassword');
    const toggleRepeatPassword = document.getElementById('toggleRepeatPassword');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    const matchBar = document.getElementById('passwordMatchBar');
    const matchText = document.getElementById('passwordMatchText');

    // Function to toggle visibility of password
    function toggleVisibility(input, icon) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('fa-eye-slash');
    }

    // Event Listeners for Toggle Password
    togglePassword.addEventListener('click', () => toggleVisibility(passwordInput, togglePassword));
    toggleRepeatPassword.addEventListener('click', () => toggleVisibility(repeatPasswordInput, toggleRepeatPassword));

    // Password Strength and Validation
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const regex = /^[a-zA-Z0-9]+$/; // Only letters and numbers

        if (!regex.test(password)) {
            showWarningAndClearFields('Your password must be 8 characters long and can only contain letters and numbers (no symbols allowed).');
            return;
        }

        checkPasswordStrength(password);
        checkPasswordMatch();
    });

    // Repeat Password Validation
    repeatPasswordInput.addEventListener('input', () => {
        const repeatPassword = repeatPasswordInput.value;

        if (!/^[a-zA-Z0-9]+$/.test(repeatPassword)) {
            showWarningAndClearFields('Your password must be 8 characters long and can only contain letters and numbers (no symbols allowed).');
            return;
        }

        checkPasswordMatch();
    });

    // Function to Show Warning and Clear Fields
    function showWarningAndClearFields(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid Input',
            text: message,
        });
        passwordInput.value = '';
        repeatPasswordInput.value = '';
        resetIndicators();
    }

    // Function to Reset Indicators
    function resetIndicators() {
        strengthBar.style.width = '0%';
        strengthBar.className = 'progress-bar';
        strengthText.textContent = '';
        matchBar.style.width = '0%';
        matchText.textContent = '';
    }

    // Function to Check Password Strength
    function checkPasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;

        let width = (strength / 4) * 100;
        let color = strength < 2 ? 'bg-danger' : strength < 4 ? 'bg-warning' : 'bg-success';

        strengthBar.style.width = width + '%';
        strengthBar.className = `progress-bar ${color}`;

        strengthText.textContent = strength < 2 ? 'Weak' : strength < 4 ? 'Moderate' : 'Strong';
    }

    // Function to Check Password Match
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const repeatPassword = repeatPasswordInput.value;

        if (password === '' || repeatPassword === '') {
            matchBar.style.width = '0%';
            matchText.textContent = '';
            return;
        }

        if (password === repeatPassword) {
            matchBar.style.width = '100%';
            matchBar.className = 'progress-bar bg-success';
            matchText.textContent = 'Passwords Match!';
        } else {
            matchBar.style.width = '100%';
            matchBar.className = 'progress-bar bg-danger';
            matchText.textContent = 'Passwords Do Not Match!';
        }
    }
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
