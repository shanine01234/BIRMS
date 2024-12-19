<?php
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db';  // Replace with the actual password
$dbname = 'u510162695_birms_db';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL statement to create the password_resets table
$sql = "CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Table 'password_resets' created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// Close the connection
$conn->close();
?>
