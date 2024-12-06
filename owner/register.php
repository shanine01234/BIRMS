<?php 
session_start();

// Include required files
require_once('../inc/function.php');
require_once('db_connection.php'); // Use a secure connection script for your database

$msgAlert = "";

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Rate Limiting
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] > 5) {
    $msgAlert = '<div class="alert alert-danger">Too many attempts. Please try again later.</div>';
    exit($msgAlert);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerOwner'])) {
    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    // Sanitize inputs
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $restobar = filter_input(INPUT_POST, 'restobar', FILTER_SANITIZE_STRING);
    $contact_num = filter_input(INPUT_POST, 'contact_num', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $gcash_num = filter_input(INPUT_POST, 'gcash_num', FILTER_SANITIZE_STRING);

    // Validate file uploads
    $allowed_types = ['image/jpeg', 'image/png'];
    if ($_FILES['restoPhoto']['error'] === UPLOAD_ERR_OK && in_array(mime_content_type($_FILES['restoPhoto']['tmp_name']), $allowed_types)) {
        $restoPhoto = '../uploads/' . basename($_FILES['restoPhoto']['name']);
        move_uploaded_file($_FILES['restoPhoto']['tmp_name'], $restoPhoto);
    } else {
        die('Invalid photo file.');
    }

    if ($_FILES['gcash_qr']['error'] === UPLOAD_ERR_OK && in_array(mime_content_type($_FILES['gcash_qr']['tmp_name']), $allowed_types)) {
        $gcash_qr = '../uploads/' . basename($_FILES['gcash_qr']['name']);
        move_uploaded_file($_FILES['gcash_qr']['tmp_name'], $gcash_qr);
    } else {
        die('Invalid QR code file.');
    }

    // Validate passwords
    if ($password !== $cpassword) {
        die('Passwords do not match.');
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Save to the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO owners (firstname, middlename, lastname, email, restobar, contact_num, address, password, gcash_num, resto_photo, gcash_qr) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssssss', $firstname, $middlename, $lastname, $email, $restobar, $contact_num, $address, $hashed_password, $gcash_num, $restoPhoto, $gcash_qr);

    if ($stmt->execute()) {
        $msgAlert = '<div class="alert alert-success">Account successfully created!</div>';
    } else {
        $msgAlert = '<div class="alert alert-danger">Registration failed. Please try again.</div>';
    }

    $stmt->close();
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

    <!-- Custom CSS to adjust icon margins -->
    <style>
        /* Adjusting the position of the eye icons */
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
                <!-- Nested Row within Card Body -->
                <div class="row w-100">
                    <div class="col-lg-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account as Resto Owner!</h1>
                                <?=$msgAlert?>
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
                                        placeholder="Email Address" required>
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
                                        placeholder="Email Address" required>
                                    <?= $msgAlert; ?>
        <form class="user" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script>
        // Toggle password visibility for the first password field
        document.getElementById("togglePassword").addEventListener("click", function () {
            var password = document.getElementById("exampleInputPassword");
            var type = password.type === "password" ? "text" : "password";
            password.type = type;
            this.classList.toggle("fa-eye-slash");
        });

        // Toggle password visibility for the repeat password field
        document.getElementById("toggleRepeatPassword").addEventListener("click", function () {
            var password = document.getElementById("exampleRepeatPassword");
            var type = password.type === "password" ? "text" : "password";
            password.type = type;
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>

</html>
