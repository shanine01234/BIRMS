<?php 
require_once('../inc/function.php');
include('process/restobar_proc.php');

if (!isset($_SESSION['owner_id'])) {
    header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BIRMS | Owner Branches</title>
<link rel="icon" type="image/png" href="../img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Custom fonts for this template-->
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../css/datatables.min.css" rel="stylesheet">

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
          <li class="nav-item ">
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
            <li class="nav-item active">
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
            <li class="nav-item active">
                  <a class="nav-link" href="order.php">
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
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$row['firstname']." ".$row['lastname']?>r</span>
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
                <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">My Branches</h1>
                     
                    </div>

                    <!-- Content Row -->

                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addBranchModal">
    Add Branch
</button>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" role="dialog" aria-labelledby="addBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBranchModalLabel">Add Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST">
                <div class="modal-body">
                    <label>Branch</label>
                    <input type="text" name="branch" class="form-control mb-2" required>
                    <label>Location</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="addBranch">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
                        <div class="col">
                        <!-- Area Chart -->
                        <div class="card shadow mb-4 p-4 w-100">
                            <?=$msgAlert?>
                            <div class="data_table">
                            <table id="dashprint" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Branch</th>
            <th>Location</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $myrow = $oop->displayBranches($_SESSION['owner_id']);
        $l = 1;
        foreach($myrow as $row) {
        ?>
        <tr>
            <td><?=$l++?></td>
            <td><?=$row['branch']?></td>
            <td><?=$row['location']?></td>
            <td>
                <!-- Edit Button -->
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editBranchModal<?=$row['id']?>">
                    <i class="fas fa-edit fa-sm fa-fw"></i>
                </button>
                
                <!-- Delete Button -->
                <a href="#" data-toggle="modal" data-target="#deleteModal<?=$row['id']?>" class="btn btn-danger">
                    <i class="fas fa-trash fa-sm fa-fw"></i>
                </a>
            </td>
        </tr>

        <!-- Edit Branch Modal -->
        <div class="modal fade" id="editBranchModal<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="editBranchModalLabel<?=$row['id']?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBranchModalLabel<?=$row['id']?>">Edit Branch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?=$row['id']?>">
                            <label>Branch</label>
                            <input type="text" name="branch" class="form-control mb-2" value="<?=$row['branch']?>" required>
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" value="<?=$row['location']?>" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="updateBranch">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?=$row['id']?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel<?=$row['id']?>">Delete Branch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this branch?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <form action="" method="POST">
                            <input type="hidden" name="id" value="<?=$row['id']?>">
                            <button type="submit" name="deleteBranch" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                        <span>Shanine Zaspa &copy; 2024</span>
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
    <script src="../js/custom.js"></script>
    <script src="../js/datatables.min.js"></script>

    <!-- Page level plugins -->
    <script src="../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../js/demo/chart-area-demo.js"></script>
    <script src="../js/demo/chart-pie-demo.js"></script>

</body>

</html>
