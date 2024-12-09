<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$host = "127.0.0.1";
$user = "u510162695_birms_db";
$password = "1Birms_db";
$db_name = "u510162695_birms_db";

// Create connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms = isset($_POST['terms']) ? 1 : 0;

    // Validate input data
    if (empty($name) || empty($contact) || empty($email) || empty($password) || empty($confirm_password)) {
        echo json_encode(["message" => "All fields are required."]);
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

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate a verification code
    $verification_code = bin2hex(random_bytes(15));

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, contact, code, status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 0; // 0 for unverified
    $stmt->bind_param("sssssi", $name, $email, $hashed_password, $contact, $verification_code, $status);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(["message" => "Account created successfully. Please verify your email."]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>