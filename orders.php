<?php
require_once('inc/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
}

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
            /* height: 300px;  */
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

        #prev-btn,
        #next-btn,
        #prev-btn-drinks,
        #next-btn-drinks {
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
            margin: 0 15px;
            /* Adjust the spacing here */

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
            background-color: black;
            /* Sets the background color of the toggler icon */
        }

        .modal-content {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #f6c23e;
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
</ul>            </div>

        </div>
    </nav>
    <!-- Cover Image with Text -->
    <div class="cover-container">
        <img src="img/bg2.jpg" alt="Cover Image" class="cover-image">
        <div class="cover-text">
            <h1 style="color:white;"> My Orders</h1>
            <p>Experience the best dining on the island</p>
        </div>
    </div>

    <form method="post" class="container-fluid">
    <div class="d-flex align-items-center justify-content-between">
        <h4 class="text-start my-3" style="font-size:30px">Orders (<?= $order_count->num_rows ?? 0 ?>)</h4>
    </div>

    <div class="row">
        <?php
        $total = [];
        if (isset($_SESSION['user_id'])) {
            if ($order_count->num_rows > 0) {
                foreach ($order_count as $row) {
                    $get_items = $conn->query("SELECT m.*, o.quantity FROM order_items o INNER JOIN menu m ON o.menu_id = m.id WHERE order_id = '" . $row['id'] . "' ");
        ?>
                    <div class="col-md-3 mb-4 d-flex align-items-stretch">
                        <div class="card menu-card" data-bs-toggle="modal" data-bs-target="#receipt-<?= $row['id'] ?>">
                            <div class="card-body">
                                <div class="float-end">
                                    <?php
                                    if ($row['status'] == 1) {
                                    ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php
                                    } else if ($row['status'] == 2) {
                                    ?>
                                        <span class="badge bg-primary">Confirmed</span>
                                    <?php
                                    } else if ($row['status'] == 3) {
                                    ?>
                                        <span class="badge bg-success">Finished</span>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p><?= date('Y-m-d', strtotime($row['created_at'])) ?></p>
                                <p>Quantity: <?= $get_items->num_rows ?></p>
                                <h5>Total: &#8369 <?= number_format($row['total_price'], 2) ?></h5>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="receipt-<?= $row['id'] ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productModalLabel">Order Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <p><?= date('Y-m-d', strtotime($row['created_at'])) ?></p>

                                    <img src="img/<?= $row['proof'] ?>" alt="proof-gcash" class="w-100 my-3">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($get_items as $menu) {
                                            ?>
                                                <tr>
                                                    <td><?= $menu['product_name'] ?></td>
                                                    <td><?= $menu['price'] ?></td>
                                                    <td><?= $menu['quantity'] ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                    <p>Quantity: <?= $get_items->num_rows ?></p>
                                    <h5>Total: <?= number_format($row['total_price'], 2) ?></h5>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                }
            }
        }
        ?>
    </div>
</form>


    <!-- Footer -->

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
                    <input type="hidden" id="modal-id" name="id" />
                    <input type="hidden" id="modal-price-input" name="price" />

                    <div class="d-flex mx-auto my-3 justify-content-center align-items-center gap-2">
                        <span>Quantity</span>
                        <input type="number" name="quantity" id="modal-quantity" class="form-control w-25" value="1" min="1">
                    </div>

                    <button type="submit" name="update-cart" class="btn btn-warning"> Update</button>
                    <button type="submit" name="remove-cart" class="btn btn-danger">Remove</butt>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            const modalImg = document.getElementById('modal-img');
            const modalTitle = document.getElementById('modal-title');
            const modalPrice = document.getElementById('modal-price');
            const modalId = document.getElementById('modal-id');
            const modalQuantity = document.getElementById('modal-quantity');
            const modalPriceInput = document.getElementById('modal-price-input');
            // const modalDescription = document.getElementById('modal-description');

            document.querySelectorAll('.menu-card').forEach(card => {
                card.addEventListener('click', function() {
                    const imgSrc = this.getAttribute('data-img');
                    const price = this.getAttribute('data-price');
                    const Id = this.getAttribute('data-id');
                    const quantity = this.getAttribute('data-quantity');
                    const title = this.querySelector('.card-title').textContent;
                    // const description = this.querySelector('.card-text').textContent;

                    modalImg.src = imgSrc;
                    modalId.value = Id;
                    modalTitle.textContent = title;
                    modalPrice.textContent = '₱ ' + price;
                    // modalDescription.textContent = description;
                    modalPriceInput.value = price;
                    modalQuantity.value = quantity;

                    modal.show();
                });
            });
        })
    </script>
</body>

</html>
