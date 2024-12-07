<?php
require_once('inc/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {
        // update
        if (isset($_POST['update-cart'])) {
            $id = $_POST['id'];
            $quantity = $_POST['quantity'];

            // $check = $conn->query("SELECT * FROM cart WHERE user_id = '$user_id' AND id = '$id'");

            $stmt = $conn->query("UPDATE cart SET quantity = $quantity WHERE id = '$id'");
?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Cart item updated",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "cart.php"
                    });
                })
            </script>
        <?php
        }

        // remove
        if (isset($_POST['remove-cart'])) {
            $id = $_POST['id'];
            $stmt = $conn->query("DELETE FROM cart WHERE id = '$id'");
        ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Cart item removed successfully",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "cart.php"
                    });
                })
            </script>
<?php
        }

        if (isset($_POST['order'])) {
            $total = $_POST['total'];

            $stmt = $conn->query("INSERT INTO orders SET user_id = '$user_id', total_price = $total");

            if ($stmt) {
                $get_new = $conn->query("SELECT * FROM orders ORDER BY id DESC");
                $new = $get_new->fetch_assoc();
                $order_id = $new['id'];

                for ($i=0; $i < count($_POST['menu_id']); $i++) { 
                    $query = $conn->query("INSERT INTO order_items SET order_id = '$order_id', menu_id = '". $_POST['menu_id'][$i] ."', quantity = '". $_POST['quantity'][$i] ."', unit_price = '". $_POST['price'][$i] ."'");
                }

                $delete = $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");

                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Order placed successfully",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "cart.php"
                        });
                    })
                </script>
    <?php
            }
        }
    }
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

            .nav-link, .nav-link i {
            color: blue !important;
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
    <a class="nav-link" href="index.php">
        <img src="https://img.icons8.com/?size=100&id=72&format=png&color=000000" alt="Home" width="20" height="20">
        <span style="color: black">Home</span>
    </a>
</li>


        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="restobar.php">
                <i class="fas fa-utensils"></i> <span style="color: black">Restobar</span>
            </a>
        </li>
        <li class="nav-item">
    <a class="nav-link" href="about.php">
        <img src="https://img.icons8.com/?size=100&id=11808&format=png&color=000000" alt="About" width="20" height="20">
        <span style="color: black">About</span>
    </a>
</li>
        <li class="nav-item">
    <a class="nav-link position-relative" href="cart.php">
        <img src="https://img.icons8.com/?size=100&id=8chNl15hy6jY&format=png&color=000000" alt="Cart" width="20" height="20">
        <span style="color: black">Cart</span>
        <span class="badge bg-danger position-absolute top-0 end-0"><?= $count_cart->num_rows ?? 0 ?></span>
    </a>
</li>

                    <li class="nav-item">
            <a class="nav-link position-relative" href="orders.php">
                <i class="fas fa-file"></i> <span style="color: black">Orders</span>
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
                    <a href="?logout" class="text-dark text-decoration-none"><i class="fa fa-sign-out"></i> <span style="color: black">Logout</span></a>
                </li>
            </ul>
        </li>

                <?php 
            }else{
                ?>
                 <li class="nav-item">
    <a class="nav-link" href="login.php">
        <img src="https://img.icons8.com/?size=100&id=6850&format=png&color=000000" alt="Login" width="20" height="20">
        <span style="color: black">Login</span>
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
            <img src="https://img.icons8.com/?size=100&id=BrU2BBoRXiWq&format=png&color=000000" alt="Instagram" width="30" height="30">
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="https://twitter.com" target="_blank">
            <img src="https://img.icons8.com/?size=100&id=phOKFKYpe00C&format=png&color=000000" alt="Twitter" width="30" height="30">
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
            <h1 style="color:white;"> My Cart</h1>
            <p>Experience the best dining on the island</p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="text-start my-3" style="font-size:30px">Cart Items (<?= $count_cart->num_rows ?? 0 ?>)</h4>
            
           <?php 
           if (isset($_SESSION['user_id'])) {
            if ($count_cart->num_rows > 0) {
                ?>
                 <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#order-modal">Order Now</button>
                <?php
            }
           }
           ?>

        </div>

        <div class="row">
            <?php
            $total = [];
            if (isset($_SESSION['user_id'])) {
                if ($count_cart->num_rows > 0) {
                    foreach ($count_cart as $row) {
                        $total[] = (float)$row['price'] * (float)$row['quantity'];
                        $price = (float)$row['price'] * (float)$row['quantity'];
            ?>
                        <div class="col-md-3 mb-4 d-flex align-items-stretch">
                            <div class="card menu-card" data-bs-toggle="modal" data-bs-target="#productModal" data-img="img/product/<?= $row['product_photo'] ?>" data-price="<?= $row['price'] ?>" data-quantity="<?= $row['quantity'] ?>" data-id="<?= $row['id'] ?>">
                                <img src="img/product/<?= $row['product_photo'] ?>" alt="Product" class="card-img">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $row['product_name'] ?></h5>
                                    <p class="card-text">Quantity: <?= $row['quantity'] ?></p>
                                    <p class="card-text">₱ <?= number_format((float)$row['price'], 2) ?></p>
                                    <p class="card-text">Total: ₱ <?= number_format($price, 2) ?></p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="menu_id[]" value="<?= $row['menu_id'] ?>">
                        <input type="hidden" name="quantity[]" value="<?= $row['quantity'] ?>">
                        <input type="hidden" name="price[]" value="<?= $row['price'] ?>">
                <?php
                    }
                }

                ?>
                <div class="col-12 text-end my-3">

                    <h3>Total: ₱ <?= number_format(array_sum($total), 2) ?></h3>
                    <input type="hidden" name="total" value="<?= array_sum($total) ?>">

                </div>
            <?php
            }else{
                ?>
                <p class="my-3 text-center">Please login first</p>
                <?php 
            }
            ?>


        </div>

        <div class="modal fade" id="order-modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalLabel">Order Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>Are you sure you want to order this items?</h5>
                    </div>
                    <div class="modal-footer">
                        <a href="./order-form.php" name="order" class="btn btn-warning" >Confirm</a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

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
