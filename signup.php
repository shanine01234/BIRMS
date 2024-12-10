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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            position: relative;
            font-family: "Inconsolata", monospace;
            margin: 0;
            height: 100vh;
        }

        body::before {
            content: "";
            position: absolute;
            top: -105px;
            left: 0;
            width: 100%;
            height: 900px;
            background-image: url('img/photos/one.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.5;
            z-index: -1;
            filter: brightness(80%) blur(2px);
        }

        .signup-container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 100px auto;
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

        .modal-body {
            font-family: Arial, sans-serif;
        }

        .modal-body,
        .modal-body p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="background-color: #fff;">
    <!-- Signup Form -->
    <div class="signup-container">
        <a href="login.php" class="btn btn-warning btn-back">Back</a>
        <h4 class="text-start my-3" style="font-size: 30px;">Sign Up</h4>
        <form id="signup-form">
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
                <div class="progress my-2" style="height: 10px;">
                    <div id="password-strength-bar" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small id="password-strength" class="form-text text-muted"></small>
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
                <input type="checkbox" id="terms" name="terms" class="form-check-input" required>
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
                <div class="modal-body">
                    <p><strong>Terms and Conditions for Bantayan Island Restobar Management System</strong></p>
                    <p>Welcome to Bantayan Retobars! We appreciate your visit. By accessing or using our services, you agree to comply with the following terms and conditions.</p>
                    <ol>
                        <li><strong>Age Restrictions</strong></li>
                        <p>• Guests must be 18 years or older to enter and consume alcoholic beverages.</p>
                        <p>• Valid identification is required upon request.</p>
                        <li><strong>Responsible Consumption</strong></li>
                        <p>• We encourage responsible drinking. We reserve the right to refuse service to anyone appearing intoxicated or behaving inappropriately.</p>
                        <p>• No outside food or drinks are allowed.</p>
                        <li><strong>Reservation Policy</strong></li>
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

    <!-- JavaScript for AJAX and SweetAlert -->
    <script>
        function sanitizeInput(input) {
            return input.replace(/[&<>"'\/]/g, function (char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                    '/': '&#x2F;'
                }[char];
            });
        }

        $(document).ready(function () {
            // Sanitize inputs on form submission
            $('#signup-form').on('submit', function (e) {
                e.preventDefault();

                // Check if terms are accepted
                if (!$('#terms').is(':checked')) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'You must agree to the terms and conditions.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Sanitize form inputs
                const name = sanitizeInput($('#name').val());
                const contact = sanitizeInput($('#contact').val());
                const email = sanitizeInput($('#email').val());
                const password = sanitizeInput($('#password').val());
                const confirmPassword = sanitizeInput($('#confirm-password').val());

                $.ajax({
                    type: 'POST',
                    url: 'create_account.php',
                    data: {
                        name: name,
                        contact: contact,
                        email: email,
                        password: password,
                        confirm_password: confirmPassword
                    },
                    success: function (response) {
                        const jsonResponse = JSON.parse(response);
                        Swal.fire({
                            title: 'Success!',
                            text: jsonResponse.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            if (jsonResponse.redirect) {
                                window.location.href = jsonResponse.redirect;
                            }
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: xhr.responseJSON ? xhr.responseJSON.message : 'There was an error processing your request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            // Toggle password visibility
            function togglePasswordVisibility(button, field, icon) {
                button.on('click', function () {
                    if (field.attr('type') === 'password') {
                        field.attr('type', 'text');
                        icon.removeClass('fas fa-eye').addClass('fas fa-eye-slash');
                    } else {
                        field.attr('type', 'password');
                        icon.removeClass('fas fa-eye-slash').addClass('fas fa-eye');
                    }
                });
            }

            togglePasswordVisibility($('#toggle-password'), $('#password'), $('#password-icon'));
            togglePasswordVisibility($('#toggle-confirm-password'), $('#confirm-password'), $('#confirm-password-icon'));

            // Password strength checker
            const passwordField = $('#password');
            const strengthBar = $('#password-strength-bar');
            const strengthText = $('#password-strength');

            passwordField.on('input', function () {
                const password = passwordField.val();
                const strength = checkPasswordStrength(password);

                // Update the progress bar
                strengthBar.css('width', strength.score + '%');
                strengthBar.removeClass('bg-danger bg-warning bg-success');
                if (strength.score <= 40) {
                    strengthBar.addClass('bg-danger');
                } else if (strength.score <= 70) {
                    strengthBar.addClass('bg-warning');
                } else {
                    strengthBar.addClass('bg-success');
                }

                // Update the strength message
                strengthText.text(strength.message);
            });

            function checkPasswordStrength(password) {
                let score = 0;
                let message = 'Weak';

                if (password.length >= 6) score += 20;
                if (password.length >= 10) score += 20;
                if (/[a-z]/.test(password)) score += 20; // Lowercase
                if (/[A-Z]/.test(password)) score += 20; // Uppercase
                if (/\d/.test(password)) score += 10;    // Number
                if (/[@$!%*?&]/.test(password)) score += 10; // Special char

                // Update message based on score
                if (score <= 40) {
                    message = 'Weak';
                } else if (score <= 70) {
                    message = 'Moderate';
                } else {
                    message = 'Strong';
                }

                return { score, message };
            }
        });
    </script>
</body>

</html>