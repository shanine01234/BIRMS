<?php
require_once('inc/header.php');
require 'vendor/autoload.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify the token and check expiration
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Token is valid, show reset password form
        if (isset($_POST['reset-password'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT); // Hash the new password
            
            // Update the password and clear the reset token
            $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $update_stmt->bind_param("ss", $new_password, $token);
            $update_stmt->execute();
            
            echo "<script>
                    Swal.fire('Success', 'Your password has been reset successfully.', 'success');
                  </script>";
        }
    } else {
        // Invalid or expired token
        echo "<script>
                Swal.fire('Error', 'This password reset link is invalid or has expired.', 'error');
              </script>";
        exit();
    }
} else {
    echo "<script>
            Swal.fire('Error', 'Invalid request.', 'error');
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
</head>
<body>
    <div class="reset-password-container">
        <h4>Enter New Password</h4>
        <form method="post">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" name="reset-password" class="btn btn-primary btn-block">Reset Password</button>
        </form>
    </div>
</body>
</html>
