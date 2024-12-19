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

// Function to display all tables dynamically
function displayAllTables($conn) {
    // Get all table names
    $tablesQuery = "SHOW TABLES";
    $tablesResult = $conn->query($tablesQuery);

    if ($tablesResult->num_rows > 0) {
        while ($tableRow = $tablesResult->fetch_array()) {
            $tableName = $tableRow[0];
            displayTable($conn, $tableName);
        }
    } else {
        echo "No tables found in the database.";
    }
}

// Function to display a single table with delete and add column functionality
function displayTable($conn, $tableName) {
    $sql = "SELECT * FROM `$tableName`";
    $result = $conn->query($sql);

    echo "<div style='margin-bottom: 20px;'>";
    echo "<h2>" . strtoupper($tableName) . " TABLE</h2>";

    if ($result->num_rows > 0) {
        echo "<table>";
        // Display table headers
        $fields = $result->fetch_fields();
        echo "<tr>";
        foreach ($fields as $field) {
            echo "<th>" . $field->name . "</th>";
        }
        echo "<th>Action</th>";
        echo "</tr>";

        // Display table rows
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td>" . htmlspecialchars($value ?? "NULL") . "</td>";
            }

            // Delete button
            echo "<td>";
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";
            echo "<input type='hidden' name='id' value='" . $row[array_keys($row)[0]] . "'>";
            echo "<button type='submit' name='delete'>Delete</button>";
            echo "</form>";
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";

        // Add record button
        echo "<form method='post'>";
        echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";
        echo "<button type='submit' name='add_form'>Add Record</button>";
        echo "</form>";

        // Add column button
        echo "<form method='post'>";
        echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";
        echo "<button type='submit' name='add_column_form'>Add Column</button>";
        echo "</form>";

    } else {
        echo "0 results found in $tableName table";
    }
    echo "</div>";
}

// Handle deletion of a row
if (isset($_POST['delete'])) {
    $tableName = $_POST['table_name'];
    $id = $_POST['id'];

    $primaryKey = getPrimaryKey($conn, $tableName);
    if ($primaryKey) {
        $deleteQuery = "DELETE FROM `$tableName` WHERE `$primaryKey` = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        echo "<p>Row deleted successfully from $tableName.</p>";
    } else {
        echo "<p>Could not determine primary key for table $tableName.</p>";
    }
}

// Function to fetch the primary key of a table
function getPrimaryKey($conn, $tableName) {
    $primaryKeyQuery = "SHOW KEYS FROM `$tableName` WHERE Key_name = 'PRIMARY'";
    $result = $conn->query($primaryKeyQuery);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Column_name'];
    }
    return null;
}

// Handle display of add record form
if (isset($_POST['add_form'])) {
    $tableName = $_POST['table_name'];
    displayAddForm($conn, $tableName);
}

// Display form for adding a new record
function displayAddForm($conn, $tableName) {
    $columnsQuery = "SHOW COLUMNS FROM `$tableName`";
    $columnsResult = $conn->query($columnsQuery);

    echo "<h3>Add New Record to " . strtoupper($tableName) . "</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";

    while ($column = $columnsResult->fetch_assoc()) {
        $fieldName = $column['Field'];
        echo "<label>$fieldName:</label>";
        echo "<input type='text' name='fields[$fieldName]' required><br>";
    }
    echo "<button type='submit' name='add_record'>Add Record</button>";
    echo "</form>";
}

// Handle addition of a new record
if (isset($_POST['add_record'])) {
    $tableName = $_POST['table_name'];
    $fields = $_POST['fields'];

    $columns = implode(", ", array_keys($fields));
    $placeholders = implode(", ", array_fill(0, count($fields), '?'));
    $values = array_values($fields);

    $insertQuery = "INSERT INTO `$tableName` ($columns) VALUES ($placeholders)";
    $stmt = $conn->prepare($insertQuery);
    $types = str_repeat("s", count($values)); // Assuming all inputs are strings
    $stmt->bind_param($types, ...$values);

    if ($stmt->execute()) {
        echo "<p>New record added successfully to $tableName.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
}

// Handle display of add column form
if (isset($_POST['add_column_form'])) {
    $tableName = $_POST['table_name'];
    displayAddColumnForm($tableName);
}

// Display form for adding a new column
function displayAddColumnForm($tableName) {
    echo "<h3>Add New Column to " . strtoupper($tableName) . "</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";
    echo "<label>Column Name:</label>";
    echo "<input type='text' name='new_column_name' required><br>";
    echo "<label>Column Type:</label>";
    echo "<input type='text' name='new_column_type' placeholder='e.g., VARCHAR(255), INT' required><br>";
    echo "<button type='submit' name='add_column'>Add Column</button>";
    echo "</form>";
}

// Handle addition of a new column
if (isset($_POST['add_column'])) {
    $tableName = $_POST['table_name'];
    $newColumnName = $_POST['new_column_name'];
    $newColumnType = $_POST['new_column_type'];

    $alterQuery = "ALTER TABLE `$tableName` ADD `$newColumnName` $newColumnType";
    if ($conn->query($alterQuery)) {
        echo "<p>New column '$newColumnName' added successfully to table '$tableName'.</p>";
    } else {
        echo "<p>Error adding column: " . $conn->error . "</p>";
    }
}

// Display all tables
displayAllTables($conn);

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

button {
    padding: 5px 10px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    cursor: pointer;
    margin: 5px;
}

button:hover {
    background-color: #45a049;
}

form {
    display: inline-block;
}

h3 {
    margin-top: 20px;
}
</style>
