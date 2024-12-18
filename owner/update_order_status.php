<?php
require_once('../inc/function.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = intval($_POST['status']);

    if ($status < 1 || $status > 3) {
        die('Invalid status value.');
    }

    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $oop->conn->prepare($sql);
    $stmt->bind_param('ii', $status, $order_id);

    if ($stmt->execute()) {
        header('Location: ../orders.php'); // Redirect back to the orders page
        exit();
    } else {
        echo 'Error updating status.';
    }
}
?>
