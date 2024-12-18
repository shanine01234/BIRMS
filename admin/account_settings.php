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

// Fetch user details (username) when the page loads
$userId = $_SESSION['id']; // Assume the user ID is stored in session
$userDetails = $dataOperation->getUserDetailsById($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data (username, current password, new password, confirm password)
    if (isset($_POST['username'], $_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $username = trim($_POST['username']);
        $currentPassword = trim($_POST['current_password']);
        $newPassword = trim($_POST['new_password']);
        $confirmPassword = trim($_POST['confirm_password']);

        // Password length validation (minimum 8 characters)
        if (strlen($newPassword) < 8) {
            echo "<div class='message error'>New password must be at least 8 characters long.</div>";
        } else {
            // Check if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Check if the current password is correct (e.g., for a logged-in user)
                $existingPassword = $dataOperation->getUserPasswordById($userId);

                // Verify the current password (you should use password_verify for hashed passwords)
                if (password_verify($currentPassword, $existingPassword)) {
                    // If the password is correct, update it
                    $hashedPassword = password_hash($newPassword, PASSWORD_ARGON2I);

                    // Update both the username and password in the database
                    $stmt = $pdo->prepare("UPDATE admin SET username = :username, password = :password WHERE id = :id");
                    $stmt->execute([
                        'username' => $username, 
                        'password' => $hashedPassword, 
                        'id' => $userId
                    ]);

                    // Success message
                    echo "<div class='message success'>Account updated successfully!</div>";
                } else {
                    echo "<div class='message error'>Current password is incorrect.</div>";
                }
            } else {
                echo "<div class='message error'>New password and confirm password do not match.</div>";
            }
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

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="password"]:focus {
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

        /* Password strength bar styling */
        .strength-bar {
            height: 8px;
            margin-top: 5px;
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            display: none;
        }

        .strength-bar div {
            height: 100%;
            border-radius: 5px;
        }

        .strength-weak {
            background-color: #ff4d4d;
            width: 33%;
        }

        .strength-medium {
            background-color: #ffcc00;
            width: 66%;
        }

        .strength-strong {
            background-color: #28a745;
            width: 100%;
        }

        /* Match bar for confirm password */
        .match-bar {
            height: 8px;
            margin-top: 5px;
            width: 100%;
            background-color: #ddd;
            border-radius: 5px;
            display: none;
        }

        .match-bar.match {
            background-color: #28a745;
        }

        .match-bar.no-match {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Back Button with Icon -->
    <a href="./index.php?page=dashboard" class="btn-grad" style="width: 150px;">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="container">
        <h1>Change Your Account Details</h1>
        <div class="form-container">
            <form method="POST">
                <!-- Display the current username in the input field -->
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($userDetails['username'] ?? '') ?>" required><br>

                <label for="current_password">Current Password:</label>
                <div style="position: relative;">
                    <input type="password" name="current_password" id="current_password" required><br>
                    <img src="https://img.icons8.com/?size=100&id=LKTmVnYtDvRk&format=png&color=000000" alt="Show Password" onclick="togglePassword('current_password')" style="position: absolute; right: 10px; top: 10px; cursor: pointer;">
                </div>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required oninput="checkPasswordStrength()"><br>
                <div class="strength-bar" id="passwordStrengthBar"></div>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required oninput="checkPasswordMatch()"><br>
                <div class="match-bar" id="passwordMatchBar"></div>

                <button type="submit">Update Account</button>
            </form>
        </div>
    </div>

    <script>
        // Function to toggle password visibility
        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        // Function to check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const strengthBar = document.getElementById('passwordStrengthBar');
            let strength = 0;

            if (password.length >= 8) {
                strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;
            }

            strengthBar.style.display = 'block';

            if (strength === 1) {
                strengthBar.innerHTML = '<div class="strength-weak"></div>';
            } else if (strength === 2) {
                strengthBar.innerHTML = '<div class="strength-medium"></div>';
            } else if (strength >= 3) {
                strengthBar.innerHTML = '<div class="strength-strong"></div>';
            }
        }

        // Function to check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const matchBar = document.getElementById('passwordMatchBar');

            if (password === confirmPassword && confirmPassword !== "") {
                matchBar.style.display = 'block';
                matchBar.className = "match-bar match";
            } else {
                matchBar.style.display = 'block';
                matchBar.className = "match-bar no-match";
            }
        }
    </script>
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

    // Fetch user details (username) by user ID
    public function getUserDetailsById($userId) {
        $stmt = $this->pdo->prepare("SELECT username, password FROM admin WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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