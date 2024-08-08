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

    public function displayOrders($owner_id, $limit, $offset) {
        $sql = "SELECT * FROM orders LIMIT ? OFFSET ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
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

// Set up pagination
$limit = 8; // Number of items per page
$total_orders = $order->getOrderCount(); // Get total number of orders
$total_pages = ceil($total_orders / $limit); // Calculate total number of pages

// Get the current page number from the URL parameter, default to 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = ($page > $total_pages) ? $total_pages : $page; // Ensure page is not out of bounds
$offset = ($page - 1) * $limit;

// Fetch paginated orders
$orders = $order->displayOrders($_SESSION['owner_id'], $limit, $offset);

// Get dashboard data
$confirmed_sales = $order->getConfirmedOrderSales();
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

                    <!-- Orders Table -->
                    <?php if (isset($error_message)) { echo '<div class="alert alert-danger">' . $error_message . '</div>'; } ?>

                    <div class="row">
                        <?php foreach ($orders as $row) {
                            $get_items = $oop->conn->query("SELECT m.*, o.quantity FROM order_items o INNER JOIN menu m ON o.menu_id = m.id WHERE order_id = '" . $row['id'] . "'");
                        ?>
                        <div class="col-md-3 mb-4 d-flex align-items-stretch">
                            <div class="card menu-card">
                                <div class="card-body" style="border: 4px solid #f6c23e;">
                                    <div class="float-end">
                                        <?php 
                                        if ($row['status'] == 1) {
                                            echo '<span class="badge bg-warning text-dark">Pending</span>';
                                        } else if ($row['status'] == 2) {
                                            echo '<span class="badge bg-primary">Confirmed</span>';
                                        } else if ($row['status'] == 3) {
                                            echo '<span class="badge bg-success">Finished</span>';
                                        }
                                        ?>
                                    </div>
                                    <p><?= date('Y-m-d', strtotime($row['created_at'])) ?></p>
                                    <p>Quantity: <?= $get_items->num_rows ?></p>
                                    <h5>Total: &#8369 <?= number_format($row['total_price'], 2) ?></h5>

                                    <!-- Status Update Form -->
                                    <form method="post" action="">
                                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                        <div class="form-group">
                                            <select name="status" class="form-select">
                                                <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>Pending</option>
                                                <option value="2" <?= $row['status'] == 2 ? 'selected' : '' ?>>Confirmed</option>
                                                <option value="3" <?= $row['status'] == 3 ? 'selected' : '' ?>>Finished</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="receipt-<?= $row['id'] ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Order Receipt</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Order Details -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <!-- Pagination Controls -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>© BIRMS 2024</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
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

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../js/datatables.min.js"></script>
</body>
</html>
