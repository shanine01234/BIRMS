<?php
require 'vendor/autoload.php'; // Include PHPMailer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set content type
header('Content-Type: application/json');

// Load .env file securely
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Verify .env loaded
if (!isset($_ENV['127.0.0.1'], $_ENV['u510162695_birms_db'], $_ENV['1Birms_db'], $_ENV['u510162695_birms_db'], $_ENV['SMTP_USER'], $_ENV['SMTP_PASS'])) {
    echo json_encode(["message" => "Failed to load environment variables."]);
    exit;
}

// Database configuration
$host = $_ENV['127.0.0.1'];
$user = $_ENV['u510162695_birms_db'];
$password = $_ENV['1Birms_db'];
$db_name = $_ENV['u510162695_birms_db'];

// Create database connection
$conn = new mysqli($host, $user, $password, $db_name);
if ($conn->connect_error) {
    echo json_encode(["message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $contact = htmlspecialchars(trim($_POST['contact']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Input validation
    if (empty($name) || empty($contact) || empty($email) || empty($password) || empty($confirm_password)) {
        echo json_encode(["message" => "All fields are required."]);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["message" => "Invalid email format."]);
        exit;
    }
    if ($password !== $confirm_password) {
        echo json_encode(["message" => "Passwords do not match."]);
        exit;
    }
    if (!$terms) {
        echo json_encode(["message" => "You must agree to the terms and conditions."]);
        exit;
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM tblusers WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        echo json_encode(["message" => "This email is already registered."]);
        exit;
    }
    $checkEmail->close();

    // Securely hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate a 5-digit verification code
    $verification_code = random_int(10000, 99999);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO tblusers (username, email, password, contact, code, status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 0; // 0 for unverified
    $stmt->bind_param("sssssi", $name, $email, $hashed_password, $contact, $verification_code, $status);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // Set to 2 for debugging SMTP
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email content
            $mail->setFrom($_ENV['SMTP_USER'], 'Bantayan Island Restobar');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "Dear $name,<br><br>Your verification code is: <strong>$verification_code</strong><br><br>Please use this code to verify your email address.<br><br>Thank you!<br>Bantayan Island Restobar";

            $mail->send();
            echo json_encode(["message" => "Account created successfully. Please verify your email."]);
        } catch (Exception $e) {
            echo json_encode(["message" => "Account created, but email could not be sent. Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    // Close resources
    $stmt->close();
    $conn->close();
}
?>
