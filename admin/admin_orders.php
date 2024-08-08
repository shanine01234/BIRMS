<?php
require_once('../inc/function.php'); // Ensure this file contains your database connection and functions

// Fetch orders from the database
$orders = $oop->getAllOrders(); // Assuming getAllOrders() is a method in your functions file

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders - Bantayan Island Restobar</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">User Orders</h2>
        <table class="table table-bordered" id="ordersTable">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= $order['user_id'] ?></td>
                    <td><?= $order['item_name'] ?></td>
                    <td><?= $order['quantity'] ?></td>
                    <td>₱<?= $order['total_price'] ?></td>
                    <td><?= $order['order_date'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#ordersTable').DataTable();
        });
    </script>
</body>

</html>
