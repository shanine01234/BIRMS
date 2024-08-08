<?php 
require_once('inc/header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bantayan Island Restobar</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .cover-container {
            position: relative;
            width: 100%;
            height: 500px;
        }
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .cover-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            text-align: center;
        }
       
        .card {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 700px;
            margin: auto; 
            border: 2px solid black;
        }
        .card img {
            width: 50%;
            height: auto;
        }
        .card-body {
            width: 50%;
            padding: 10px;
        }

        .image-container {
            position: relative;
            overflow: hidden;
            width: 300px; 
            height: 400px; 
        }

        .image-container img {
            display: block;
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: opacity 0.3s ease;
        }

        .image-container:hover img {
            opacity: 0.3; 
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); 
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-align: center;
            padding: 10px;
        }

        .image-container:hover .overlay {
            opacity: 1;
        }

        .overlay-text {
            font-size: 16px; /* Adjust text size */
            line-height: 1.5;
        }

        /* Footer Styles */
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        footer .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 20px;
        }
        .navbar-nav {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .nav-item {
            text-align: center;
            color: black !important;
            margin: 0 15px; /* Adjust the spacing here */
          
        }
        .nav-link, .nav-link i {
            color: black !important;
        }
        .navbar-toggler-icon {
        background-color: black; /* Sets the background color of the toggler icon */
    }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container-fluid" style="background-color: transparent;"> 
            <a class="navbar-brand" href="#">
            <img src="./img/logo.jpg" alt="" width="30" height="24">
                <span style="color: black;   ">Bantayan Island Restobar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">
                <i class="fas fa-home"></i> Home
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="restobar.php">
                <i class="fas fa-utensils"></i> Restobar
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="about.php">
                <i class="fas fa-info-circle"></i> About
            </a>
        </li>
        <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Cart
                            <span class="badge bg-danger position-absolute top-0 end-0"><?= $count_cart->num_rows ?? 0 ?></span>
                        </a>
                    </li>

                    <li class="nav-item">
            <a class="nav-link position-relative" href="orders.php" >
                <i class="fas fa-file"></i> Orders
                <span class="badge bg-danger position-absolute top-0 end-0"><?= $order_count->num_rows ?? 0 ?></span>
            </a>
        </li>

        <?php 
            if (isset($user_id)) {
                ?>
                  <li class="nav-item dropdown">
            <a class="nav-link" href="#" data-bs-toggle="dropdown">
            <i class="fas fa-user"></i> 
            <span class="d-flex align-items-center gap-2"><?= $_SESSION['name'] ?>
                <i class="fa fa-caret-down"></i></span>
            </a>

            <ul class="dropdown-menu">
                <li class="dropdown-item">
                    <a href="?logout" class="text-dark text-decoration-none"><i class="fa fa-sign-out"></i> Logout</a>
                </li>
            </ul>
        </li>

                <?php 
            }else{
                ?>
                  <li class="nav-item">
            <a class="nav-link" href="login.php">
                <i class="fas fa-user"></i> Login
            </a>
        </li>

                <?php 
            }
        ?>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="https://facebook.com" target="_blank">
                <i class="fab fa-facebook"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="https://instagram.com" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="https://twitter.com" target="_blank">
                <i class="fab fa-twitter"></i>
            </a>
        </li>
    </ul>
</div>

        </div>
    </nav>

    <!-- Cover Image with Text -->
    <div class="cover-container">
        <img src="img/bantayan island.jpg" alt="Cover Image" class="cover-image">
        <div class="cover-text">
            <h1 style="color:#f6c23e;">ABOUT US</h1>
            <p>Welcome to Island Breeze Restobar, where island charm meets culinary delight! Nestled in the heart of Bantayan Island, our restobar offers an unparalleled dining experience with a fusion of local and international flavors. Whether you're looking to savor fresh seafood, enjoy a tropical cocktail, or simply unwind with live music, Island Breeze is your perfect destination.</p>
        </div>
    </div>

    <div class="container-fluid">
        <h4 class="text-center mt-3"></h4>
        
        </div>
    </div>

    <!-- Footer -->
    

    <!-- Bootstrap core JavaScript-->
    <!-- <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/datatables.min.js"></script>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
