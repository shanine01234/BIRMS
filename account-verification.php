<?php 
require_once('inc/header.php');

if (isset($_POST['submit'])) {
    $verification_code = $_POST['verification_code'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
        $row = $stmt->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            
            if ($row['verification_code'] === $verification_code) {

                $update = $conn->query("UPDATE users SET status = 2 WHERE verification_code = '$verification_code' AND email = '$email'");

                ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        Swal.fire({
                                position: "middle",
                                icon: "success",
                                title: "Account verified successfully",
                                showConfirmButton: false,
                                timer: 1500
                        }).then(() => {
                            window.location.href = "login.php"
                        });
                    })
                    </script>
                <?php 
            }else{
                ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        Swal.fire({
                                position: "middle",
                                icon: "error",
                                title: "Incorrect verification code",
                                showConfirmButton: false,
                                timer: 1500
                        }).then(() => {
                            window.location.href = "account-verification.php"
                        });
                    })
                    </script>
                <?php 
            }    
        
        }else{
            ?>
            <script>
               document.addEventListener('DOMContentLoaded', function(){
                Swal.fire({
                        position: "middle",
                        icon: "error",
                        title: "Incorrect email or password",
                        showConfirmButton: false,
                        timer: 1500
                }).then(() => {
                    window.location.href = "account-verification.php"
                });
               })
            </script>
           <?php 
        }

    }else{
        ?>
        <script>
           document.addEventListener('DOMContentLoaded', function(){
            Swal.fire({
                    position: "middle",
                    icon: "error",
                    title: "Incorrect email or password",
                    showConfirmButton: false,
                    timer: 1500
            }).then(() => {
                window.location.href = "account-verification.php"
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
            font-variation-settings: "wdth" 100;
          
        }
        .cover-container {
            position: relative;
            width: 100%;
            height: 400px;
        }
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .cover-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: black;
            text-align: center;
            width: 70%;
        }
        .card {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 700px;
            margin: auto; 
            border: 2px solid black;
        }
        .card img {
            width: 50%;
            height: auto;
        }
        .card-body {
            width: 50%;
            padding: 10px;
        }
        .image-container {
            position: relative;
            overflow: hidden;
            width: 300px; 
            height: 400px; 
        }
        .image-container img {
            display: block;
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: opacity 0.3s ease;
        }
        .image-container:hover img {
            opacity: 0.3; 
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7); 
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            text-align: center;
            padding: 10px;
        }
        .image-container:hover .overlay {
            opacity: 1;
        }
        .overlay-text {
            font-size: 16px; 
            line-height: 1.5;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        footer .social-icons a {
            color: white;
            margin: 0 10px;
            font-size: 20px;
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
        }
        .nav-link, .nav-link i {
            color: black !important;
        }
        .navbar-toggler-icon {
            background-color: black; 
        }
        .signup-container {
            border: 2px solid #ddd; 
            padding: 20px;
            border-radius: 5px; 
            max-width: 400px;
            margin: 0 auto; 
            background-color: white; 
            margin-top:100px;
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
    <label for="password">Re-type Password</label>
    <div class="input-group">
        <input type="password" id="password" name="password" class="form-control my-2" required>
        <div class="input-group-append">
            <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                <i class="fas fa-eye"></i> <!-- Eye icon to toggle password visibility -->
            </span>
        </div>
    </div>
</div>
            <button type="submit" name="submit" class="btn btn-warning btn-block">Verify</button>
        </form>
    </div>

    <!-- Footer -->

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
<script>
    document.getElementById('toggle-password').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        var icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';  
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';  
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>

</html>
