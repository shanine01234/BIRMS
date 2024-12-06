<?php
// Include necessary files and database connection
require_once('../inc/function.php');
require_once('../config/db.php'); // Adjust this path as needed

if (isset($_POST['registerOwner'])) {
    // Sanitize and validate user input
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $middlename = htmlspecialchars(trim($_POST['middlename']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $restobar = htmlspecialchars(trim($_POST['restobar']));
    $contact_num = htmlspecialchars(trim($_POST['contact_num']));
    $address = htmlspecialchars(trim($_POST['address']));
    $gcash_num = htmlspecialchars(trim($_POST['gcash_num']));
    $random_id = uniqid(); // Generate a unique ID for the owner

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msgAlert = $oop->alert('Invalid email address', 'danger', 'x-circle');
    }

    // Validate passwords
    if ($password !== $cpassword) {
        $msgAlert = $oop->alert('Passwords do not match', 'danger', 'x-circle');
    }

    // Validate contact number
    if (!preg_match('/^\d{11}$/', $contact_num)) {
        $msgAlert = $oop->alert('Invalid contact number', 'danger', 'x-circle');
    }

    // Validate GCash number
    if (!preg_match('/^\d{11}$/', $gcash_num)) {
        $msgAlert = $oop->alert('Invalid GCash number', 'danger', 'x-circle');
    }

    // Validate and upload files securely
    $allowedFileTypes = ['image/jpeg', 'image/png'];
    $restoPhoto = $_FILES['restoPhoto'];
    $gcashQr = $_FILES['gcash_qr'];

    if (!in_array($restoPhoto['type'], $allowedFileTypes) || !in_array($gcashQr['type'], $allowedFileTypes)) {
        $msgAlert = $oop->alert('Invalid file type for photos', 'danger', 'x-circle');
    } else {
        $restoPhotoPath = "../img/resto/" . $random_id . "_resto." . pathinfo($restoPhoto['name'], PATHINFO_EXTENSION);
        $gcashQrPath = "../img/gcash_qr/" . $random_id . "_gcash." . pathinfo($gcashQr['name'], PATHINFO_EXTENSION);

        move_uploaded_file($restoPhoto['tmp_name'], $restoPhotoPath);
        move_uploaded_file($gcashQr['tmp_name'], $gcashQrPath);
    }

    if (empty($msgAlert)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Call the OOP function to register owner
        $result = $oop->registerOwner(
            $firstname,
            $middlename,
            $lastname,
            $email,
            $hashedPassword,
            $hashedPassword, // Using the same hashed password as verification is done before this step
            $restobar,
            $contact_num,
            $address,
            $restoPhotoPath,
            $random_id,
            $gcash_num,
            date('Y-m-d H:i:s')
        );

        if ($result == 1) {
            $msgAlert = $oop->alert('Registered successfully', 'warning', 'check-circle'); ?>
            <script>
                function redirect() {
                    window.location = "login.php";
                }
                setTimeout(redirect, 2000);
            </script>
        <?php
        } elseif ($result == 10) {
            $msgAlert = $oop->alert('Email is already used', 'danger', 'x-circle');
        } elseif ($result == 20) {
            $msgAlert = $oop->alert('Passwords do not match', 'danger', 'x-circle');
        } elseif ($result == 30) {
            $msgAlert = $oop->alert('Invalid contact number', 'danger', 'x-circle');
        }
    }
}
?>
