<?php
require_once('../inc/function.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header('location: login.php');
    exit();
}

// Fetch current user information
$userId = $_SESSION['id'];
$query = "SELECT * FROM admin WHERE id = :id";
$stmt = $oop->pdo->prepare($query);
$stmt->bindParam(':id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the new password and confirm password match
    if ($newPassword === $confirmPassword) {
        // Hash the new password with Argon2i
        $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2I);

        // Update the username and password in the database
        $updateQuery = "UPDATE admin SET username = :username, password = :password WHERE id = :id";
        $updateStmt = $oop->pdo->prepare($updateQuery);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->bindParam(':password', $hashedPassword);
        $updateStmt->bindParam(':id', $userId);

        if ($updateStmt->execute()) {
            // Password updated successfully
            $successMessage = "Account settings updated successfully!";
        } else {
            $errorMessage = "Failed to update account settings.";
        }
    } else {
        $errorMessage = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <!-- Add your stylesheets here -->
</head>
<body>
    <div class="container">
        <h2>Account Settings</h2>

        <!-- Display success or error message -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
        <?php endif; ?>

        <!-- Account Settings Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Account</button>
        </form>
    </div>
</body>
</html>
