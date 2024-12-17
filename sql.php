<?php
// Database connection details
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db';  // Replace with the actual password
$dbname = 'u510162695_birms_db';

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to add the 'proof' column to the 'orders' table
$sql = "ALTER TABLE orders ADD COLUMN proof VARCHAR(255)";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Column 'proof' added successfully.";
} else {
    echo "Error adding column: " . $conn->error;
}

// Close the connection
$conn->close();
?>