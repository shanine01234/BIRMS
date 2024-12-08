<?php 
require_once('../inc/function.php');
require_once('process/loginOwner.php');

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

    <title>BIRMS | Owner Login</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom CSS for password visibility -->
    <style>
        .password-container {
            position: relative;
        }

        .password-container .far.fa-eye, .password-container .far.fa-eye-slash {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* Gradient Background */
        body {
        background: 
            linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), /* Gradient overlay for fade effect */
            url('../img/photos/own.jpg'); /* Background image path */
        background-size: cover; /* Cover the entire viewport */
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Prevent repeating */
        height: 100vh; /* Full height of viewport */
        margin: 0; /* Remove default margin */
    }

    /* Resize form */
    .user-form {
        max-width: 350px; /* Maximum width for form */
        margin: 0 auto; /* Center the form */
        padding: 30px; /* Add some padding */
    }

    .form-group input {
        padding: 10px;
        font-size: 16px; /* Resize input text */
        
    }

    .btn-user {
        padding: 10px 20px; /* Resize the button */
        font-size: 16px; /* Resize the button text */
    }
    </style>
</head>

<body>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Hello Resto Owner!</h1>
                                        <?=$msgAlert?>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="owner_email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email..." required>
                                        </div>

                                        <div class="form-group password-container">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" required>
                                            <i class="far fa-eye-slash" id="togglePassword"></i>
                                        </div>

                                        <button type="submit" name="loginOwner" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="register.php">Create an Account!</a>
                                    </div>
                                </div>
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
    <script src="../js/sb-admin-2.min.js"></script>

    <!-- Show Password Script -->
    <script>
        // Toggle password visibility
        document.getElementById("togglePassword").addEventListener("click", function () {
            var passwordField = document.getElementById("exampleInputPassword");
            var type = passwordField.type === "password" ? "text" : "password";
            passwordField.type = type;
            
            // Toggle the icon class based on the password field type
            this.classList.toggle("fa-eye-slash", passwordField.type === "password");
            this.classList.toggle("fa-eye", passwordField.type === "text");
        });
    </script>

</body>

</html>
