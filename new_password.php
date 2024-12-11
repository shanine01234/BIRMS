<?php
require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if a token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token and check expiration time
    $stmt = $conn->prepare("SELECT email, reset_expiry FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email']; // Get the user's email associated with the token

        // Check if the form is submitted
        if (isset($_POST['reset-password'])) {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);

            // Check if passwords match
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                // Update the password and clear the token
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE email = ?");
                $update_stmt->bind_param("ss", $hashed_password, $email);
                $update_stmt->execute();

                echo "<script>
                        Swal.fire('Success', 'Your password has been reset successfully!', 'success').then(() => {
                            window.location.href = 'login.php'; // Redirect to login page
                        });
                      </script>";
                exit();
            } else {
                // Passwords do not match
                echo "<script>
                        Swal.fire('Error', 'Passwords do not match. Please try again.', 'error');
                      </script>";
            }
        }
    } else {
        // Invalid or expired token
        echo "<script>
                Swal.fire('Error', 'The password reset link is invalid or has expired.', 'error').then(() => {
                    window.location.href = 'forgot_password.php'; // Redirect to forgot password page
                });
              </script>";
        exit();
    }
} else {
    // No token provided
    echo "<script>
            Swal.fire('Error', 'Invalid request.', 'error').then(() => {
                window.location.href = 'forgot_password.php';
            });
          </script>";
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
    <title>Reset Password</title>
    <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }
        .reset-password-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .reset-password-container h4 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .btn-primary {
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="reset-password-container">
        <h4>Reset Your Password</h4>
        <form method="post">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" name="reset-password" class="btn btn-primary btn-block">Reset Password</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
