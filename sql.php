<?php
// Connect to database
$host = '127.0.0.1';
$username = 'u510162695_birms_db';
$password = '1Birms_db';  // Replace with the actual password
$dbname = 'u510162695_birms_db';

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete_id']) && isset($_GET['table_name'])) {
    $delete_id = (int)$_GET['delete_id']; // Ensure it's an integer
    $table_name = $_GET['table_name'];
    
    // Assuming each table has an 'id' column (you may need to adjust for other table structures)
    $sql = "DELETE FROM $table_name WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Function to display table
function displayTable($conn, $tableName) {
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div style='margin-bottom: 20px;'>";
        echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        
        // Get field information for headers
        $fields = $result->fetch_fields();
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<th style='background-color: #f2f2f2;'>" . htmlspecialchars($field->name) . "</th>";
        }
        echo "<th style='background-color: #f2f2f2;'>Delete</th>";  // Add Delete column
        echo "</tr>";
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                // Mask password for security
                if (strpos(strtolower($key), 'password') !== false) {
                    echo "<td>[MASKED]</td>";
                } else {
                    // Sanitize the value to prevent XSS
                    echo "<td>" . htmlspecialchars($value ?? "NULL") . "</td>";
                }
            }

            // Add delete button
            $deleteUrl = "?delete_id=" . $row['id'] . "&table_name=" . $tableName;
            echo "<td><a href='$deleteUrl' onclick='return confirm(\"Are you sure you want to delete this row?\");'>Delete</a></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "0 results found in $tableName table";
    }
}

// Display tables
displayTable($conn, 'admin');
displayTable($conn, 'branches');
displayTable($conn, 'cart');
displayTable($conn, 'menu');
displayTable($conn, 'orders');
displayTable($conn, 'orders1');
displayTable($conn, 'orders2');
displayTable($conn, 'order_items');
displayTable($conn, 'owner');
displayTable($conn, 'restobar');
displayTable($conn, 'restobar_details');
displayTable($conn, 'users');

$conn->close();
?>

<style>
table {
    border-collapse: collapse;
    width: 100%;
    margin: 20px 0;
    font-family: Arial, sans-serif;
}

h2 {
    color: #333;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
}

th, td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f5f5f5;
}

a {
    color: #f44336; /* Red color for delete link */
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
</style>
