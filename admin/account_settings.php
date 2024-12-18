<?php
require_once('../inc/function.php');
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $new_name = trim($_POST['name']);
    $new_password = trim($_POST['password']);

    if (!empty($new_name)) {
        // Update name in the database
        $stmt = $oop->pdo->prepare("UPDATE users SET name = :name WHERE id = :id");
        $stmt->bindParam(':name', $new_name);
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
    }

    if (!empty($new_password)) {
        // Hash the new password using Argon2i
        $hashed_password = password_hash($new_password, PASSWORD_ARGON2I);

        // Update password in the database
        $stmt = $oop->pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $_SESSION['id']);
        $stmt->execute();
    }

    header('Location: account_settings.php'); // Redirect after update
    exit();
}

// Fetch the current name for pre-filling the form
$stmt = $oop->pdo->prepare("SELECT name FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h1 class="h3 mb-4 text-gray-800">Account Settings</h1>

        <form method="POST" action="account_settings.php">
            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                <small class="form-text text-muted">Leave blank if you don't want to change the password.</small>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
