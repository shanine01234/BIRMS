<?php 
require_once('inc/header.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize user input to avoid XSS
    $verification_code = htmlspecialchars(trim($_POST['verification_code']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    // Ensure that no fields are empty
    if (empty($verification_code) || empty($email) || empty($password)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if the entered password matches the stored hash
            if (password_verify($password, $row['password'])) {

                // Verify the code
                if ($row['verification_code'] === $verification_code) {
                    // Update the user's status to 'verified'
                    $update_stmt = $conn->prepare("UPDATE users SET status = 2 WHERE verification_code = ? AND email = ?");
                    $update_stmt->bind_param("ss", $verification_code, $email);
                    $update_stmt->execute();

                    // Show success message
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function(){
                            Swal.fire({
                                position: 'middle',
                                icon: 'success',
                                title: 'Account verified successfully',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'login.php';
                            });
                        });
                    </script>";

                } else {
                    // Incorrect verification code
                    echo "<script>
                        document.addEventListener('DOMContentLoaded', function(){
                            Swal.fire({
                                position: 'middle',
                                icon: 'error',
                                title: 'Incorrect verification code',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = 'account-verification.php';
                            });
                        });
                    </script>";
                }

            } else {
                // Incorrect password
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function(){
                        Swal.fire({
                            position: 'middle',
                            icon: 'error',
                            title: 'Incorrect email or password',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'account-verification.php';
                        });
                    });
                </script>";
            }

        } else {
            // User does not exist
            echo "<script>
                document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                        position: 'middle',
                        icon: 'error',
                        title: 'Incorrect email or password',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'account-verification.php';
                    });
                });
            </script>";
        }
    }
}

// Redirect to remove .php from the URL if present
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/datatables.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: "Inconsolata", monospace;
            font-weight: normal;
        }
        .signup-container {
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            max-width: 400px;
            margin: 0 auto;
            background-color: white;
            margin-top: 100px;
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

<body style="background-color: #fff;">
    <!-- Signup Form -->
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 30px;">Account Verification</h4>
        <p>Please check your email account. We sent a code to your email account.</p>
        <form method="post">
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" class="form-control my-2" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control my-2" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control my-2" required>
            </div>
            <button type="submit" name="submit" class="btn btn-warning btn-block">Verify</button>
        </form>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/datatables.min.js"></script>

    <!-- Bootstrap 5 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>
</html>
