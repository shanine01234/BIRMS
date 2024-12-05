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
 <link rel="icon" type="image/png" href="img/d3f06146-7852-4645-afea-783aef210f8a.jpg" alt="" width="30" height="24" style="border-radius: 100px;">
    <!-- Custom fonts for this template-->
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

        .signup-container {
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            max-width: 350px; 
            margin: 0 auto;
            background-color: white;
            margin-top: 50px;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-control {
            margin-bottom: 10px;
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

        .checkbox-container {
            display: flex;
            justify-content: space-between;
        }
        
        .checkbox-container input {
            margin-right: 10px;
        }
    </style>
</head>

<body style="background-color: #fff;">
    <!-- Signup Form -->
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 24px;">Account Verification</h4>
        <p>Please check your email account. We sent a code to your email account.</p>
        <form method="post">
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" id="verification_code" name="verification_code" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="retype-password">Re-type Password</label>
                <input type="password" id="retype-password" name="retype-password" class="form-control" required>
            </div>
            
            <!-- Show Password Checkbox -->
            <div class="checkbox-container">
                <label><input type="checkbox" id="password-checkbox"> Show Password</label>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" name="submit" class="btn btn-warning btn-block">Verify</button>
        </form>
    </div>

    <script>
        // Toggle password visibility with a checkbox
        document.getElementById('password-checkbox').addEventListener('change', function () {
            var passwordField = document.getElementById('password');
            var retypePasswordField = document.getElementById('retype-password');
            
            if (this.checked) {
                passwordField.type = 'text';  // Show password
                retypePasswordField.type = 'text';  // Show re-type password
            } else {
                passwordField.type = 'password';  // Hide password
                retypePasswordField.type = 'password';  // Hide re-type password
            }
        });
    </script>
</body>

</html>
