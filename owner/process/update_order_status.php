<?php 
require_once(__DIR__ . '/../inc/function.php');

// Check if the user is logged in
if (!isset($_SESSION['owner_id'])) {
    header('location: login.php');
    exit();
}

// Ensure that the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Validate inputs
    if (!is_numeric($order_id) || !in_array($status, [1, 2, 3])) {
        echo "Invalid input.";
        exit();
    }

    // Update the order status in the database
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $oop->conn->prepare($sql);
    $stmt->bind_param("ii", $status, $order_id);

    if ($stmt->execute()) {
        header('Location: ../orders.php'); // Redirect back to orders page
        exit(); // Ensure no further code is executed
    } else {
        echo "Failed to update order status.";
    }
}
?>
