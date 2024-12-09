<?php
require 'vendor/autoload.php'; // Include PHPMailer's autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    // Check if the email is already used and verified
    $stmt = $conn->prepare("SELECT status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($status);
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if ($status == 1) {
            echo json_encode(["message" => "This email is already registered and verified."]);
            exit;
        }
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Generate a 5-digit verification code
    $verification_code = random_int(10000, 99999);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, contact, code, status) VALUES (?, ?, ?, ?, ?, ?)");
    $status = 0; // 0 for unverified
    $stmt->bind_param("sssssi", $name, $email, $hashed_password, $contact, $verification_code, $status);

    // Execute the statement
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
            $mail->setFrom('shaninezaspa179@gmail.com', 'Bantayan Restobar');
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

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>