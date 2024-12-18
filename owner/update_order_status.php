<?php
require_once('../inc/function.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = intval($_POST['status']);

    // Validate status
    if ($status < 1 || $status > 3) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value.']);
        exit();
    }

    // Update the status in the database
    $sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $oop->conn->prepare($sql);
    $stmt->bind_param('ii', $status, $order_id);

    if ($stmt->execute()) {
        // Determine the new status text
        switch ($status) {
            case 1: $new_status_text = 'Pending'; break;
            case 2: $new_status_text = 'Confirmed'; break;
            case 3: $new_status_text = 'Finished'; break;
            default: $new_status_text = 'Unknown'; break;
        }

        // Return success response with updated status
        echo json_encode(['success' => true, 'new_status_text' => $new_status_text]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating status.']);
    }
}
?>
