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
            echo "<script>Swal.fire('Error', 'New password must be at least 8 characters long.', 'error');</script>";
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
                    echo "<script>Swal.fire('Success', 'Account updated successfully!', 'success');</script>";
                } else {
                    echo "<script>Swal.fire('Error', 'Current password is incorrect.', 'error');</script>";
                }
            } else {
                echo "<script>Swal.fire('Error', 'New password and confirm password do not match.', 'error');</script>";
            }
        }
    } else {
        echo "<script>Swal.fire('Error', 'All fields are required.', 'error');</script>";
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
    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); /* Darker shadow for depth */
            transform-style: preserve-3d; /* Enable 3D space */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition on hover */
        }

        .form-container:hover {
            transform: translateY(-5px) rotateX(5deg) rotateY(5deg); /* Slight rotation for 3D effect */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); /* Deeper shadow on hover */
        }


        /* Password strength bar */
        .strength-bar {
            height: 5px;
            margin-top: 5px;
            background-color: #ddd;
            border-radius: 2px;
        }

        .strength-bar div {
            height: 100%;
            width: 0;
            border-radius: 2px;
        }

        /* Password match bar */
        .match-bar {
            height: 5px;
            margin-top: 5px;
            background-color: #ddd;
            border-radius: 2px;
        }

        .match-bar div {
            height: 100%;
            width: 0;
            border-radius: 2px;
        }

        /* Positioning of the eye icon */
        .password-container {
            position: relative;
        }

        .password-container i {
            position: absolute;
            right: 10px;
            top: 36%;
            transform: translateY(-50%);
            cursor: pointer;
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
                <div class="password-container">
                    <input type="password" name="current_password" id="current_password" required>
                    <i class="fas fa-eye" id="toggle_current_password"></i>
                </div><br>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
                <i class="fas fa-eye" id="toggle_new_password" style="position: absolute;right: 29px;top: 83%;transform: translateY(-172px);cursor: pointer;"></i>
                <div class="strength-bar" id="strength-bar"><div></div></div><br>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <i class="fas fa-eye" id="toggle_confirm_password" style="
                    position: absolute;
                    transform: translateY(-50px);
                    right: 369px;
                    top: 100.5%;
                    cursor: pointer;
                "></i><div class="match-bar" id="match-bar"><div></div></div><br>


                <button type="submit">Update Account</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('toggle_current_password').addEventListener('click', function() {
            const passwordField = document.getElementById('current_password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggle_new_password').addEventListener('click', function() {
            const passwordField = document.getElementById('new_password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });
        document.getElementById('toggle_confirm_password').addEventListener('click', function() {
            const passwordField = document.getElementById('confirm_password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash');
        });

        // New password strength indicator
        document.getElementById('new_password').addEventListener('input', function() {
            const strengthBar = document.getElementById('strength-bar').firstElementChild;
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            if (/[^\w\s]/.test(password)) {
                // Show SweetAlert if invalid characters are found
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid characters!',
                    text: 'Password contains disallowed symbols. Only letters, numbers, and basic symbols are allowed.',
                });
            }

            switch (strength) {
                case 1:
                    strengthBar.style.width = '25%';
                    strengthBar.style.backgroundColor = 'red';
                    break;
                case 2:
                    strengthBar.style.width = '50%';
                    strengthBar.style.backgroundColor = 'orange';
                    break;
                case 3:
                    strengthBar.style.width = '75%';
                    strengthBar.style.backgroundColor = 'yellow';
                    break;
                case 4:
                    strengthBar.style.width = '100%';
                    strengthBar.style.backgroundColor = 'green';
                    break;
                default:
                    strengthBar.style.width = '0';
                    break;
            }
        });

        // Confirm new password match indicator
        document.getElementById('confirm_password').addEventListener('input', function() {
            const matchBar = document.getElementById('match-bar').firstElementChild;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;

            if (newPassword === confirmPassword) {
                matchBar.style.width = '100%';
                matchBar.style.backgroundColor = 'green';
            } else {
                matchBar.style.width = '0';
            }

            // Alert if passwords don't match
            if (newPassword !== confirmPassword && confirmPassword !== '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Passwords do not match',
                    text: 'Please make sure the new password and confirm password match.',
                });
            }
        });
    </script>
</body>
</html>

<!-- Include SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
