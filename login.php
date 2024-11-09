<?php 
require_once('inc/header.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
        $row = $stmt->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 1) {
                ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function(){
                        Swal.fire({
                                position: "middle",
                                icon: "warning",
                                title: "Account not verified, Please verify your account first",
                                showConfirmButton: false,
                                timer: 2000
                        }).then(() => {
                            window.location.href = "account-verification.php"
                        });
                    })
                    </script>
                <?php 
            }else{
                $_SESSION['name'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];    
                ?>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    Swal.fire({
                            position: "middle",
                            icon: "success",
                            title: "Account logged in successfully",
                            showConfirmButton: false,
                            timer: 1500
                    }).then(() => {
                        window.location.href = "restobar.php"
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
                    window.location.href = "login.php"
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
                window.location.href = "login.php"
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
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }

        footer .social-icons a {
            color: #ffffff;
            margin: 0 10px;
            font-size: 20px;
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .nav-item {
            margin: 0 15px;
        }

        .nav-link, .nav-link i {
            color: #343a40 !important;
        }

        .navbar-toggler-icon {
            background-color: #343a40;
        }
        .login-container {
           border: 2px solid #ddd; 
           padding: 20px;
           border-radius: 5px; 
           max-width: 400px;
           margin: 0 auto;
            background-color: #f9f9f9; 
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

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Add your nav items here -->
            </ul>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="login-container">
    <a href="index.php" class="btn btn-warning btn-back">Back</a>
    <h4>Login</h4>
    <form method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
         <div class="form-group">
    <label for="password">Password</label>
    <div class="input-group">
        <input type="password" id="password" name="password" class="form-control" required>
        <div class="input-group-append">
            <span class="input-group-text" id="toggle-password" style="cursor: pointer;">
                <i class="fas fa-eye"></i> <!-- Eye icon to toggle password visibility -->
            </span>
        </div>
    </div>
</div>
        <button type="submit" name="login" class="btn btn-warning btn-block">Login</button>
        <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>
</div>


   
    <!-- Bootstrap core JavaScript-->
     <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
     <script>
    // Toggle password visibility
    document.getElementById('toggle-password').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        var icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';  // Show password
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';  // Hide password
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>
</body>

</html>
