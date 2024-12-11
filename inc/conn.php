<?php
session_start();

class Connection {
    private $host = "127.0.0.1";
    private $user = "u510162695_birms_db";
    private $password = "1Birms_db";
    private $db_name = "u510162695_birms_db";
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);

        if (!$this->conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }

    // Method to retrieve the connection
    public function getConnection() {
        return $this->conn;
    }
}

// Create an instance of the Connection class
$database = new Connection();
$conn = $database->getConnection();
?>
