<?php
$servername = "localhost";
$username = "u510162695_birms_db";
$password = "1Birms_db";
$dbname = "u510162695_birms_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
// Include database connection file
require_once 'db_connection.php';

// Function to generate a secure random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length)); // Generates a random hex token
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize input
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        die('Error: All fields are required.');
    }

    if (!isValidEmail($email)) {
        die('Error: Invalid email format.');
    }

    try {
        // Check if the email already exists
        $checkStmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $checkStmt->execute([':email' => $email]);

        if ($checkStmt->rowCount() > 0) {
            die('Error: Email is already registered.');
        }

        // Hash the password securely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Generate a verification token
        $verification_token = generateToken();

        // Insert the user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_token) 
                               VALUES (:username, :email, :password, :token)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password,
            ':token' => $verification_token
        ]);

        // Send verification email
        $verify_link = "http://yourwebsite.com/verify.php?token=" . $verification_token;
        $subject = "Verify Your Account";
        $message = "Hello $username,\n\nPlease verify your account by clicking the link below:\n$verify_link\n\nThank you!";
        $headers = "From: no-reply@yourwebsite.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "Success: A verification email has been sent to your email address.";
        } else {
            echo "Error: Failed to send verification email.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<?php
// Include database connection file
require_once 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Check if the token exists in the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = :token AND is_verified = 0");
        $stmt->execute([':token' => $token]);

        if ($stmt->rowCount() > 0) {
            // Verify the user
            $updateStmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = :token");
            $updateStmt->execute([':token' => $token]);
            echo "Success: Your account has been verified!";
        } else {
            echo "Error: Invalid or expired verification token.";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    echo "Error: No verification token provided.";
}
?>
