

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

.input-group-text {

background-color: #ffffff;

border: 1px solid #ced4da;

cursor: pointer;

padding: 0.375rem 0.75rem;

}

.input-group-text i {

font-size: 16px;

color: #6c757d;

}

.input-group-text:hover i {

color: #000000;

}

</style>

</head>

<body style="background-color: #fff;">

<!-- Signup Form -->

<div class="signup-container">

<a href="login.php" class="btn btn-warning btn-back">Back</a>

<h4 class="text-start my-3" style="font-size: 30px;">Sign Up</h4>

<form method="post">

<div class="form-group">

<label for="name">Name</label>

<input type="text" id="name" name="name" class="form-control my-2" required>

</div>

<div class="form-group">

<label for="contact">Contact Number</label>

<input type="text" id="contact" name="contact" class="form-control my-2" required pattern="09[0-9]{9}" title="Contact number must be 11 digits and start with '09'">

</div>

<div class="form-group">

<label for="email">Email</label>

<input type="email" id="email" name="email" class="form-control my-2" required>

</div>

<div class="form-group position-relative">

<label for="password">Password</label>

<div class="input-group">

<input type="password" id="password" name="password" class="form-control my-2" required>

<button type="button" id="toggle-password" class="btn btn-light border" style="height: 39px; top: 7px;">

<i id="password-icon" class="fas fa-eye"></i>

</button>

</div>

<!-- Password Strength Progress Bar -->

<div class="progress my-2" style="height: 10px;">

<div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>

</div>

<small id="password-strength" class="form-text"></small>

</div>

<div class="form-group position-relative">

<label for="confirm-password">Confirm Password</label>

<div class="input-group">

<input type="password" id="confirm-password" name="confirm_password" class="form-control my-2" required>

<button type="button" id="toggle-confirm-password" class="btn btn-light border" style="height: 39px; top: 7px;">

<i id="confirm-password-icon" class="fas fa-eye"></i>

</button>

</div>

<small id="password-match" class="form-text"></small>

</div>

<div class="form-check my-3">

<input type="checkbox" id="terms" name="terms" class="form-check-input">

<label for="terms" class="form-check-label">

I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a>.

</label>

</div>

<button type="submit" name="signup" class="btn btn-warning btn-block">Sign Up</button>

</form>

</div>

<!-- Terms and Conditions Modal -->

<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>

<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

</div>

<div class="modal-body" style="font-family: Arial, sans-serif;">

<p><strong>Terms and Conditions for Bantayan Island Restobar Management System</strong></p>

<p>Welcome to Bantayan Retobars! We appreciate your visit. By accessing or using our services, you agree to comply with the following terms and conditions.</p>

<ol>

<li><strong>Age Restrictions</strong></li>

<p>• Guests must be 18 years or older to enter and consume alcoholic beverages.</p>

<p>• Valid identification is required upon request.</p>

<li><strong>Responsible Consumption</strong></li>

<p>• We encourage responsible drinking. We reserve the right to refuse service to anyone appearing intoxicated or behaving inappropriately.</p>

<li><strong>Reservation Policy</strong></li>

<p>• Please notify us at least 24 hours in advance for cancellations or changes.</p>

<li><strong>Payment</strong></li>

<p>• We accept cash and G-cash payment.</p>

<p>• All sales are final. No refunds or exchanges.</p>

<li><strong>Liability</strong></li>

<p>• Bantayan Retobars is not responsible for lost or stolen items. Please keep your belongings secure.</p>

<p>• Guests assume all risks related to their visit, including injury resulting from accidents or incidents within the premises.</p>

<li><strong>Changes to Terms</strong></li>

<p>• Bantayan Retobars reserves the right to update these Terms and Conditions at any time. Changes will be effective immediately upon posting.</p>

</ol>

</div>

<div class="modal-footer" style="font-family: Arial, sans-serif;">

<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

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

<script>

document.addEventListener("DOMContentLoaded", function () {

const passwordInput = document.getElementById("password");

const confirmPasswordInput = document.getElementById("confirm-password");

const passwordStrengthText = document.getElementById("password-strength");

const passwordStrengthBar = document.getElementById("password-strength-bar");

const passwordMatchText = document.getElementById("password-match");

const togglePasswordBtn = document.getElementById("toggle-password");

const passwordIcon = document.getElementById("password-icon");

const toggleConfirmPasswordBtn = document.getElementById("toggle-confirm-password");

const confirmPasswordIcon = document.getElementById("confirm-password-icon");

// Function to determine password strength

function checkPasswordStrength(password) {

let score = 0;

if (password.length >= 6) score += 25; // Length >= 6

if (/[A-Z]/.test(password)) score += 25; // Uppercase letter

if (/[0-9]/.test(password)) score += 25; // Number

if (/[!@#$%^&*]/.test(password)) score += 25; // Special character

return score;

}

// Function to update the progress bar and text

function updateStrengthBar(score) {

passwordStrengthBar.style.width = ${score}%;

if (score < 50) {

passwordStrengthBar.className = "progress-bar bg-danger";

passwordStrengthText.textContent = "Weak Password";

passwordStrengthText.style.color = "red";

} else if (score < 75) {

passwordStrengthBar.className = "progress-bar bg-warning";

passwordStrengthText.textContent = "Moderate Password";

passwordStrengthText.style.color = "orange";

} else {

passwordStrengthBar.className = "progress-bar bg-success";

passwordStrengthText.textContent = "Strong Password!";

passwordStrengthText.style.color = "green";

}

}

// Password strength validation

passwordInput.addEventListener("input", function () {

const password = passwordInput.value;

const score = checkPasswordStrength(password);

updateStrengthBar(score);

});

// Password confirmation validation

confirmPasswordInput.addEventListener("input", function () {

if (confirmPasswordInput.value === passwordInput.value) {

passwordMatchText.textContent = "Passwords match!";

passwordMatchText.style.color = "green";

} else {

passwordMatchText.textContent = "Passwords do not match.";

passwordMatchText.style.color = "red";

}

});

// Toggle show/hide password

togglePasswordBtn.addEventListener("click", function () {

if (passwordInput.type === "password") {

passwordInput.type = "text";

passwordIcon.classList.remove("fa-eye");

passwordIcon.classList.add("fa-eye-slash");

} else {

passwordInput.type = "password";

passwordIcon.classList.remove("fa-eye-slash");

passwordIcon.classList.add("fa-eye");

}

});

toggleConfirmPasswordBtn.addEventListener("click", function () {

if (confirmPasswordInput.type === "password") {

confirmPasswordInput.type = "text";

confirmPasswordIcon.classList.remove("fa-eye");

confirmPasswordIcon.classList.add("fa-eye-slash");

} else {

confirmPasswordInput.type = "password";

confirmPasswordIcon.classList.remove("fa-eye-slash");

confirmPasswordIcon.classList.add("fa-eye");

}

});

});

</script>

<script>

document.addEventListener("DOMContentLoaded", function () {

const form = document.querySelector("form");

form.addEventListener("submit", function (event) {

// Get all input fields

const inputs = form.querySelectorAll("input[type='text'], input[type='email'], input[type='password']");

// Trim whitespace from each input value

inputs.forEach(input => {

input.value = input.value.trim();

});

});

});

</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</body>

</html>

