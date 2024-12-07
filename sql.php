<?php
// Database connection
$host = "127.0.0.1";
$user = "u510162695_birms_db";
$password = "1Birms_db";
$db_name = "u510162695_birms_db";

// Create connection
$conn = new mysqli($host, $user, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to display tables and their data
function display_tables_and_data($conn) {
    // Get all tables in the database
    $tables_result = $conn->query("SHOW TABLES");

    while ($table = $tables_result->fetch_array(MYSQLI_ASSOC)) {
        $table_name = $table['Tables_in_' . $GLOBALS['db_name']];
        echo "<h2>Table: $table_name</h2>";
        
        // Get all rows in the current table
        $data_result = $conn->query("SELECT * FROM $table_name");
        
        // Display table data
        if ($data_result->num_rows > 0) {
            echo "<table border='1'><tr>";
            
            // Display table headers
            $fields_info = $data_result->fetch_fields();
            foreach ($fields_info as $field) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "<th>Actions</th>"; // Delete button header
            echo "</tr>";
            
            // Display table rows
            while ($row = $data_result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "<td><form method='post' action=''><button type='submit' name='delete' value='$table_name|{$row[$fields_info[0]->name]}'>Delete</button></form></td>"; // Delete button
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data available in this table.";
        }
    }
}

// Handle delete operation
if (isset($_POST['delete'])) {
    $delete_info = explode('|', $_POST['delete']);
    $table_name = $delete_info[0];
    $id_value = $delete_info[1];
    
    // Assuming the first column is the primary key
    $conn->query("DELETE FROM $table_name WHERE {$fields_info[0]->name} = '$id_value'");
    echo "Record deleted from $table_name with ID: $id_value<br>";
}

// Display tables and data
display_tables_and_data($conn);

// Close connection
$conn->close();
?>
