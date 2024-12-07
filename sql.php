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

// Handle delete request (multiple selected rows)
if (isset($_POST['delete_selected']) && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];
    $delete_ids = array_map('intval', $delete_ids); // Sanitize the array to integers

    // Prepare the SQL query to delete multiple rows
    $delete_ids_placeholder = implode(',', $delete_ids);
    $sql = "DELETE FROM {$_POST['table_name']} WHERE id IN ($delete_ids_placeholder)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Selected records deleted successfully.";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
}

// Function to display table
function displayTable($conn, $tableName) {
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div style='margin-bottom: 20px;'>";
        echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";
        
        // Start the form for bulk delete
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='table_name' value='$tableName'>"; // Pass table name for deletion
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        
        // Get field information for headers
        $fields = $result->fetch_fields();
        echo "<tr>";
        echo "<th style='background-color: #f2f2f2;'>Select</th>"; // Add Select column for checkboxes
        foreach ($fields as $field) {
            echo "<th style='background-color: #f2f2f2;'>" . htmlspecialchars($field->name) . "</th>";
        }
        echo "<th style='background-color: #f2f2f2;'>Delete</th>";  // Add Delete column
        echo "</tr>";
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            
            // Add checkbox for selection
            echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>";
            
            foreach ($row as $key => $value) {
                // Mask password for security
                if (strpos(strtolower($key), 'password') !== false) {
                    echo "<td>[MASKED]</td>";
                } else {
                    // Sanitize the value to prevent XSS
                    echo "<td>" . htmlspecialchars($value ?? "NULL") . "</td>";
                }
            }

            // Add delete button (in this case, it's for visual purposes, as bulk deletion is handled via checkboxes)
            echo "<td></td>"; 
            echo "</tr>";
        }
        echo "</table>";

        // Add a "Delete Selected" button
        echo "<br><input type='submit' name='delete_selected' value='Delete Selected' onclick='return confirm(\"Are you sure you want to delete the selected rows?\");'>";
        echo "</form>";

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

input[type="submit"] {
    background-color: #f44336; /* Red color for delete button */
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #d32f2f;
}

input[type="checkbox"] {
    margin: 0;
}
</style>
