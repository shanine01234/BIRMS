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
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Handle AJAX request
$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['code'])) {
    $code = $data['code'];

    // Validate the code
    if (strlen($code) !== 5 || !ctype_digit($code)) {
        echo json_encode(["success" => false, "message" => "Invalid verification code."]);
        exit;
    }

    // Check if the code exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE code = ? AND status = 0");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Code exists, update the status
        $stmt->bind_result($user_id);
        $stmt->fetch();

        $update_stmt = $conn->prepare("UPDATE users SET status = 1 WHERE id = ?");
        $update_stmt->bind_param("i", $user_id);

        if ($update_stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Email verified successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating status: " . $update_stmt->error]);
        }

        $update_stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Verification code is incorrect or already used."]);
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>