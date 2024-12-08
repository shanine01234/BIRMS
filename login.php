<?php 
require_once('inc/header.php');

// reCAPTCHA secret key (replace with your own)
$recaptchaSecret = '6LeLJZIqAAAAAHC-kdtS8KKyK3sjvibhmI5CgMuq';

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['lock_time'] = null;
}

if ($_SESSION['login_attempts'] >= 3 && $_SESSION['lock_time'] === null) {
    $_SESSION['lock_time'] = time();
}

if ($_SESSION['login_attempts'] >= 3) {
    $lock_duration = 180; // 3 minutes
    $elapsed_time = time() - $_SESSION['lock_time'];

    if ($elapsed_time >= $lock_duration) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['lock_time'] = null;
    }
}

if (isset($_POST['login']) && $_SESSION['login_attempts'] < 3) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    //$recaptchaResponse = $_POST['g-recaptcha-response'];

     // Verify reCAPTCHA
     $url = 'https://www.google.com/recaptcha/api/siteverify';
     $data = [
         'secret' => $recaptchaSecret,
         //'response' => $recaptchaResponse
     ];
     
     $options = [
         'http' => [
             'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
             'method'  => 'POST',
             'content' => http_build_query($data)
         ]
     ];
     
     $context  = stream_context_create($options);
     $verify = file_get_contents($url, false, $context);
     $captchaSuccess = json_decode($verify);

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
        $row = $stmt->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['login_attempts'] = 0; // Reset the attempts after successful login
            if ($row['status'] == 1) {
                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "middle",
                            icon: "warning",
                            title: "Account not verified, Please verify your account first",
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.href = "account-verification.php";
                        });
                    })
                </script>
                <?php
            } else {
                $_SESSION['name'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_id'] = $row['id'];    
                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: "middle",
                            icon: "success",
                            title: "Account logged in successfully",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = "restobar.php";
                        });
                    })
                </script>
                <?php
            }
        } else {
            $_SESSION['login_attempts']++;
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        position: "middle",
                        icon: "error",
                        title: "Incorrect email or password. Attempt: <?= $_SESSION['login_attempts']; ?> of 3",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = "login.php";
                    });
                })
            </script>
            <?php
        }
    } else {
        $_SESSION['login_attempts']++;
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: "middle",
                    icon: "error",
                    title: "Incorrect email or password. Attempt: <?= $_SESSION['login_attempts']; ?> of 3",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "login.php";
                });
            })
        </script>
        <?php
    }
}

// If the login button is disabled after 3 failed attempts
if ($_SESSION['login_attempts'] >= 3) {
    $remaining_time = 180 - (time() - $_SESSION['lock_time']);
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('button[type=\"submit\"]').disabled = true;
            let remainingTime = $remaining_time;
            let timer = setInterval(function() {
                if (remainingTime <= 0) {
                    clearInterval(timer);
                    document.querySelector('button[type=\"submit\"]').disabled = false;
                }
                document.querySelector('button[type=\"submit\"]').textContent = 'Try again in ' + remainingTime + 's';
                remainingTime--;
            }, 1000);
        })
    </script>";
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

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inconsolata:wght@200..900&display=swap" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LeLJZIqAAAAAO8809SbMug7D8oCmhaSn_2i7BiT"></script>

    <style>
        body {
    font-family: "Roboto", sans-serif;
    background: url('img/photos/log.jpg') no-repeat center center fixed; /* Background image */
    background-size: cover; /* Ensure the image covers the entire screen */
    color: #495057;
    min-height: 100vh;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
}
.login-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    margin-top: -10px;
}


.login-container h4 {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
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

        .btn-warning {
    background-color: #f0ad4e;
    border-color: #f0ad4e;
    color: #ffffff;
    border-radius: 8px;
    font-weight: 600;
    position: relative;
    padding: 12px 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-warning::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    background: linear-gradient(45deg, #ffeb3b, #f0ad4e, #ffeb3b);
    z-index: -1;
    opacity: 0;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.6), 0 0 20px rgba(255, 255, 255, 0.5), 0 0 30px rgba(255, 255, 255, 0.4), 0 0 40px rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    transition: opacity 0.3s ease-in-out;
}

.btn-warning:hover::before {
    opacity: 1;
}

.btn-warning:hover {
    background-color: #ec971f;
    border-color: #d58512;
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8), 0 0 20px rgba(255, 255, 255, 0.7), 0 0 30px rgba(255, 255, 255, 0.6), 0 0 40px rgba(255, 255, 255, 0.5);
}

        
    </style>
</head>

<body>
    <!-- Navbar -->
    

    <!-- Login Form -->
    <div class="login-container" stye="background: rgba(255, 255, 255, 0.8);">
        
        <h4>Login</h4>
        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="login" class="btn btn-warning btn-block">Login</button>
            <a href="forgot-password.php">Forgot Password?</a>
            <p class="text-center mt-3">Don't have an account? <a href="signup.php">Sign Up</a></p>
            <a href="index.php" class="btn-back">
  <img src="https://img.icons8.com/?size=100&id=EX0c5vXvAejm&format=png&color=000000" alt="Back" width="30" height="30">
</a>

        </form>
    </div>


   
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/custom.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <script>
        function onSubmit(token) {
            document.getElementById("login-form").submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            grecaptcha.ready(function() {
                grecaptcha.execute('6LeLJZIqAAAAAO8809SbMug7D8oCmhaSn_2i7BiT', {action: 'login'}).then(function(token) {
                    document.getElementById("g-recaptcha-response").value = token;
                });
            });
        });
</body>

</html>
