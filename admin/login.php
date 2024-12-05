<?php 
session_start();
require_once('../inc/function.php');
require_once('process/loginAdmin.php');

// Verify reCAPTCHA response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginAdmin'])) {
    // Initialize or get attempts count
    if (!isset($_SESSION['attempt_count'])) {
        $_SESSION['attempt_count'] = 0;
    }

    // Check if attempts exceed 3 within 3 minutes
    if (isset($_SESSION['lock_time']) && time() - $_SESSION['lock_time'] < 180) {
        $msgAlert = "Too many failed login attempts. Please try again after 3 minutes.";
    } else {
        // Reset lock after 3 minutes
        if (isset($_SESSION['lock_time']) && time() - $_SESSION['lock_time'] >= 180) {
            $_SESSION['attempt_count'] = 0;
            unset($_SESSION['lock_time']);
        }

        $recaptchaSecret = '6Ldz7JIqAAAAAIp9MiVvQepNEFe9o0GywFAnBH95'; // Replace with your reCAPTCHA secret key
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        $responseKeys = json_decode($response, true);

        if ($responseKeys['success']) {
            // Authenticate user (replace with your authentication logic)
            $username = $_POST['username'];
            $password = $_POST['password'];
            $isAuthenticated = authenticateAdmin($username, $password); // Custom function in loginAdmin.php

            if ($isAuthenticated) {
                $_SESSION['attempt_count'] = 0; // Reset attempts on success
                header("Location: adminDashboard.php"); // Redirect to dashboard
                exit();
            } else {
                $_SESSION['attempt_count']++;
                if ($_SESSION['attempt_count'] >= 3) {
                    $_SESSION['lock_time'] = time();
                    $msgAlert = "Too many failed login attempts. Please try again after 3 minutes.";
                } else {
                    $msgAlert = "Invalid credentials. Attempt " . $_SESSION['attempt_count'] . " of 3.";
                }
            }
        } else {
            $msgAlert = "reCAPTCHA verification failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>BIRMS | Admin Login</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldz7JIqAAAAALCcq3dDLQBNAyHnlcVKyFzuxxBg"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            grecaptcha.ready(function () {
                grecaptcha.execute('6Ldz7JIqAAAAALCcq3dDLQBNAyHnlcVKyFzuxxBg', { action: 'login' }).then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                });
            });
        });
    </script>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back Admin!</h1>
                                        <?= $msgAlert ?? '' ?>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                placeholder="Enter Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                placeholder="Password" required>
                                        </div>
                                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
                                        <button type="submit" name="loginAdmin" class="btn btn-primary btn-user btn-block" 
                                        <?= (isset($_SESSION['lock_time']) && time() - $_SESSION['lock_time'] < 180) ? 'disabled' : '' ?>>
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                </div>
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
    <script src="../js/sb-admin-2.min.js"></script>
</body>

</html>
