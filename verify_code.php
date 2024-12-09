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
    // Concatenate the code parts
    $code = implode('', $_POST['code']);

    // Validate the code
    if (strlen($code) !== 5 || !ctype_digit($code)) {
        echo json_encode(["message" => "Invalid verification code."]);
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
            echo json_encode(["message" => "Email verified successfully."]);
        } else {
            echo json_encode(["message" => "Error updating status: " . $update_stmt->error]);
        }

        $update_stmt->close();
    } else {
        echo json_encode(["message" => "Verification code is incorrect or already used."]);
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>