<?php 
require_once('inc/function.php');

header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; object-src 'none'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-ancestors 'none';");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer-when-downgrade');
header('Permissions-Policy: geolocation=(self), microphone=(), camera=()');
header('Cache-Control: no-store, no-cache, must-revalidate, proxy-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Expect-CT: max-age=86400, enforce, report-uri="https://example.com/report"');

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
