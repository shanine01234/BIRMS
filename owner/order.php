<?php 
require_once('../inc/function.php');
include('process/restobar_proc.php');

if (!isset($_SESSION['owner_id'])) {
    header('location: login.php');
}

// Define the Order class
class Order {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function displayOrders($owner_id) {
        // Adjust the SQL query as needed
        $sql = "SELECT * FROM order_items i INNER JOIN orders o ON i.order_id = o.id INNER JOIN menu m ON i.menu_id = m.id  INNER JOIN owner w ON m.owner_id = w.owner_id WHERE w.owner_id = $owner_id GROUP BY o.id"; // Adjust this to reflect your actual filtering logic
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Create an instance of the Order class
$order = new Order($oop->conn); // Assuming $oop->conn is your database connection
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

    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/datatables.min.css" rel="stylesheet">
    <style>
        .button-row {
    display: flex;
    align-items: center; /* Align items vertically */
    justify-content: flex-start; /* Align items horizontally to the left */
}

.btn-custom-small {
    height: 30px; /* Adjust as needed */
    width: 100px; /* Adjust as needed */
    font-size: 12px; /* Optional: adjust font size */
    padding: 0; /* Remove default padding */
}
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">BIRMS Owner</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <li class="nav-item">
    <a class="nav-link" href="sales.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span> Sales Dashboard</span></a>
</li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Pages
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="branch.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Branches</span></a>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="menu.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Menus</span></a>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="resto_details.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Restobar Details</span></a>
            </li>

            <!-- Nav Item - Orders -->
            <li class="nav-item active">
                <a class="nav-link" href="orders.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>My Orders</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php 
                                $myrow = $oop->displayOwnerDets($_SESSION['owner_id']);
                                foreach($myrow as $row){
                                ?>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$row['firstname']." ".$row['lastname']?></span>
                                <img class="img-profile rounded-circle"
                                    src="../img/profiles/<?php if(empty($row['profile'])){ echo 'owner.png'; }else{ echo $row['profile'];}?>">
                                <?php 
                                }
                                ?>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">My Orders</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <!-- Orders Table -->
                            <div class="card shadow mb-4 p-4 w-100">
                            <div class="button-row">
                                   <button class="btn btn-primary btn-custom-small mb-3" onclick="printTable()">Print Orders</button>&nbsp;
                                   <button class="btn btn-primary btn-custom-small mb-3" onclick="window.location.href='orders.php'">Pending Menu</button>

                           </div>

                                <div class="data_table">
                                    <table id="ordersTable" class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User ID</th>
                                                <th>Total Price</th>
                                                <th>Created At</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $orders = $order->displayOrders($_SESSION['owner_id']);
                                            $i = 1;
                                            foreach($orders as $order){
                                                ?>
                                                <tr>
                                                    <td><?=$i++?></td>
                                                    <td><?=$order['user_id']?></td>
                                                    <td>₱<?=$order['total_price']?></td>
                                                    <td><?=$order['created_at']?></td>
                                                    <td>
                                                        <?php
                                                        switch($order['status']){
                                                            case 1: echo "Pending"; break;
                                                            case 2: echo "Confirmed"; break;
                                                            case 3: echo "Finished"; break;
                                                            default: echo "Unknown"; break;
                                                        }
                                                        ?>
                                                        
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Group &copy; BSIT</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                    <a class="btn btn-primary" href="process/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="../js/sb-admin-2.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>
    <script src="../js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable();
        });

        function printTable() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Orders</title>');
            printWindow.document.write('<link rel="stylesheet" href="../css/sb-admin-2.min.css" type="text/css">'); 
            printWindow.document.write('<link rel="stylesheet" href="../css/datatables.min.css" type="text/css">'); 
            printWindow.document.write('</head><body >');
            printWindow.document.write('<h1>My Orders</h1>');
            printWindow.document.write(document.querySelector('#ordersTable').outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</body>

</html>
