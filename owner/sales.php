<?php
require_once('../inc/function.php');
include('process/restobar_proc.php');

if (!isset($_SESSION['owner_id'])) {
    header('location: login.php');
    exit();
}

// Define the Order class
class Order {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function displayOrders($owner_id) {
        $sql = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderCount() {
        $sql = "SELECT COUNT(*) as total FROM orders";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    public function getConfirmedOrderSales() {
        $sql = "SELECT SUM(total_price) as total_sales FROM orders WHERE status = 2";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total_sales'];
    }

    public function getOrderStatusCounts() {
        $counts = [];
        $statuses = [1, 2, 3];
        foreach ($statuses as $status) {
            $sql = "SELECT COUNT(*) as count FROM orders WHERE status = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $counts[$status] = $result->fetch_assoc()['count'];
        }
        return $counts;
    }
}

// Create an instance of the Order class
$order = new Order($oop->conn); // Assuming $oop->conn is your database connection

// Handle the status update request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if (is_numeric($order_id) && in_array($status, [1, 2, 3])) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $oop->conn->prepare($sql);
        $stmt->bind_param("ii", $status, $order_id);

        if ($stmt->execute()) {
            header('Location: orders.php'); // Redirect back to orders page
            exit();
        } else {
            $error_message = "Error updating status: " . $stmt->error;
        }
    } else {
        $error_message = "Invalid data provided.";
    }
}

// Get dashboard data
$total_orders = $order->getOrderCount();
$confirmed_sales = $order->getConfirmedOrderSales();
$order_status_counts = $order->getOrderStatusCounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>BIRMS | Owner Orders</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">BIRMS Owner</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
    <a class="nav-link" href="sales.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span> Sales Dashboard</span></a>
</li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Pages</div>
            <li class="nav-item">
                <a class="nav-link" href="branch.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Branches</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="menu.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Menus</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="resto_details.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Restobar Details</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="order.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Orders</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php 
                                $myrow = $oop->displayOwnerDets($_SESSION['owner_id']);
                                foreach ($myrow as $row) {
                                ?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $row['firstname'] . " " . $row['lastname'] ?></span>
                                <img class="img-profile rounded-circle" src="../img/profiles/<?php if (empty($row['profile'])) { echo 'owner.png'; } else { echo $row['profile']; } ?>">
                                <?php } ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <!-- Page Heading -->
                <div class="container-fluid">
                    <h4 class="text-start my-3" style="font-size:30px">Orders</h4>

                    <!-- Dashboard -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Orders
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $total_orders ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Confirmed Orders Sales
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                &#8369 <?= number_format($confirmed_sales, 2) ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Chart -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Sales Overview</h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logout Modal -->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="../js/sb-admin-2.min.js"></script>
        <script src="../js/datatables.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'bar', // You can change this to 'line', 'pie', etc.
                data: {
                    labels: ['Pending', 'Confirmed', 'Finished'], // Labels for the chart
                    datasets: [{
                        label: 'Order Status Count',
                        data: [
                            <?php echo $order_status_counts[1]; ?>, 
                            <?php echo $order_status_counts[2]; ?>, 
                            <?php echo $order_status_counts[3]; ?>
                        ],
                        backgroundColor: ['rgba(255, 206, 86, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                        borderColor: ['rgba(255, 206, 86, 1)', 'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
        </script>
    </div>
</body>
</html>
