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
if (isset($_POST['delete']) && isset($_POST['table']) && isset($_POST['id'])) {
    $table = $_POST['table'];
    $id = intval($_POST['id']);
    $deleteSql = "DELETE FROM $table WHERE id = $id"; // Assumes the primary key column is `id`
    $conn->query($deleteSql);
    echo "<p style='color: red;'>Record deleted successfully from $table.</p>";
}

// Handle add request
if (isset($_POST['add']) && isset($_POST['table'])) {
    $table = $_POST['table'];
    $columns = [];
    $values = [];
    
    foreach ($_POST as $key => $value) {
        if ($key != 'add' && $key != 'table') {
            $columns[] = $key;
            $values[] = "'" . $conn->real_escape_string($value) . "'";
        }
    }
    
    $columnsList = implode(", ", $columns);
    $valuesList = implode(", ", $values);
    $addSql = "INSERT INTO $table ($columnsList) VALUES ($valuesList)";
    $conn->query($addSql);
    echo "<p style='color: green;'>Record added successfully to $table.</p>";
}

// Function to display all tables
function displayTables($conn) {
    $tables = $conn->query("SHOW TABLES");
    
    while ($tableRow = $tables->fetch_array()) {
        $tableName = $tableRow[0];
        echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";
        $result = $conn->query("SELECT * FROM $tableName");
        
        if ($result->num_rows > 0) {
            echo "<table border='1' cellpadding='10' cellspacing='0'>";
            
            // Get headers
            $fields = $result->fetch_fields();
            echo "<tr>";
            foreach ($fields as $field) {
                echo "<th>" . $field->name . "</th>";
            }
            echo "<th>Actions</th>"; // Additional column for actions
            echo "</tr>";
            
            // Display rows with delete button
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>" . ($value ?? "NULL") . "</td>";
                }
                // Delete button form
                echo "<td>
                    <form method='POST' style='display: inline;'>
                        <input type='hidden' name='table' value='$tableName'>
                        <input type='hidden' name='id' value='" . $row['order_item_id'] . "'>
                        <button type='submit' name='delete'>Delete</button>
                    </form>
                </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No records found in $tableName table.<br>";
        }

        // Display add form
        echo "<h3>Add Record to " . strtoupper($tableName) . "</h3>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='table' value='$tableName'>";
        foreach ($fields as $field) {
            if ($field->name !== 'id') { // Skip primary key
                echo "<label>" . ucfirst($field->name) . ":</label> 
                      <input type='text' name='" . $field->name . "' required><br>";
            }
        }
        echo "<button type='submit' name='add'>Add</button>";
        echo "</form>";
    }
}

// Display all tables
displayTables($conn);

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
    h3 {
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
    form {
        margin: 10px 0;
    }
    button {
        cursor: pointer;
        padding: 5px 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 3px;
    }
    button:hover {
        background-color: #45a049;
    }
</style>
