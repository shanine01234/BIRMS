<?php
require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Initialize response array
$response = [
    'status' => 'error',
    'message' => '',
    'redirect' => ''
];

// Check if token is set
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);

    try {
        // Verify the database connection
        if (!isset($conn)) {
            throw new Exception("Database connection error.");
        }

        // Verify token from database with prepared statement
        $stmt = $conn->prepare("SELECT id, email, token, reset_token_at FROM users WHERE token = ? AND reset_token_at IS NOT NULL LIMIT 1");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Invalid or expired token.");
        }

        $user = $result->fetch_assoc();
        
        // Check if the reset token has expired (1 hour expiration)
        if (strtotime($user['reset_token_at']) <= time() - 3600) {
            // Clear expired token
            $clear_stmt = $conn->prepare("UPDATE users SET token = NULL, reset_token_at = NULL WHERE id = ?");
            $clear_stmt->bind_param("i", $user['id']);
            $clear_stmt->execute();
            throw new Exception("The password reset link has expired. Please request a new one.");
        }

        // Handle password reset form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset-password'])) {
            if (!isset($_POST['new-password']) || empty($_POST['new-password'])) {
                throw new Exception("Please enter a new password.");
            }

            $new_password_raw = trim($_POST['new-password']);
            
            // Validate password strength
            if (strlen($new_password_raw) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }

            if (!preg_match("/[A-Z]/", $new_password_raw)) {
                throw new Exception("Password must contain at least one uppercase letter.");
            }

            if (!preg_match("/[a-z]/", $new_password_raw)) {
                throw new Exception("Password must contain at least one lowercase letter.");
            }

            if (!preg_match("/[0-9]/", $new_password_raw)) {
                throw new Exception("Password must contain at least one number.");
            }

            // Hash the password securely using Argon2id (newer version than Argon2i)
            $new_password = password_hash($new_password_raw, PASSWORD_ARGON2ID);

            // Update the user's password and reset the token
            $update_stmt = $conn->prepare("UPDATE users SET password = ?, token = NULL, reset_token_at = NULL WHERE id = ? AND token = ?");
            if (!$update_stmt) {
                throw new Exception("Failed to prepare update statement: " . $conn->error);
            }

            $update_stmt->bind_param("sis", $new_password, $user['id'], $token);
            
            if (!$update_stmt->execute()) {
                throw new Exception("Failed to update password: " . $update_stmt->error);
            }

            if ($update_stmt->affected_rows === 0) {
                throw new Exception("No changes were made. Please try again.");
            }

            // Set success response
            $response['status'] = 'success';
            $response['message'] = 'Your password has been reset successfully. You can now log in.';
            $response['redirect'] = 'login.php';
        }

    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'No token provided.';
}

// Output the response if this is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<script>
        Swal.fire({
            title: '" . ($response['status'] === 'success' ? 'Success' : 'Error') . "',
            text: '" . addslashes($response['message']) . "',
            icon: '" . $response['status'] . "'"
        . ($response['redirect'] ? ".then(() => { window.location.href = '" . $response['redirect'] . "'; })" : "") .
        ");
    </script>";
    if ($response['status'] === 'success') {
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Bantayan Island Restobar - Reset Password</title>
    
    <!-- Security headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' cdn.jsdelivr.net cdnjs.cloudflare.com; script-src 'self' 'unsafe-inline' cdn.jsdelivr.net cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' cdnjs.cloudflare.com;">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    
    <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg">
    
    <!-- External resources -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .password-requirements {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
            color: #ffffff;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #d58512;
            transform: translateY(-1px);
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h4>Set a New Password</h4>
        <form method="post" id="resetPasswordForm">
            <div class="form-group">
                <label for="new-password">Enter New Password</label>
                <input type="password" id="new-password" name="new-password" class="form-control" required 
                       pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}"
                       title="Password must be at least 8 characters long and include uppercase, lowercase, and numbers">
                <div class="password-requirements">
                    Password must contain:
                    <ul>
                        <li>At least 8 characters</li>
                        <li>At least one uppercase letter</li>
                        <li>At least one lowercase letter</li>
                        <li>At least one number</li>
                    </ul>
                </div>
            </div>
            <button type="submit" name="reset-password" class="btn btn-warning btn-block">Reset Password</button><br>
            <a href="index.php" class="btn btn-warning btn-back">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </form>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('new-password').value;
        if (password.length < 8 || 
            !/[A-Z]/.test(password) || 
            !/[a-z]/.test(password) || 
            !/[0-9]/.test(password)) {
            e.preventDefault();
            Swal.fire({
                title: 'Error',
                text: 'Please ensure your password meets all requirements.',
                icon: 'error'
            });
        }
    });
    </script>
</body>
</html>