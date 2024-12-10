<?php
require 'vendor/autoload.php'; // Include PHPMailer's autoload file

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
    $contact = htmlspecialchars(trim($_POST['contact']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms = isset($_POST['terms']) ? 1 : 0;

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

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, contact_num, status) VALUES (?, ?, ?, ?, 0)");
    $status = 0; // 0 for unverified
    $stmt->bind_param("ssssi", $name, $email, $hashed_password, $contact, $status);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Account created successfully."]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>