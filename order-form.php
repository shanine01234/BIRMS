<?php
require_once('inc/header.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['user_id'])) {

        if (isset($_POST['order'])) {
            $total = $_POST['total'];
            $validated = 1;

                for ($y=0; $y < count($_POST['total_restobar']) ; $y++) { 
                    $filename = date('mdGis') . '.png';
                    $tmpname = $_FILES['proof']['tmp_name'][$y];
                    $folder = "img/" . $filename;

                    $stmt = $conn->query("INSERT INTO orders SET user_id = '$user_id', total_price = '". $_POST['total_restobar'][$y] ."', proof = '$filename'");

                    $get_new = $conn->query("SELECT * FROM orders ORDER BY id DESC");
                    $new = $get_new->fetch_assoc();
                    $order_id = $new['id'];

                    $query = $conn->query("INSERT INTO order_items SET order_id = '$order_id', menu_id = '" . $_POST['menu_id'][$y] . "', quantity = '" . $_POST['quantity'][$y] . "', unit_price = '" . $_POST['price'][$y] . "'");

                   if ($stmt) {
                    move_uploaded_file($tmpname, $folder);
                   }else{
                    $validated = 0;
                   }
                }

                if ($validated) {
                    $delete = $conn->query("DELETE FROM cart WHERE user_id = '$user_id'");
                }

                
?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: "Order placed successfully",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "orders.php"
                        });
                    })
                </script>
<?php
            }
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
     <link rel="shortcut icon" type="x-icon" href="./img/logo.jpg"
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

        .nav-link,
        .nav-link i {
            color: black !important;
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
                        <a class="nav-link position-relative" href="orders.php">
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
                    } else {
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
        <img src="img/bg2.jpg" alt="Cover Image" class="cover-image">
        <div class="cover-text">
            <h1 style="color:white;"> Order</h1>
            <!-- <p>Please read the inputs below carefully.</p> -->
        </div>
    </div>

    <form method="post" class="container-fluid py-3" enctype="multipart/form-data">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="text-start my-3" style="font-size:30px">Order Form </h4>
        </div>

        <?php
        $total = [];
        if (isset($_SESSION['user_id'])) {
            if ($count_cart->num_rows > 0) {
                foreach ($count_cart as $row) {
                    $total[] = (float)$row['price'] * (float)$row['quantity'];
                    $price = (float)$row['price'] * (float)$row['quantity'];
        ?>
                    <!-- <input type="hidden" name="menu_id[]" value="<?= $row['menu_id'] ?>">
                    <input type="hidden" name="quantity[]" value="<?= $row['quantity'] ?>">
                    <input type="hidden" name="price[]" value="<?= $row['price'] ?>"> -->
            <?php
                }
            }

            ?>
        <?php
        } else {
        ?>
            <p class="my-3 text-center">Please login first</p>
        <?php
        }
        ?>

        <input type="hidden" name="total" value="<?= array_sum($total) ?>">

        <p>Please read the inputs below carefully.</p>
        <hr>

        <div class="row">
        
        <?php
        if (isset($_SESSION['user_id'])) {

            $query = $conn->query("SELECT * FROM cart c INNER JOIN menu m ON c.menu_id = m.id INNER JOIN owner w ON m.owner_id = w.owner_id INNER JOIN restobar r ON w.owner_id = r.owner_id WHERE c.user_id = $user_id");

            if ($query->num_rows > 0) {
                foreach ($query as $value) {
                    $product_by_owner = $conn->query("SELECT m.id, o.owner_id,m.price,c.quantity,o.gcash_qr FROM menu m INNER JOIN cart c ON m.id = c.menu_id INNER JOIN owner o ON m.owner_id = o.owner_id WHERE o.owner_id = '".$value['owner_id']."'");
                    $product_row = $product_by_owner->fetch_assoc();
        ?>
                    
                    <input type="hidden" name="menu_id[]" value="<?= $value['menu_id'] ?>">
                    <input type="hidden" name="quantity[]" value="<?= $value['quantity'] ?>">
                    <input type="hidden" name="price[]" value="<?= $value['price'] ?>">
                    <div class="col-lg-4">
                        <input type="hidden" name="total_restobar[]" value="<?= (int)$product_row['quantity'] * (int)$product_row['price'] ?>">
                        <input type="hidden" name="owner_id[]" value="<?= $value['owner_id'] ?>">
                    <div class="row">
                        <div class="col-12 mx-auto">
                        <h5 class="fw-bold">Restobar: <?= $value['resto_name'] ?></h5>
                            <p class="fw-bold">Scan QR Code to pay</p>
                            <img src="img/gcash_qr/<?= $product_row['gcash_qr'] ?>" alt="image" class="w-100 my-3">
                            <p>Pay using phone number: 09093568290</p>
                            <hr>
                        </div>
                        <div class="col-12">

                        </div>

                        <div class="col-12 mx-auto">
                            <label for="">Amount</label>
                            <input type="text" class="w-100 text-dark form-control my-2" name="none" disabled value="₱ <?= number_format((int)$product_row['quantity'] * (int)$product_row['price'], 2) ?>">
                            <label>Proof of Payment</label>
                            <input type="file" name="proof[]" class="form-control my-2" required>
                        </div>

                    </div>
                    </div>
        <?php
                }
            }
        }
        ?>

        <div class="col-12 text-center">
        <button type="submit" name="order" class="btn btn-primary mt-3 mx-auto w-25"> Submit</button>
        </div>
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
