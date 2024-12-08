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

// Handle bulk delete request
if (isset($_POST['delete_selected']) && isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];
    $delete_ids = array_map('intval', $delete_ids); // Sanitize IDs to integers

    $table_name = $_POST['table_name'];
    $delete_ids_placeholder = implode(',', $delete_ids);
    $sql = "DELETE FROM `$table_name` WHERE id IN ($delete_ids_placeholder)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Selected records deleted successfully.";
    } else {
        echo "Error deleting records: " . $conn->error;
    }
}

// Handle "Add Column" request
if (isset($_POST['add_column'])) {
    $table_name = $_POST['table_name'];
    $column_name = trim($_POST['column_name']);
    $data_type = strtoupper(trim($_POST['data_type']));

    // Validate inputs
    if (!empty($table_name) && !empty($column_name) && !empty($data_type)) {
        $valid_data_types = ['INT', 'VARCHAR(255)', 'TEXT', 'DATE', 'BOOLEAN', 'FLOAT']; // Allowed data types
        if (in_array($data_type, $valid_data_types)) {
            $sql = "ALTER TABLE `$table_name` ADD `$column_name` $data_type";
            if ($conn->query($sql) === TRUE) {
                echo "Column '$column_name' added successfully to table '$table_name'.";
            } else {
                echo "Error adding column: " . $conn->error;
            }
        } else {
            echo "Invalid data type. Allowed types: " . implode(', ', $valid_data_types);
        }
    } else {
        echo "Please provide both column name and data type.";
    }
}

// Function to display table
function displayTable($conn, $tableName) {
    $sql = "SELECT * FROM `$tableName`";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div style='margin-bottom: 20px;'>";
        echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";
        
        // Bulk delete form start
        echo "<form method='POST' action=''>";
        echo "<input type='hidden' name='table_name' value='$tableName'>"; // Pass table name for deletion
        echo "<table border='1' cellpadding='10' cellspacing='0'>";
        
        // Table headers
        $fields = $result->fetch_fields();
        echo "<tr>";
        echo "<th>Select</th>"; // Add a checkbox column
        foreach ($fields as $field) {
            echo "<th>" . htmlspecialchars($field->name) . "</th>";
        }
        echo "</tr>";
        
        // Table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='delete_ids[]' value='" . $row['id'] . "'></td>";
            foreach ($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value ?? "NULL") . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        echo "<br><input type='submit' name='delete_selected' value='Delete Selected' onclick='return confirm(\"Are you sure?\");'>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "0 results found in $tableName table.";
    }

    // Form to add new column
    echo "
    <form method='POST' action='' style='margin-bottom: 20px;'>
        <input type='hidden' name='table_name' value='$tableName'>
        <h3>Add a New Column to Table: $tableName</h3>
        <label for='column_name'>Column Name: </label>
        <input type='text' name='column_name' required>
        <label for='data_type'>Data Type: </label>
        <select name='data_type' required>
            <option value='INT'>INT</option>
            <option value='VARCHAR(255)'>VARCHAR(255)</option>
            <option value='TEXT'>TEXT</option>
            <option value='DATE'>DATE</option>
            <option value='BOOLEAN'>BOOLEAN</option>
            <option value='FLOAT'>FLOAT</option>
        </select>
        <input type='submit' name='add_column' value='Add Column'>
    </form>";
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
}

h2 {
    color: #333;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

input[type="submit"], select {
    padding: 5px 10px;
    margin-top: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

form {
    margin-top: 20px;
}
</style>
