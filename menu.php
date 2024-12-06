<?php 
require './inc/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        // ADD TO CART
        // $user_id = 1;
        if (isset($_POST['add-cart'])) {
            $menu_id = $_POST['menu_id'];
            $quantity = $_POST['quantity'];
            $restobar = $_POST['restobar'];
           
            $check = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id' AND menu_id = '$menu_id'");
    
            if ($check->num_rows > 0) {
                $stmt = $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id = '$user_id' AND menu_id = '$menu_id'");
                ?>
                <script>
                   document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Cart item updated",
                            showConfirmButton: false,
                            timer: 1500
                    }).then(() => {
                        window.location.href = "menu.php?restobar=<?= $restobar ?>"
                    });
                   })
                </script>
                <?php 
            }else{
                $stmt = $conn->query("INSERT INTO cart SET menu_id = '$menu_id', user_id = '$user_id', quantity = '$quantity'");
                ?>
                <script>
                   document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Item added to cart",
                            showConfirmButton: false,
                            timer: 1500
                    }).then(() => {
                        window.location.href = "menu.php?restobar=<?= $restobar ?>"
                    });
                   })
                </script>
                <?php 
            }
        }
    
        // ADD ORDER
        if (isset($_POST['add-order'])) {
            $menu_id = $_POST['menu_id'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $total = (int)$price * (int)$quantity;
           
            $check = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' AND status = 1");
    
            if ($check->num_rows > 0) {
                ?>
                <script>
                   document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                            position: "center",
                            icon: "error",
                            title: "Please wait for the confirmation of your order",
                            showConfirmButton: false,
                            timer: 1500
                    }).then(() => {
                        window.location.href = "menu.php?restobar=<?= $restobar ?>"
                    });
                   })
                </script>
                <?php 
            }else{
               header("location: "); 
            }
        }
    }else{
        ?>
                 <script>
                    document.addEventListener('DOMContentLoaded', function(){
                     Swal.fire({
                             position: "center",
                             icon: "error",
                             title: "Please login first",
                             showConfirmButton: false,
                             timer: 1500
                     }).then(() => {
                         window.location.href = "login.php"
                     });
                    })
                 </script>
                <?php 
     }
    
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

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
     <link rel="shortcut icon" type="x-icon" href="./img/logo.jpg"
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> -->
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
            height: 300px;
        }
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .cover-text {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            text-align: center;
        }
        .restobar-image {
            height: 400px;
            width: 400px;
            object-fit: cover;
            border: 3px solid #f6c23e;
        }
        .container {
            max-width: 1200px;
            margin: auto;
        }
        .menu-card {
            border: 3px solid #f6c23e;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 300px; 
            height: 300px; 
            margin-right: 15px; 
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-img {
            width: 100%;
            height: 200px; 
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
            text-align: center;
            height: calc(100% - 200px); 
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }
        .menu-slider {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        .card-container {
            display: flex;
            transition: transform 0.5s ease;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            .col-md-3 {
                flex: 0 0 100%;
                max-width: 100%;
                display: flex;
                justify-content: center;
            }

            .menu-card {
                width: 90%;
                height: auto; 
            }
        }

        #prev-btn, #next-btn, #prev-btn-drinks, #next-btn-drinks {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 100;
        }

        #prev-btn {
            left: 10px;
        }

        #next-btn {
            right: 10px;
        }

        #prev-btn-drinks {
            left: 10px;
        }

        #next-btn-drinks {
            right: 10px;
        }
        .text-justify {
        text-align: justify;
        text-align-last: justify;
        margin: 5px;
    }

    .detail-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .detail-item {
        width: 100%;
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
    .modal-content {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid  #f6c23e;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-body {
            text-align: center;
            
        }

        #modal-img {
            max-height: 200px;
            object-fit: cover;
            margin-bottom: 1.5rem;
        }

        #modal-title {
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 500;
        }

        #modal-price {
            color: #6c757d;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        #modal-description {
            color: #343a40;
        }

        .modal-footer {
            border-top: none;
            justify-content: center;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-close {
            filter: invert(1);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
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
            <a class="nav-link position-relative" href="cart.php" >
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

    <div class="cover-container">
        <img src="img/bg2.jpg" alt="Cover Image" class="cover-image">
    </div>



    <div class="container-fluid mt-4">
        <div class="row align-items-center">
            <?php 
            $myrow = $oop->displaySelectedRestobar($_GET['restobar']);
            foreach($myrow as $row) {
            ?>
                <div class="col-md-6 text-center">
                    <img src="img/photos/<?=$row['resto_photo']?>" alt="Restobar Image" class="restobar-image img-fluid">
                </div>
                <div class="col-md-3 text-center">
    <h4 class="mt-3" style="font-size: 30px"> <?=$row['resto_name']?></h4>
    <div class="detail-container text-justify">
        <?php
        $myrowDetails = $oop->displayDetails($row['owner_id']);
        foreach($myrowDetails as $detailRow){
            ?><span class="detail-item"><?=$detailRow['details']?>.</span><br><?php
        }
        ?>
    </div>
</div>
            <?php
            }
            ?>
        </div>

        <h5 class="text-center mt-4" style="text-decoration: underline; font-size: 30px">OUR AVAILABLE <span style="color: red;">MENU</span></h5>
        
        <div class="buttons text-center mb-4">
            <a href="?restobar=<?=$_GET['restobar']?>&foods" class="btn btn-success mr-2">Foods</a>
            <a href="?restobar=<?=$_GET['restobar']?>&drinks" class="btn btn-warning">Drinks</a>
        </div>

        <?php 
            if (isset($_GET['message'])) {
                ?>
                <div class="alert alert-success">
                    <?= $_GET['message'] ?>
                </div>
                <?php 
            }
        ?>

        <!-- Foods Slider -->
        <div class="menu-slider" id="foods-slider">
        <button id="prev-btn" class="btn btn-outline-danger">
           <i class="fas fa-arrow-left">Previous</i>
        </button>
            <div class="card-container">
                <?php
                $type = isset($_GET['foods']) ? 'foods' : (isset($_GET['drinks']) ? 'drinks' : 'both');

                if ($type === 'foods' || $type === 'both') {
                    $myrowFoods = $oop->displayFoods($_GET['restobar']) ?: [];
                    $itemCount = 0;
                    foreach($myrowFoods as $row) {
                        if ($itemCount < 10) {
                ?>
                <div class="col-md-3 mb-4 d-flex align-items-stretch">
                    <div class="card menu-card" data-bs-toggle="modal" data-bs-target="#productModal"
                        data-img="img/product/<?=$row['product_photo']?>" 
                        data-price="<?=$row['price']?>" data-id="<?=$row['id']?>">
                        <img src="img/product/<?=$row['product_photo']?>" alt="Product" class="card-img">
                        <div class="card-body">
                            <h5 class="card-title"><?=$row['product_name']?></h5>
                            <p class="card-text">₱<?=$row['price']?></p>
                        </div>
                    </div>
                </div>
                <?php
                            $itemCount++;
                        }
                    }
                }
                ?>
            </div>
            <button id="next-btn" class="btn btn-outline-danger">
                 <i class="fas fa-arrow-right">Next</i>
            </button>
        </div>

        <!-- Drinks Slider -->
        <div class="menu-slider" id="drinks-slider">
        <button id="prev-btn-drinks" class="btn btn-outline-danger">
        <i class="fas fa-arrow-left">Previous</i>
        </button>
            <div class="card-container">
                <?php
                if ($type === 'drinks' || $type === 'both') {
                    $myrowDrinks = $oop->displayDrinks($_GET['restobar']) ?: [];
                    $itemCount = 0;
                    foreach($myrowDrinks as $row) {
                        if ($itemCount < 20) {
                ?>
                <div class="col-md-3 mb-4 d-flex align-items-stretch">
                    <div class="card menu-card" data-bs-toggle="modal" data-bs-target="#productModal"
                        data-img="img/product/<?=$row['product_photo']?>" 
                        data-price="<?=$row['price']?>" data-id="<?=$row['id']?>">
                        <img src="img/product/<?=$row['product_photo']?>" alt="Product" class="card-img">
                        <div class="card-body">
                            <h5 class="card-title"><?=$row['product_name']?></h5>
                            <p class="card-text">₱<?=$row['price']?></p>
                        </div>
                    </div>
                </div>
                <?php
                            $itemCount++;
                        }
                    }
                }
                ?>
            </div>
            <button id="next-btn-drinks" class="btn btn-outline-danger">
                 <i class="fas fa-arrow-right">Next</i>
            </button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modal-img" src="" alt="Product Image" class="img-fluid">
                    <h5 id="modal-title"></h5>
                    <p id="modal-price"></p>
                    <input type="hidden" id="modal-id" name="menu_id" />
                    <input type="hidden" id="modal-price-input" name="price" />
                    <input type="hidden" name="restobar" value="<?= $_GET['restobar'] ?>">

                    <div class="d-flex mx-auto my-3 justify-content-center align-items-center gap-2">
                        <span>Quantity</span>
                    <input type="number" name="quantity" class="form-control w-25" value="1" min="1">
                    </div>

                    <button type="submit" name="add-cart" class="btn btn-warning"> Add to Cart</button>
                    <!-- <button type="submit" name="add-order" class="btn btn-success">Order</butt> -->
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Footer -->
    <footer class="footer text-center mt-4 py-3 bg-dark text-white">
        <div class="container">
            <p class="m-0">Bantayan Island Restobar &copy; 2024</p>
        </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const prevBtnDrinks = document.getElementById('prev-btn-drinks');
        const nextBtnDrinks = document.getElementById('next-btn-drinks');
        const foodsSlider = document.getElementById('foods-slider');
        const drinksSlider = document.getElementById('drinks-slider');
        
        let foodsIndex = 0;
        let drinksIndex = 0;
        const slideWidth = document.querySelector('.menu-card').offsetWidth + 15;

        function moveSlider(slider, index) {
            slider.querySelector('.card-container').style.transform = `translateX(-${index * slideWidth}px)`;
        }

        prevBtn.addEventListener('click', function() {
            if (foodsIndex > 0) {
                foodsIndex--;
                moveSlider(foodsSlider, foodsIndex);
            }
        });

        nextBtn.addEventListener('click', function() {
            if ((foodsIndex + 1) * slideWidth < foodsSlider.querySelector('.card-container').scrollWidth) {
                foodsIndex++;
                moveSlider(foodsSlider, foodsIndex);
            }
        });

        prevBtnDrinks.addEventListener('click', function() {
            if (drinksIndex > 0) {
                drinksIndex--;
                moveSlider(drinksSlider, drinksIndex);
            }
        });

        nextBtnDrinks.addEventListener('click', function() {
            if ((drinksIndex + 1) * slideWidth < drinksSlider.querySelector('.card-container').scrollWidth) {
                drinksIndex++;
                moveSlider(drinksSlider, drinksIndex);
            }
        });

        // Modal functionality
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const modalImg = document.getElementById('modal-img');
        const modalTitle = document.getElementById('modal-title');
        const modalPrice = document.getElementById('modal-price');
        const modalId = document.getElementById('modal-id');
        const modalPriceInput = document.getElementById('modal-price-input');
        const modalDescription = document.getElementById('modal-description');

        document.querySelectorAll('.menu-card').forEach(card => {
            card.addEventListener('click', function() {
                const imgSrc = this.getAttribute('data-img');
                const price = this.getAttribute('data-price');
                const Id = this.getAttribute('data-id');
                const title = this.querySelector('.card-title').textContent;
                const description = this.querySelector('.card-text').textContent;

                modalImg.src = imgSrc;
                modalId.value = Id;
                modalTitle.textContent = title;
                modalPrice.textContent = '₱ '+ price;
                modalDescription.textContent = description;
                modalPriceInput.value = price;
                

                modal.show();
            });
        });
    });
</script>

</body>

</html>
