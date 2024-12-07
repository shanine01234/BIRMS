<?php
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

// Get all tables in the database
$query = "SHOW TABLES";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $table = $row["Tables_in_$db_name"];

        // Display table name
        echo "<h2>" . htmlspecialchars($table, ENT_QUOTES, 'UTF-8') . "</h2>";

        // Get all data from the table
        $data_query = "SELECT * FROM $table";
        $data_result = $conn->query($data_query);

        if ($data_result->num_rows > 0) {
            // Display table data
            echo "<table border='1'><tr>";

            // Display column names
            $fields = $data_result->fetch_fields();
            foreach ($fields as $field) {
                echo "<th>" . htmlspecialchars($field->name, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "<th>Action</th></tr>";

            // Display rows
            while ($row = $data_result->fetch_assoc()) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<td>" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</td>";
                }

                // Add a delete button for each row
                echo "<td><a href='?delete=" . urlencode($table) . "&id=" . urlencode($row['id']) . "'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No data found in the table.";
        }
    }
} else {
    echo "No tables found in the database.";
}

// Delete row functionality
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $table = htmlspecialchars($_GET['delete'], ENT_QUOTES, 'UTF-8');
    $id = intval($_GET['id']); // Ensure that the ID is an integer to prevent SQL injection

    // List of allowed tables to delete from (optional, you can customize this)
    $allowed_tables = ['table1', 'table2', 'table3']; // Add your table names here
    if (!in_array($table, $allowed_tables)) {
        die("Error: Invalid table for deletion.");
    }

    // Ensure that id exists in the table
    $check_id_query = "SELECT COUNT(*) FROM $table WHERE id = ?";
    $stmt = $conn->prepare($check_id_query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        die("Error: ID not found in the table.");
    }

    // Delete row by id (SQL Injection safe with prepared statements)
    $delete_query = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param('i', $id); // 'i' means integer
    if ($stmt->execute()) {
        echo "Record deleted successfully.";
        // Redirect to avoid resubmitting the form
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>
