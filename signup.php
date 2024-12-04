<?php 
require_once('inc/header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];

    if ($password !== $password_confirm) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $verification_code = uniqid();
        
        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows) {
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                        position: "middle",
                        icon: "error",
                        title: "Account already exists",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "signup.php"
                    });
                });
            </script>
            <?php
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = $conn->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
            $query->bind_param("ssss", $name, $email, $hashed, $verification_code);
            if ($query->execute()) {
                // Send verification email
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'shaninezaspa179@gmail.com';
                $mail->Password = 'hglesxkasgmryjxq';
                $mail->Port = 587;

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->setFrom('bantayanrestobar@gmail.com', 'Bantayan Restobar');
                $mail->addAddress($email);
                $mail->Subject = "Account Verification Code";
                $mail->Body = "This is your verification code: " . $verification_code;
                $mail->send();

                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        Swal.fire({
                            position: "middle",
                            icon: "success",
                            title: "Account created successfully",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "account-verification.php"
                        });
                    });
                </script>
                <?php 
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
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bantayan Island Restobar</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: "Inconsolata", monospace;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings: "wdth" 100;
        }
        .signup-container {
            border: 2px solid #ddd; 
            padding: 20px;
            border-radius: 5px; 
            max-width: 400px;
            margin: 0 auto; 
            background-color: white; 
            margin-top:100px;
        }
        .strength-bar {
            width: 100%;
            height: 5px;
            background-color: lightgray;
            margin-top: 10px;
        }
        .strength-bar span {
            height: 100%;
            display: block;
        }
        .strength-weak { background-color: red; }
        .strength-medium { background-color: orange; }
        .strength-strong { background-color: green; }
        .caps-lock-warning {
            color: red;
            font-size: 12px;
            display: none;
        }
    </style>
</head>

<body style="background-color: #fff;">
    <!-- Signup Form -->
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 30px;">Sign Up</h4>
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control my-2" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control my-2" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control my-2" required onkeyup="checkPasswordStrength(); checkCapsLock(event)">
                <div id="password-strength" class="strength-bar"><span></span></div>
                <small id="caps-lock-warning" class="caps-lock-warning">Caps Lock is on!</small>
            </div>
            <div class="form-group">
                <label for="password-confirm">Re-type Password</label>
                <input type="password" id="password-confirm" name="password-confirm" class="form-control my-2" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="show-password">
                <label class="form-check-label" for="show-password">Show Password</label>
            </div>
            <button type="submit" name="signup" class="btn btn-warning btn-block">Sign Up</button>
        </form>
    </div>

    <!-- Footer -->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/datatables.min.js"></script>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <!-- Password Strength Checker -->
    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            const passwordField = document.getElementById('password');
            const confirmField = document.getElementById('password-confirm');
            if (this.checked) {
                passwordField.type = 'text';
                confirmField.type = 'text';
            } else {
                passwordField.type = 'password';
                confirmField.type = 'password';
            }
        });

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength').children[0];
            const strengthText = document.getElementById('password-strength');
            const capsLockWarning = document.getElementById('caps-lock-warning');
            let strength = "weak";

            if (password.length > 8) {
                if (/[A-Z]/.test(password) && /[0-9]/.test(password)) {
                    strength = "strong";
                } else {
                    strength = "medium";
                }
            }

            if (strength === "weak") {
                strengthBar.classList = ['strength-weak'];
            } else if (strength === "medium") {
                strengthBar.classList = ['strength-medium'];
            } else {
                strengthBar.classList = ['strength-strong'];
            }

            strengthBar.style.width = `${(strength === "weak" ? 30 : strength === "medium" ? 60 : 100)}%`;
        }

        function checkCapsLock(event) {
            const capsLockWarning = document.getElementById('caps-lock-warning');
            if (event.getModifierState('CapsLock')) {
                capsLockWarning.style.display = 'block';
            } else {
                capsLockWarning.style.display = 'none';
            }
        }
    </script>
</body>
</html>
