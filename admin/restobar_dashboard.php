<?php
require_once('../inc/function.php');
session_start();

// Check if user is logged in and is an owner
if (!isset($_SESSION['user_id']) || !isset($_GET['restobar'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}

$owner_id = $_SESSION['user_id'];
$restobar_id = $_GET['restobar'];

// Function to get orders
function getOrders($restobar_id) {
    global $conn;
    $query = "SELECT o.*, m.product_name, m.price, u.username
              FROM orders o
              JOIN menu m ON o.menu_id = m.id
              JOIN users u ON o.user_id = u.id
              WHERE m.owner_id = ? AND m.resto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $owner_id, $restobar_id);
    $stmt->execute();
    return $stmt->get_result();
}

$orders = getOrders($restobar_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Restobar Orders Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/datatables.min.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .table thead th {
            background-color: #f6c23e;
            color: black;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container-fluid" style="background-color: transparent;"> 
            <a class="navbar-brand" href="#">
                <img src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24">
                <span style="color: black;">Restobar Dashboard</span>
            </a>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <h3 class="text-center mb-4">Order Records</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Username</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $orders->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['id']}</td>";
                    echo "<td>{$row['product_name']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td>₱{$row['total_price']}</td>";
                    echo "<td>{$row['created_at']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer class="footer text-center mt-4 py-3 bg-dark text-white">
        <div class="container">
            <p class="m-0">Restobar Dashboard &copy; 2024</p>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>
