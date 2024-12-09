<?php

require_once('inc/header.php');
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['reset-password'])) {
    $email = $conn->real_escape_string($_POST['email']); // Secure input handling

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50)); // Generate reset token

        // Save the reset token and timestamp in the database
        $stmt = $conn->prepare("UPDATE users SET token=?, reset_token_at=NOW() WHERE email=?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Configure PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug = 2; // Set to 2 for verbose output (useful for debugging)
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shaninezaspa179@gmail.com';
            $mail->Password = 'hglesxkasgmryjxq'; // Ensure this is correct and secure
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('shaninezaspa179@gmail.com', 'Bantayan Island Restobar');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';

            // Set up the reset link
            $resetLink = "https://bantayanrestobars.com/new_password?token=" . $token;

            $mail->Body = "<p>Click the link below to reset your password:</p>
                           <p><a href='$resetLink'>$resetLink</a></p>
                           <p>This link will expire in 1 hour.</p>";

            $mail->send();

            // After successful email send, display the success message and redirect to login
            echo "<script>
                    Swal.fire('Success', 'Password reset link sent. Please check your email.', 'success').then(() => {
                        window.location.href = 'login.php'; // Redirect to login page
                    });
                  </script>";

        } catch (Exception $e) {
            // Catch PHPMailer's error
            echo "<script>
                    Swal.fire('Error', 'There was an error sending the reset email: {$e->getMessage()}', 'error');
                  </script>";
        }

    } else {
        // Email not found
        echo "<script>
                Swal.fire('Error', 'Email not found in our records.', 'error');
              </script>";
    }
}

// Redirect if URL ends with .php
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

    <title>Bantayan Island Restobar - Reset Password</title>
    <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Include SweetAlert2 library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: "Roboto", sans-serif;
            background-color: #f8f9fa;
            color: #495057;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .login-container h4 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        }

        .btn-warning {
            background-color: #f0ad4e;
            border-color: #f0ad4e;
            color: #ffffff;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-warning:hover {
            background-color: #ec971f;
            border-color: #d58512;
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>

    <!-- Login Form -->
    <div class="login-container">
        <h4>Reset Password</h4>
        <form method="post">
            <div class="form-group">
                <label for="email">Enter your registered email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" name="reset-password" class="btn btn-warning btn-block">Send Reset Link</button><br>
            <a href="index.php" class="btn btn-warning btn-back">
                <i class="fas fa-arrow-left"></i>
            </a>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
