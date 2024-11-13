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
    $verification_code = uniqid();

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
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
                window.location.href = "signup.php";
            });
           })
        </script>
       <?php 
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->query("INSERT INTO users SET username = '$name', email ='$email', password= '$hashed', verification_code = '$verification_code'");
        if ($query) {

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'your-password';
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
                    window.location.href = "account-verification.php";
                });
            })
            </script>
        <?php 
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
    <title>Bantayan Island Restobar</title>

    <!-- Custom fonts and styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        font-family: "Inconsolata", monospace;
        background-color: #fff;
    }
    .signup-container {
        border: 2px solid #ddd;
        padding: 20px;
        border-radius: 5px;
        max-width: 400px;
        margin: 100px auto;
        background-color: white;
    }
    .form-group label {
        font-weight: bold;
    }
    .form-control, .input-group .form-control {
        border: 1.5px solid #ddd; 
        border-radius: 4px;
        padding: 10px; 
    }
    .input-group-text {
        cursor: pointer;
        background-color: transparent;
        border: none;
        border-left: none; 
        border-rigth: 1px ; 
    }
    .input-group .form-control {
        border-right: none;
    }
    #password-strength {
        font-size: 0.9em;
    }
    .btn-back {
        margin-bottom: 10px;
        font-size: 14px;
        padding: 5px 10px;
    }
</style>
</head>

<body>
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 30px;">Sign Up</h4>
        <form method="post" onsubmit="return validatePassword()">
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
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control my-2" required>
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="togglePasswordVisibility()">
                            <i id="toggle-icon" class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                <small id="password-strength" class="form-text"></small>
            </div>
            <div class="form-group">
                <label for="retype-password">Re-type Password</label>
                <div class="input-group">
                    <input type="password" id="retype-password" name="retype-password" class="form-control my-2" required>
                    <div class="input-group-append">
                        <span class="input-group-text" onclick="toggleRetypePasswordVisibility()">
                            <i class="fas fa-eye" id="toggle-retype-icon"></i>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" name="signup" class="btn btn-warning btn-block">Sign Up</button>
        </form>
    </div>

    <script>
        document.getElementById("password").addEventListener("input", function() {
            const password = this.value;
            const strengthText = document.getElementById("password-strength");

            if (password.length < 6) {
                strengthText.textContent = "Weak Password";
                strengthText.style.color = "red";
            } else if (password.length < 10) {
                strengthText.textContent = "Moderate Password";
                strengthText.style.color = "orange";
            } else if (/[A-Z]/.test(password) && /[0-9]/.test(password) && /[^a-zA-Z0-9]/.test(password)) {
                strengthText.textContent = "Strong Password";
                strengthText.style.color = "green";
            } else {
                strengthText.textContent = "Moderate Password";
                strengthText.style.color = "orange";
            }
        });

        function validatePassword() {
            const password = document.getElementById("password").value;
            const uppercase = /[A-Z]/;
            const lowercase = /[a-z]/;
            const number = /[0-9]/;
            const specialCharacter = /[!@#$%^&*(),.?":{}|<>]/;

            if (!uppercase.test(password) || !lowercase.test(password) || !number.test(password) || !specialCharacter.test(password)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Requirements',
                    text: 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
                    confirmButtonText: 'OK'
                });
                return false;
            }
            return true;
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggle-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        function toggleRetypePasswordVisibility() {
            const retypePasswordInput = document.getElementById("retype-password");
            const toggleIcon = document.getElementById("toggle-retype-icon");

            if (retypePasswordInput.type === "password") {
                retypePasswordInput.type = "text";
                toggleIcon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                retypePasswordInput.type = "password";
                toggleIcon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
    </script>

    <!-- Footer and JavaScript dependencies -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/datatables.min.js"></script>
</body>
</html>
``
