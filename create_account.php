<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure this path is correct

// Database connection function
function connectDatabase() {
    $host = '127.0.0.1';
    $user = 'u510162695_birms_db';
    $password = '1Birms_db';
    $db_name = 'u510162695_birms_db';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Database Connection Failed: " . $e->getMessage());
        die("Connection failed: " . $e->getMessage());
    }
}

// Function to generate a 5-digit verification code
function generateFiveDigitCode() {
    return str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
}

// Function to send verification email
function sendVerificationEmail($email) {
    try {
        // Generate and save code
        $verificationCode = generateFiveDigitCode();
        
        $conn = connectDatabase();
        $stmt = $conn->prepare("UPDATE users SET code = :code WHERE email = :email");
        $stmt->execute([
            'code' => $verificationCode,
            'email' => $email
        ]);

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // Disable verbose debug output
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shaninezaspa179@gmail.com';
        $mail->Password = 'hglesxkasgmryjxq'; // Ensure this is correct and secure
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('shaninezaspa179@gmail.com', 'Bantayan Island Restobar');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification Code';
        $mail->Body = "
            <h2>Email Verification</h2>
            <p>Your verification code is: <strong>$verificationCode</strong></p>
            <p>Please use this code to verify your email address.</p>
            <p>If you didn't request this code, please ignore this email.</p>
        ";

        $mail->send();
        return [
            'success' => true,
            'message' => 'Verification code sent successfully'
        ];

    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return [
            'success' => false,
            'message' => 'Failed to send verification code: ' . $mail->ErrorInfo
        ];
    }
}

// Main registration process
function registerUser($username, $email, $password, $contact) {
    try {
        $conn = connectDatabase();

        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $checkEmail->execute(['email' => $email]);
        if ($checkEmail->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Email already exists'];
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL to insert user
        $stmt = $conn->prepare("INSERT INTO users (
            username, 
            email, 
            password, 
            contact, 
            status,
            code
        ) VALUES (
            :username, 
            :email, 
            :password, 
            :contact, 
            0,
            NULL
        )");

        // Execute the statement
        $result = $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'contact' => $contact
        ]);

        if ($result) {
            // Send verification email
            $emailResult = sendVerificationEmail($email);
            
            if ($emailResult['success']) {
                return [
                    'success' => true, 
                    'message' => 'Registration successful. Please check your email for verification code.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Registration successful but failed to send verification email.'
                ];
            }
        } else {
            error_log("SQL Error: " . implode(" ", $stmt->errorInfo()));
            return ['success' => false, 'message' => 'Registration failed'];
        }
    } catch(PDOException $e) {
        error_log("Registration Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $username = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $contact = filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = $_POST['terms'] ?? '';

    // Validation array
    $errors = [];

    // Perform validations
    if (empty($username)) {
        $errors['name'] = "Name is required";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($contact) || !preg_match("/^[0-9]{10}$/", $contact)) {
        $errors['contact'] = "Invalid contact number. Please enter 10 digits.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long";
    }

    if ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    if (empty($terms)) {
        $errors['terms'] = "You must accept the Terms and Conditions";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $registrationResult = registerUser($username, $email, $password, $contact);

        if ($registrationResult['success']) {
            // Redirect or show success message
            session_start();
            $_SESSION['registration_success'] = $registrationResult['message'];
            
            // Optionally, you can redirect to a verification page
            header("Location: verify_gmail.php");
            exit();
        } else {
            // Store errors to display on the form
            $errors['registration'] = $registrationResult['message'];
        }
    }

    // If there are errors, redirect back to the signup form with error messages
    if (!empty($errors)) {
        session_start();
        $_SESSION['signup_errors'] = $errors;
        header("Location: signup.php");
        exit();
    }
}