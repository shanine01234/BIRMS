<?php 
require_once('inc/header.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require "./phpmailer/src/Exception.php";
require "./phpmailer/src/PHPMailer.php";
require "./phpmailer/src/SMTP.php";

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $contact_num = $_POST['contact_num'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verification_code = uniqid();

    // Sanitize Inputs
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $contact_num = htmlspecialchars($contact_num, ENT_QUOTES, 'UTF-8');
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Check if Terms and Conditions are agreed to
    if (!isset($_POST['terms'])) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "middle",
                icon: "error",
                title: "Please agree to the Terms and Conditions to sign up.",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = "signup.php"
            });
        });
        </script>
        <?php
        exit;
    }

    // Validate Contact Number
    if (!preg_match('/^09[0-9]{9}$/', $contact_num)) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                position: "middle",
                icon: "error",
                title: "Invalid contact number. Must be 11 digits and start with '09'.",
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = "signup.php"
            });
        });
        </script>
        <?php
        exit;
    }

    $stmt = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($stmt->num_rows) {
       ?>
        <script>
           document.addEventListener('DOMContentLoaded', function(){
            Swal.fire({
                    position: "middle",
                    icon: "error",
                    title: "Account already exists",
                    showConfirmButton: false,
                    timer: 1500
            }).then(() => {
                window.location.href = "signup.php"
            });
           })
        </script>
       <?php 
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $query = $conn->query("INSERT INTO users SET username = '$name', contact_num = '$contact_num', email = '$email', password = '$hashed', verification_code = '$verification_code'");
        if ($query) {

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'shaninezaspa179@gmail.com';
            $mail->Password = 'hglesxkasgmryjxq';
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom('bantayanrestobar@gmail.com', 'Barangay Restobar');

            $mail->addAddress($email);
            $mail->Subject = "Account Verification Code";
            $mail->Body = "This is your verification code: " . $verification_code;

            $mail->send();

            ?>
            <script>
            document.addEventListener('DOMContentLoaded', function(){
                Swal.fire({
                        position: "middle",
                        icon: "success",
                        title: "Account created successfully",
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
    <h4 class="text-start my-3" style="font-size: 30px;">Sign Up</h4>
    <form method="post" id="signupForm">
        <!-- Name -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control my-2" required>
            <small id="nameError" class="text-danger"></small>
        </div>

        <!-- Contact -->
        <div class="form-group">
            <label for="contact">Contact Number</label>
            <input type="text" id="contact" name="contact" class="form-control my-2" required maxlength="11" pattern="09[0-9]{9}" title="Contact number must be 11 digits and start with '09'">
            <small id="contactError" class="text-danger"></small>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control my-2" required>
        </div>

        <!-- Password -->
        <div class="form-group position-relative">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" class="form-control my-2" minlength="8" required>
    <!-- Password Strength Bar -->
    <div class="progress mt-2" style="height: 8px;">
        <div id="passwordStrengthBar" class="progress-bar" role="progressbar" style="width: 0%;"></div>
    </div>
    <small id="passwordStrengthText" class="text-muted"></small>
    <!-- Toggle Password Visibility -->
    <i class="far fa-eye position-absolute" style="right: 10px; top: 50px; cursor: pointer;" id="togglePassword"></i>
</div>


        <!-- Confirm Password -->
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" class="form-control my-2" required>
            <small id="passwordMatch" class="text-danger"></small>
        </div>

        <!-- Terms and Conditions -->
        <div class="form-check my-3">
            <input type="checkbox" id="terms" name="terms" class="form-check-input">
            <label for="terms" class="form-check-label">
                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="signup" class="btn btn-warning btn-block">Sign Up</button>
    </form>
</div>

<script>
    const nameInput = document.getElementById('name');
    const contactInput = document.getElementById('contact');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordStrength = document.getElementById('passwordStrength');
    const passwordMatch = document.getElementById('passwordMatch');
    const nameError = document.getElementById('nameError');
    const contactError = document.getElementById('contactError');
    const togglePassword = document.getElementById('togglePassword');

    // Name Validation: Only Text Allowed
    nameInput.addEventListener('input', () => {
        const nameRegex = /^[A-Za-z\s]+$/;
        if (!nameRegex.test(nameInput.value)) {
            nameError.textContent = "Name can only contain letters and spaces.";
            nameInput.value = nameInput.value.replace(/[^A-Za-z\s]/g, '');
        } else {
            nameError.textContent = "";
        }
    });

    // Contact Validation: Starts with 09 and is 11 Digits
    contactInput.addEventListener('input', () => {
        const contactRegex = /^09[0-9]{0,9}$/;
        if (!contactRegex.test(contactInput.value)) {
            contactError.textContent = "Contact number must start with '09' and be 11 digits.";
            contactInput.value = contactInput.value.replace(/[^0-9]/g, '').slice(0, 11);
        } else {
            contactError.textContent = "";
        }
    });

    password.addEventListener('input', () => {
    const value = password.value;
    let strength = 0;

    // Criteria for password strength
    if (value.length >= 8) strength += 1; // Minimum length
    if (value.match(/[a-z]/)) strength += 1; // Lowercase letter
    if (value.match(/[A-Z]/)) strength += 1; // Uppercase letter
    if (value.match(/[0-9]/)) strength += 1; // Number
    if (value.match(/[@$!%*?&]/)) strength += 1; // Special character

    // Update progress bar and text
    switch (strength) {
        case 0:
            passwordStrengthBar.style.width = "0%";
            passwordStrengthBar.className = "progress-bar";
            passwordStrengthText.textContent = "";
            break;
        case 1:
            passwordStrengthBar.style.width = "20%";
            passwordStrengthBar.className = "progress-bar bg-danger";
            passwordStrengthText.textContent = "Weak";
            break;
        case 2:
            passwordStrengthBar.style.width = "40%";
            passwordStrengthBar.className = "progress-bar bg-danger";
            passwordStrengthText.textContent = "Weak";
            break;
        case 3:
            passwordStrengthBar.style.width = "60%";
            passwordStrengthBar.className = "progress-bar bg-warning";
            passwordStrengthText.textContent = "Medium";
            break;
        case 4:
            passwordStrengthBar.style.width = "80%";
            passwordStrengthBar.className = "progress-bar bg-info";
            passwordStrengthText.textContent = "Strong";
            break;
        case 5:
            passwordStrengthBar.style.width = "100%";
            passwordStrengthBar.className = "progress-bar bg-success";
            passwordStrengthText.textContent = "Very Strong";
            break;
    }
});

    // Confirm Password Validation
    confirmPassword.addEventListener('input', () => {
        if (confirmPassword.value !== password.value) {
            passwordMatch.textContent = "Passwords do not match!";
        } else {
            passwordMatch.textContent = "";
        }
    });

    // Show/Hide Password
    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        confirmPassword.setAttribute('type', type);
        togglePassword.classList.toggle('fa-eye-slash');
    });

    // Prevent Form Submission without Terms Agreement
    document.getElementById('signupForm').addEventListener('submit', function (e) {
        if (!document.getElementById('terms').checked) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Please agree to the Terms and Conditions!"
            });
        }

        if (confirmPassword.value !== password.value) {
            e.preventDefault();
            Swal.fire({
                icon: "error",
                title: "Password Mismatch",
                text: "Make sure passwords match!"
            });
        }
    });
</script>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Terms and Conditions for Bantayan Island Restobar Management System</strong></p>
                    <p>Welcome to Bantayan Retobars! We appreciate your visit. By accessing or using our services, you agree to comply with the following terms and conditions.</p>
                    <ol>
                        <li><strong>Age Restrictions</li>
                            <p>• Guests must be 18 years or older to enter and consume alcoholic beverages.</p>
                            <p>• Valid identification is required upon request.</p>
                        <li><strong>Responsible Consumption</li>
                            <p>• We encourage responsible drinking. We reserve the right to refuse service to anyone appearing intoxicated or behaving inappropriately.</p>
                            <p>• No outside food or drinks are allowed.</p>
                        <li><strong>Reservation Policy/strong></li>
                            <p>• Reservations are recommended for large groups.</p>
                            <p>• Please notify us at least 24 hours in advance for cancellations or changes.</p>
                        <li><strong>Payment</strong></li>
                            <p>• We accept cash and major credit cards.</p>
                            <p>• All sales are final. No refunds or exchanges.</p>
                        <li><strong>Liability</strong></li>
                            <p>• Bantayan Retobars is not responsible for lost or stolen items. Please keep your belongings secure.</p>
                            <p>• Guests assume all risks related to their visit, including injury resulting from accidents or incidents within the premises.</p>
                        <li><strong>Changes to Terms</strong></li>
                            <p>• Bantayan Retobars reserves the right to update these Terms and Conditions at any time. Changes will be effective immediately upon posting.</p>
                    </ol>
                   </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/datatables.min.js"></script>

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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


</body>

</html>
