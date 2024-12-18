<?php
// Start the session (only if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection setup using provided credentials
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db';  // Replace with the actual password
$dbname = 'u510162695_birms_db';

try {
    // Establish PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}

// Initialize dataOperation with the database connection
$dataOperation = new dataOperation($pdo);

// Check if the form is submitted for account settings (e.g., password update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Make sure to validate and sanitize input data (e.g., for password)
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Sanitize inputs to prevent SQL injection
        $currentPassword = trim($currentPassword);
        $newPassword = trim($newPassword);
        $confirmPassword = trim($confirmPassword);

        // Check if the new password and confirm password match
        if ($newPassword === $confirmPassword) {
            // Check if the current password is correct (e.g., for a logged-in user)
            $userId = $_SESSION['user_id']; // Assume the user ID is stored in session
            
            // Assuming a method in dataOperation to fetch the user's current password
            $existingPassword = $dataOperation->getUserPasswordById($userId);

            // Verify the current password (you should use password_verify for hashed passwords)
            if (password_verify($currentPassword, $existingPassword)) {
                // If the password is correct, update it
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password in the database
                $stmt = $pdo->prepare("UPDATE admin SET password = :password WHERE id = :id");
                $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);

                // Success message
                echo "Password updated successfully!";
            } else {
                echo "Current password is incorrect.";
            }
        } else {
            echo "New password and confirm password do not match.";
        }
    } else {
        echo "All fields are required.";
    }
}

// Example of the form for account settings
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
</head>
<body>
    <h1>Change Password</h1>
    <form method="POST">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit">Update Password</button>
    </form>
</body>
</html>

<?php
// Data operation class that handles the DB logic
class dataOperation {
    private $pdo;

    // Constructor accepts a PDO object for DB operations
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch user password by user ID
    public function getUserPasswordById($userId) {
        $stmt = $this->pdo->prepare("SELECT password FROM admin WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['password'] : null;
    }
}
?>
