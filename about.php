<?php 
require_once('inc/header.php');

 $request = $_SERVER['REQUEST_URI'];
if (substr($request, -4) == '.php') {
    $new_url = substr($request, 0, -4);
    header("Location: $new_url", true, 301);
    exit();
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

    <title>Bantayan Island Restobar</title>
 <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Custom fonts for this template-->
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
        .nav-link {
            display: flex;
            align-items: center; /* Center the icon and text vertically */
            gap: 5px; /* Add spacing between the icon and text */
            color: black; /* Ensure consistent text color */
        }
             .nav-link i {
            color: blue !important;
            transition: color 0.3s ease;
        }
                .nav-link.position-relative {
            display: inline-flex; /* Inline-flex for relative positioning */
            align-items: center; /* Align content vertically */
        }
            .nav-link:hover i {
            color: #01070d;
        }
        .nav-link .badge {
            position: absolute; /* Position the badge correctly */
            top: 0; /* Align badge to the top */
            right: 0; /* Align badge to the right */
            transform: translate(30%, -50%); /* Fine-tune badge positioning */
            font-size: 0.7rem; /* Smaller font size for the badge */
            padding: 3px 6px; /* Add some padding for better visibility */
            border-radius: 50%; /* Make the badge circular */
        }
        .social-links .nav-item {
            display: inline-block;
        }
        
        .social-links .nav-link {
            text-decoration: none;
            font-size: 1.5rem; /* Adjust icon size */
            color: #000; /* Default icon color */
            transition: color 0.3s ease; /* Smooth hover transition */
        }
        
        .social-links .nav-link:hover {
            color: #007bff; /* Change color on hover */
        }

        .social-links {
            display: flex;
            flex-direction: row; /* Arrange items horizontally */
            gap: 10px; /* Space between items */
            list-style: none; /* Remove bullets */
            padding: 0;
            margin: 0;
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
            <img src="./img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="25" height="25" style="border-radius: 30px">
                <span style="color: black; font-size: 15px">Bantayan Island Restobar</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
        <li class="nav-item">
    <a class="nav-link" href="index.php" style="margin-left: 100px;">
        <i class="fas fa-home"></i> <span style="color: black">Home</span>
    </a>
</li>


        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="restobar.php" style="margin-left: 100px;">
                <i class="fas fa-utensils"></i> <span style="color: black">Restobar</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="about.php" style="margin-left: 100px;">
                <i class="fas fa-info-circle"></i><span style="color: black"> About</span>
            </a>
        </li>
        <li class="nav-item">
                        <a class="nav-link position-relative" href="cart.php" style="margin-left: -23px;">
                            <i class="fas fa-shopping-cart"></i> <span style="color: black">Cart</span>
                            <span class="badge bg-danger position-absolute top-0 end-0" style="transform: translate(25px, -5px);"><?= $count_cart->num_rows ?? 0 ?></span
                        </a>
                    </li>

                    <li class="nav-item">
            <a class="nav-link position-relative" href="orders.php"style="margin-top: -10px;margin-left: -6px;"">
                <i class="fas fa-file"></i> <span style="color: black">Orders</span>
                <span class="badge bg-danger position-absolute top-0 end-0"  style="transform: translate(22px, -4px);"><?= $order_count->num_rows ?? 0 ?></span>
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
                    <a href="?logout" class="text-dark text-decoration-none"><i class="fa fa-sign-out"></i> <span style="color: black">Logout</span></a>
                </li>
            </ul>
        </li>

                <?php 
            }else{
                ?>
                  <li class="nav-item">
            <a class="nav-link" href="login.php" style="margin-left: 100px;">
                <i class="fas fa-user"></i><span style="color: black">Login</span>
            </a>
        </li>

                <?php 
            }
        ?>
    </ul>
    <ul class="navbar-nav social-links">
    <li class="nav-item">
        <a class="nav-link" href="https://www.facebook.com/shanine.zaspa.9" target="_blank">
            <i class="fab fa-facebook"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="https://www.instagram.com/shanine_zaspa/?utm_source=ig_web_button_share_sheet" target="_blank">
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
