<?php
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db'; // Replace with the actual password
$dbname = 'u510162695_birms_db';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// SQL query to add new columns
$sql = "ALTER TABLE users 
        ADD COLUMN token VARCHAR(255) NULL,
        ADD COLUMN reset_token_at DATETIME NULL,
        ADD COLUMN code VARCHAR(255) NULL,
        ADD COLUMN reset_code_at DATETIME NULL;";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Columns added successfully";
} else {
    echo "Error adding columns: " . $conn->error;
}

// Close the connection
$conn->close();
?>
