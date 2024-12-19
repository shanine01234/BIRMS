<!-- forgot-password.php -->
<?php
require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['forgot-password'])) {
    $email = trim($_POST['email']);
    
    try {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $reset_token_at = date('Y-m-d H:i:s');
            
            // Save token to database
            $update = $conn->prepare("UPDATE users SET token = ?, reset_token_at = ? WHERE email = ?");
            $update->bind_param("sss", $token, $reset_token_at, $email);
            $update->execute();
            
            // Configure PHPMailer
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Update with your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com'; // Update with your email
            $mail->Password = 'your-app-password'; // Update with your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Set email content
            $mail->setFrom('your-email@gmail.com', 'Bantayan Island Restobar');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            
            $reset_link = "http://your-domain.com/reset-password.php?token=" . $token;
            $mail->Body = "
                <h2>Password Reset Request</h2>
                <p>Click the link below to reset your password. This link will expire in 1 hour.</p>
                <p><a href='$reset_link'>Reset Password</a></p>
                <p>If you didn't request this, please ignore this email.</p>
            ";
            
            $mail->send();
            echo "<script>Swal.fire('Success', 'Password reset instructions have been sent to your email.', 'success');</script>";
            
        } else {
            // Don't reveal if email exists or not for security
            echo "<script>Swal.fire('Notice', 'If this email exists in our system, you will receive reset instructions.', 'info');</script>";
        }
    } catch (Exception $e) {
        echo "<script>Swal.fire('Error', 'An error occurred. Please try again later.', 'error');</script>";
        error_log("Password reset error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Bantayan Island Restobar</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .container { max-width: 400px; margin-top: 50px; }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="card shadow">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Forgot Password</h4>
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" name="forgot-password" class="btn btn-primary btn-block">Send Reset Link</button>
                    <a href="login.php" class="btn btn-link btn-block">Back to Login</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<!-- reset-password.php -->
<?php
require_once('inc/header.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if token exists
if (!isset($_GET['token'])) {
    echo "<script>
        Swal.fire({
            title: 'Error',
            text: 'Invalid reset link.',
            icon: 'error'
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>";
    exit;
}

$token = trim($_GET['token']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset-password'])) {
    try {
        // Validate password
        $new_password = trim($_POST['new-password']);
        $confirm_password = trim($_POST['confirm-password']);
        
        if (strlen($new_password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }
        
        if ($new_password !== $confirm_password) {
            throw new Exception("Passwords do not match.");
        }
        
        // Verify token and get user
        $stmt = $conn->prepare("SELECT id, email FROM users WHERE token = ? AND reset_token_at > NOW() - INTERVAL 1 HOUR");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            throw new Exception("This reset link has expired. Please request a new one.");
        }
        
        $user = $result->fetch_assoc();
        
        // Hash new password and update user
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ?, token = NULL, reset_token_at = NULL WHERE id = ?");
        $update->bind_param("si", $hashed_password, $user['id']);
        
        if (!$update->execute()) {
            throw new Exception("Failed to update password. Please try again.");
        }
        
        echo "<script>
            Swal.fire({
                title: 'Success',
                text: 'Your password has been reset successfully.',
                icon: 'success'
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>";
        exit;
        
    } catch (Exception $e) {
        echo "<script>Swal.fire('Error', '" . addslashes($e->getMessage()) . "', 'error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Bantayan Island Restobar</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .container { max-width: 400px; margin-top: 50px; }
        .password-requirements {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container">
        <div class="card shadow">
            <div class="card-body p-4">
                <h4 class="text-center mb-4">Reset Password</h4>
                <form method="post" id="resetForm">
                    <div class="form-group">
                        <label for="new-password">New Password</label>
                        <input type="password" class="form-control" id="new-password" name="new-password" required minlength="6">
                        <div class="password-requirements">
                            Password must be at least 6 characters long
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required>
                    </div>
                    <button type="submit" name="reset-password" class="btn btn-primary btn-block">Reset Password</button>
                    <a href="login.php" class="btn btn-link btn-block">Back to Login</a>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        const password = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire('Error', 'Passwords do not match.', 'error');
        }
    });
    </script>
</body>
</html>