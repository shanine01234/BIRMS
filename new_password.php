<?php
require_once('inc/conn.php');
require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check for token in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Fetch user by token
    $stmt = $conn->prepare("SELECT email, reset_token_at FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $resetTokenTime = $row['reset_token_at'];

        // Check if the token has expired (valid for 1 hour)
        if (strtotime($resetTokenTime) > time() - 3600) {

            // Handle form submission for resetting password
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new-password'])) {
                $new_password = $_POST['new-password'];

                // Validate password length (optional)
                if (strlen($new_password) < 6) {
                    echo "<script>
                        Swal.fire('Error', 'Password must be at least 6 characters long.', 'error');
                      </script>";
                } else {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update the user's password and clear token
                    $stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, reset_token_at = NULL WHERE email = ?");
                    $stmt->bind_param("ss", $hashed_password, $email);
                    if ($stmt->execute()) {
                        echo "<script>
                                Swal.fire('Success', 'Your password has been reset successfully. You can now log in.', 'success')
                                .then(() => { window.location.href = 'login.php'; });
                              </script>";
                    } else {
                        echo "<script>
                                Swal.fire('Error', 'Failed to reset the password. Please try again.', 'error');
                              </script>";
                    }
                }
            }
        } else {
            echo "<script>
                    Swal.fire('Error', 'The password reset link has expired.', 'error')
                    .then(() => { window.location.href = 'forgot_password.php'; });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire('Error', 'Invalid or expired token.', 'error')
                .then(() => { window.location.href = 'forgot_password.php'; });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire('Error', 'Invalid access. Token is missing.', 'error')
            .then(() => { window.location.href = 'forgot_password.php'; });
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Reset Password</title>
    <link rel="icon" type="image/png" href="img/favicon.jpg" width="30" height="24" style="border-radius: 100px;">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Roboto", sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .btn-warning {
            border-radius: 8px;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h4 class="text-center">Set a New Password</h4>
        <form method="POST">
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new-password" class="form-control" placeholder="Enter new password" required>
            </div>
            <button type="submit" class="btn btn-warning btn-block">Reset Password</button>
            <a href="index.php" class="btn btn-secondary btn-block">Back to Home</a>
        </form>
    </div>
</body>
</html>
