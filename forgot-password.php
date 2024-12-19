<?php
// Database connection
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db';  // Replace with the actual password
$dbname = 'u510162695_birms_db';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Generate a secure token
    $token = bin2hex(random_bytes(32)); // 64-character secure token
    $expires_at = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token valid for 1 hour

    // Check if the email exists in the users table
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("No account associated with this email.");
    }

    // Insert token into password_resets table
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $expires_at);
    $stmt->execute();

    // Create a reset link
    $reset_link = "https://bantayanrestobars.com/reset_password.php?token=" . $token;

    // Send the email
    $to = $email;
    $subject = "Password Reset Request";
    $message = "Click the link below to reset your password:\n\n$reset_link\n\nThis link is valid for 1 hour.";
    $headers = "From: Bantayan Restobars";

    if (mail($to, $subject, $message, $headers)) {
        echo "Password reset link sent to your email.";
    } else {
        echo "Failed to send email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Request Password Reset</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form method="POST" action="request_reset.php">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
