<?php
session_start(); // Start the session to track login attempts

require_once('../inc/function.php');
require_once('process/loginAdmin.php');

// Initialize or reset the session variables
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Check if the lockout period has passed
$lockout_duration = 180; // 3 minutes in seconds
$current_time = time();
if ($current_time - $_SESSION['lockout_time'] > $lockout_duration) {
    $_SESSION['failed_attempts'] = 0;
    $_SESSION['lockout_time'] = 0;
}

// Verify reCAPTCHA response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginAdmin'])) {
    if ($_SESSION['failed_attempts'] >= 3) {
        $msgAlert = "Too many failed attempts. Please try again later.";
    } else {
        $recaptchaSecret = '6Ldz7JIqAAAAAIp9MiVvQepNEFe9o0GywFAnBH95'; // Replace with your reCAPTCHA secret key
        

        // Validate reCAPTCHA with Google's API
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
        

            if (validateAdminLogin($username, $password)) { // Assume validateAdminLogin() is your custom function
                $_SESSION['failed_attempts'] = 0; // Reset on successful login
                header('Location: admin_dashboard.php'); // Redirect on success
                exit();
            } else {
                $_SESSION['failed_attempts']++;
                if ($_SESSION['failed_attempts'] >= 3) {
                    $_SESSION['lockout_time'] = time(); // Start lockout timer
                    $msgAlert = "Too many failed attempts. Please wait 3 minutes.";
                } else {
                    $msgAlert = "Invalid username or password.";
                }
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
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldz7JIqAAAAALCcq3dDLQBNAyHnlcVKyFzuxxBg"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if ($_SESSION['failed_attempts'] >= 3): ?>
                disableLoginButton();
                let lockoutEnd = <?= $_SESSION['lockout_time'] + $lockout_duration ?>;
                let interval = setInterval(function() {
                    let currentTime = Math.floor(Date.now() / 1000);
                    if (currentTime >= lockoutEnd) {
                        enableLoginButton();
                        clearInterval(interval);
                    }
                }, 1000);

                function disableLoginButton() {
                    document.querySelector('button[name="loginAdmin"]').disabled = true;
                }

                function enableLoginButton() {
                    document.querySelector('button[name="loginAdmin"]').disabled = false;
                }
            <?php endif; ?>

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
                                        <?=$msgAlert ?? ''?>
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
                                        <button type="submit" name="loginAdmin" class="btn btn-primary btn-user btn-block">Login</button>
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
