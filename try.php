<?php
// Database connection details
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db'; // Replace with the actual password
$dbname = 'u510162695_birms_db';

try {
    // Create connection using MySQLi
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to add a 'status' column
    $sql = "ALTER TABLE users ADD COLUMN contact VARCHAR(50) NOT NULL DEFAULT 'active'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Column 'status' added successfully to the 'users' table.";
    } else {
        echo "Error adding column: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
