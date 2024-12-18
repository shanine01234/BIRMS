<?php 
require_once('../inc/function.php');
require_once('process/loginAdmin.php');



// Initialize login attempt tracking if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['first_attempt_time'] = time();
}

// Define constants for login attempts and lockout time
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 1); // Lockout time in minutes

// Check if user is locked out
if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    $lockout_time_left = LOCKOUT_TIME * 60 - (time() - $_SESSION['first_attempt_time']);
    if ($lockout_time_left > 0) {
        echo "<script>swal('Too Many Attempts!', 'Please try again in " . ceil($lockout_time_left / 60) . " minutes.', 'error');</script>";
        die();
    } else {
        // Reset login attempts after lockout time passes
        $_SESSION['login_attempts'] = 0;
    }
}

// Track failed login attempts by IP address
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = [];
}

// Handle IP blocking for excessive failed attempts
$ip_address = $_SERVER['REMOTE_ADDR'];
$max_attempts = 5;
$block_duration = 30 * 60; // Block for 30 minutes

if (isset($_SESSION['failed_attempts'][$ip_address]) && $_SESSION['failed_attempts'][$ip_address]['count'] >= $max_attempts) {
    $time_left = $block_duration - (time() - $_SESSION['failed_attempts'][$ip_address]['timestamp']);
    if ($time_left > 0) {
        echo "<script>swal('IP Blocked!', 'Your IP has been temporarily blocked. Please try again in " . ceil($time_left / 60) . " minutes.', 'error');</script>";
        die();
    } else {
        // Reset the failed attempts after block duration has passed
        $_SESSION['failed_attempts'][$ip_address] = ['count' => 0, 'timestamp' => time()];
    }
}

// Verify reCAPTCHA response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loginAdmin'])) {
    $recaptchaSecret = '6Ldz7JIqAAAAAIp9MiVvQepNEFe9o0GywFAnBH95'; // Replace with your reCAPTCHA v3 secret key
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    
    // Make a POST request to Google's reCAPTCHA verification API
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecret}&response={$recaptchaResponse}");
    $responseKeys = json_decode($response, true);

    // Check if CAPTCHA validation is successful
    if ($responseKeys['success'] && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process login logic
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Assume login logic here, replace with actual password check
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Example hash check
            $storedPasswordHash = 'hashed_password_from_db'; // Fetch this from the database

            // Verify password securely using password_verify
            if (password_verify($password, $storedPasswordHash)) {
                // Success: Proceed with login
                $_SESSION['login_attempts'] = 0; // Reset attempts on successful login
                // Redirect to admin dashboard or other page
                echo "<script>swal('Success!', 'You have successfully logged in.', 'success');</script>";
                header("Location: dashboard.php");
                exit();
            } else {
                // Failed login: Increment failed attempts
                log_failed_attempt($username);
                $_SESSION['login_attempts'] += 1;
                echo "<script>swal('Login Failed!', 'Incorrect username or password.', 'error');</script>";
            }
        }
    } else {
        echo "<script>swal('reCAPTCHA Failed!', 'Please complete the CAPTCHA verification.', 'error');</script>";
    }
}

// Track failed login attempts and log them
function log_failed_attempt($username) {
    $logfile = 'failed_login_attempts.log';
    $log = "Failed login attempt: " . $username . " - IP: " . $_SERVER['REMOTE_ADDR'] . " - Time: " . date('Y-m-d H:i:s') . "\n";
    file_put_contents($logfile, $log, FILE_APPEND);
}

// Check for request URI for redirection to prevent .php extension in URL
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

    <title>BIRMS | Admin Login</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">

    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldz7JIqAAAAALCcq3dDLQBNAyHnlcVKyFzuxxBg"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            grecaptcha.ready(function () {
                grecaptcha.execute('6Ldz7JIqAAAAALCcq3dDLQBNAyHnlcVKyFzuxxBg', { action: 'login' }).then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                });
            });
        });
    </script>

    <style>
        /* Gradient background */
        body {
            background: linear-gradient(to right, #4facfe, #00f2fe); /* Customize your gradient colors */
            height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }
        form.user .form-control {
            font-size: 1rem;  /* Adjust font size */
            padding: 10px;    /* Adjust padding */
        }

        form.user .btn-user {
            font-size: 1rem;  /* Adjust button font size */
            padding: 10px 20px;  /* Adjust button padding */
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
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back Admin!</h1>
                                        <?=$msgAlert ?? ''?>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" required>
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

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
</body>
</html>
