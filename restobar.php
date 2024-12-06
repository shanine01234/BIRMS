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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
          body {
            font-family: "Inconsolata", monospace;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings:
            "wdth" 100;
        }
        .cover-container {
            position: relative;
            width: 100%;
            height: 400px;
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
            width: 70%;
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
            font-size: 16px; 
            line-height: 1.5;
        }
     
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

<body style="background-color: hsl(240, 11%, 16%;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container-fluid" style="background-color: transparent;"> 
            <a class="navbar-brand" href="#">
           
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
            <a class="nav-link active" aria-current="page" href="#">
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
            <a class="nav-link" href="https://web.facebook.com/shanine.zaspa.9" target="_blank">
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
        <img src="img/bg2.jpg" alt="Cover Image" class="cover-image">
        <div class="cover-text">
            <h1 style="color:white;">Welcome to Bantayan Island Restobar</h1>
            <p>Experience the best dining on the island</p>
        </div>
    </div>

    <div class="container-fluid">
        <h4 class="text-center mt-3" style="font-size:30px" >OUR AVAILABLE RESTOBARS</h4>
        <div class="row p-2 justify-content-center">
            <?php
            $myrow = $oop->displayRestobar();
            foreach($myrow as $row){
                ?>
                <a href="menu.php?restobar=<?=$row['owner_id']?>" style="text-decoration: none; color: black; margin-right: 20px;">
                    <div class="card shadow-lg overflow-hidden mb-3">
                        <div class="image-container">
                            <img src="img/photos/<?=$row['resto_photo']?>" alt="RESTOBAR IMAGE">
                            <div class="overlay">
                                <div class="overlay-text">
                                    <?php
                                    $myrowDetails = $oop->displayDetails($row['owner_id']);
                                    foreach($myrowDetails as $detailRow){
                                        ?><span><?=$detailRow['details']?>.</span><br><?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <span>Restobar Name:</span>
                            <p class="text-muted"><?=$row['resto_name']?></p>
                            <hr>
                            <span>Owner:</span><br>
                            <span class="text-muted"><?=$row['firstname']?> <?=$row['lastname']?></span><br>
                            <hr>
                            <span>Branches:</span><br>
                            <?php
                            $myrowBranches = $oop->displayBranches($row['owner_id']);
                            foreach($myrowBranches as $branchRow){
                                ?><span class="text-muted"><?=$branchRow['branch']?> : <?=$branchRow['location']?>.</span> <br><?php
                            }
                            ?>
                            <hr>
                            <button href="menu.php?restobar=<?=$row['owner_id']?>" class="btn btn-outline-dark">Visit Now</button>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
