<?php
session_start();

// Check if the 'admin_logged_in' cookie exists
if (!isset($_COOKIE['log_in'])) {
    // Destroy the admin session and log out
    session_unset();
    session_destroy();
    header('Location: ../login.php'); // Redirect to admin login
    exit();
}

// If the cookie exists, continue normal operation
echo "Admin is still logged in.";
?>
