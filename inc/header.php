<?php 
require_once('inc/function.php');


$connection = new Connection();
$conn = $connection->conn;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // $user_id = 1;
    
    $count_cart = $conn->query("SELECT c.*, m.product_name,m.product_type,m.price,m.product_photo,m.id AS menu_id FROM cart c INNER JOIN menu m ON c.menu_id = m.id WHERE c.user_id = '$user_id'");

    $orders = $conn->query("SELECT
    o.*, 
    i.quantity,
    m.product_name,
    m.product_type,
    m.price,
    m.product_photo,
    m.id AS menu_id 
    FROM orders o INNER JOIN order_items i ON i.order_id = o.id INNER JOIN menu m ON i.menu_id = m.id WHERE o.user_id = '$user_id' ");

    $order_count = $conn->query("SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");
}

if (isset($_GET['logout'])) {
    session_destroy();
    ?>
    <script>
       document.addEventListener('DOMContentLoaded', function(){
        Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Account logged out successfully",
                showConfirmButton: false,
                timer: 1500
        }).then(() => {
            window.location.href = "index.php"
        });
       })
    </script>
    <?php 
}