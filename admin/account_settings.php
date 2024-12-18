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
            $userId = $_SESSION['id']; // Assume the user ID is stored in session
            
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
                echo "<div class='message success'>Password updated successfully!</div>";
            } else {
                echo "<div class='message error'>Current password is incorrect.</div>";
            }
        } else {
            echo "<div class='message error'>New password and confirm password do not match.</div>";
        }
    } else {
        echo "<div class='message error'>All fields are required.</div>";
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
    <!-- Include Font Awesome for the icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Base styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="password"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .message.success {
            background-color: #28a745;
            color: white;
        }

        .message.error {
            background-color: #dc3545;
            color: white;
        }

        /* Button Design */
        .btn-grad {
            background-image: linear-gradient(to right, #f7ff00 0%, #db36a4 51%, #f7ff00 100%);
            margin: 10px;
            padding: 15px 45px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: black;
            font-weight: bolder;
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            display: block;
            text-decoration: none;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .btn-grad:hover {
            background-position: right center;
            color: #fff;
        }

        /* Icon Style */
        .btn-grad i {
            margin-right: 8px; /* Space between icon and text */
        }

        /* Form container styling */
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

    </style>
</head>
<body>
    <!-- Back Button with Icon -->
    <a href="./index.php?page=dashboard" class="btn-grad" style="width: 150px;">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="container">
        <h1>Change Your Password</h1>
        <div class="form-container">
            <form method="POST">
                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" required><br>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required><br>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required><br>

                <button type="submit">Update Password</button>
            </form>
        </div>
    </div>
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
