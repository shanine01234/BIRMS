<?php
require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if token is set
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);

    // Verify the database connection
    if (!isset($conn)) {
        die("<script>Swal.fire('Error', 'Database connection error.', 'error');</script>");
    }

    // Verify token from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    if (!$stmt) {
        die("<script>Swal.fire('Error', 'Failed to prepare statement.', 'error');</script>");
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $resetTokenTime = $row['reset_token_at'];

        // Check if the reset token has expired (1 hour expiration)
        if (strtotime($resetTokenTime) > time() - 3600) {
            // Handle password reset form submission
            if (isset($_POST['reset-password'])) {
                // Validate the new password
                $new_password_raw = $_POST['new-password'];
                if (strlen($new_password_raw) < 6) {
                    echo "<script>Swal.fire('Error', 'Password must be at least 6 characters long.', 'error');</script>";
                } else {
                    $new_password = password_hash($new_password_raw, PASSWORD_DEFAULT); // Hash the password securely

                    // Update the user's password and reset the token
                    $update_stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, reset_token_at = NULL WHERE email = ?");
                    if ($update_stmt) {
                        $update_stmt->bind_param("ss", $new_password, $email);
                        if ($update_stmt->execute()) {
                            echo "<script>
                                    Swal.fire('Success', 'Your password has been reset successfully. You can now log in.', 'success').then(() => {
                                        window.location.href = 'login.php';
                                    });
                                  </script>";
                            exit;
                        } else {
                            echo "<script>Swal.fire('Error', 'Failed to update the password. Please try again.', 'error');</script>";
                        }
                    } else {
                        echo "<script>Swal.fire('Error', 'Failed to prepare the update statement.', 'error');</script>";
                    }
                }
            }
        } else {
            echo "<script>Swal.fire('Error', 'The password reset link has expired.', 'error');</script>";
        }
    } else {
        echo "<script>Swal.fire('Error', 'Invalid or expired token.', 'error');</script>";
    }
} else {
    echo "<script>Swal.fire('Error', 'No token provided.', 'error');</script>";
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

    <title>Bantayan Island Restobar - Reset Password</title>
    <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Custom fonts for this template-->

    <!-- Include SweetAlert2 library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .login-container h4 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        }

        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
            color: #ffffff;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #d58512;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>

    <!-- Reset Password Form -->
    <div class="login-container">
        
        <h4>Set a New Password</h4>
        <form method="post">
            <div class="form-group">
                <label for="new-password">Enter New Password</label>
                <input type="password" id="new-password" name="new-password" class="form-control" required>
            </div>
            <button type="submit" name="reset-password" class="btn btn-warning btn-block">Reset Password</button><br>
            <a href="index.php" class="btn btn-warning btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
