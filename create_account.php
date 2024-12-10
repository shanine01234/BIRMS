<?php
require 'vendor/autoload.php'; // Include PHPMailer's autoload file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";


header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load sensitive data securely using .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection
$host = $_ENV['127.0.0.1'];
$user = $_ENV['u510162695_birms_db'];
$password = $_ENV['1Birms_db'];
$db_name = $_ENV['u510162695_birms_db'];

// Create connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $contact_num = htmlspecialchars(trim($_POST['contact_num']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms = isset($_POST['terms']) ? 1 : 0;

    if (empty($name) || empty($contact_num) || empty($email) || empty($password) || empty($confirm_password)) {
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
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        echo json_encode(["message" => "This email is already registered."]);
        exit;
    }
    $checkEmail->close();

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate a 5-digit verification code
    $verification_code = random_int(10000, 99999);

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, contact, code, status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 0; // 0 for unverified
    $stmt->bind_param("sssssi", $name, $email, $hashed_password, $contact_num, $verification_code, $status);

    if ($stmt->execute()) {
        // Send verification email
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->SMTPDebug = 0; // Disable verbose debug output
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shaninezaspa179@gmail.com';
            $mail->Password = 'hglesxkasgmryjxq'; // Ensure this is correct and secure
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            //Recipients
            $mail->setFrom($_ENV['SMTP_USER'], 'Bantayan Island Restobar');
            $mail->addAddress($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body    = "Dear $name,<br><br>Your verification code is: <strong>$verification_code</strong><br><br>Please use this code to verify your email address.";

            $mail->send();
            echo json_encode(["message" => "Account created successfully. Please verify your email."]);
        } catch (Exception $e) {
            echo json_encode(["message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>