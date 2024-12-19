// Handle display of the add column form
if (isset($_POST['add_column_form'])) {
    $tableName = $_POST['table_name'];
    displayAddColumnForm($tableName);
}

// Function to display the add column form
function displayAddColumnForm($tableName) {
    echo "<h3>Add New Column to " . strtoupper($tableName) . "</h3>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='table_name' value='" . $tableName . "'>";
    echo "<label>Column Name:</label>";
    echo "<input type='text' name='column_name' required><br>";
    echo "<label>Data Type (e.g., VARCHAR(255), INT, TEXT):</label>";
    echo "<input type='text' name='column_type' required><br>";
    echo "<button type='submit' name='add_column'>Add Column</button>";
    echo "</form>";
}

// Handle the addition of a column to a table
if (isset($_POST['add_column'])) {
    $tableName = $_POST['table_name'];
    $columnName = $_POST['column_name'];
    $columnType = $_POST['column_type'];

    $alterQuery = "ALTER TABLE `$tableName` ADD `$columnName` $columnType";
    if ($conn->query($alterQuery) === TRUE) {
        echo "<p>Column '$columnName' added successfully to table $tableName.</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Add column button in the displayTable function
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
