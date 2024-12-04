<?php 
require_once('inc/header.php');

if (isset($_POST['submit'])) {
    $verification_code = $_POST['verification_code'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
        $row = $stmt->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            if ($row['verification_code'] === $verification_code) {
                $update = $conn->query("UPDATE users SET status = 2 WHERE verification_code = '$verification_code' AND email = '$email'");

                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "middle",
                            icon: "success",
                            title: "Account verified successfully",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "login.php";
                        });
                    });
                </script>
                <?php 
            } else {
                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "middle",
                            icon: "error",
                            title: "Incorrect verification code",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "account-verification.php";
                        });
                    });
                </script>
                <?php 
            }    
        } else {
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: "middle",
                        icon: "error",
                        title: "Incorrect email or password",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "account-verification.php";
                    });
                });
            </script>
            <?php 
        }
    } else {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "middle",
                    icon: "error",
                    title: "Incorrect email or password",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "account-verification.php";
                });
            });
        </script>
        <?php 
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
        .signup-container {
            border: 2px solid #ddd; 
            padding: 20px;
            border-radius: 5px; 
            max-width: 400px;
            margin: 0 auto; 
            background-color: white; 
            margin-top:100px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body style="background-color: #fff;">
    <!-- Account Verification Form -->
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 30px;">Account Verification</h4>
        <p>Please check your email account. We sent a code to your email account.</p>
        <form method="post">
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" class="form-control my-2" required>
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
                        <span class="input-group-text" id="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="retype-password">Re-type Password</label>
                <div class="input-group">
                    <input type="password" id="retype-password" name="retype-password" class="form-control my-2" required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="toggle-retype-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-warning btn-block">Verify</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('toggle-password').addEventListener('click', function () {
            var passwordField = document.getElementById('password');
            var icon = this.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';  // Show password
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';  // Hide password
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Toggle re-type password visibility
        document.getElementById('toggle-retype-password').addEventListener('click', function () {
            var retypePasswordField = document.getElementById('retype-password');
            var icon = this.querySelector('i');
            
            if (retypePasswordField.type === 'password') {
                retypePasswordField.type = 'text';  // Show re-type password
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                retypePasswordField.type = 'password';  // Hide re-type password
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>

</html>
