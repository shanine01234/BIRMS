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
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
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

        .nav-link,
        .nav-link i {

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
        <form id="signup-form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control my-2" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="text" id="contact" name="contact" class="form-control my-2" required pattern="09[0-9]{9}"
                    title="Contact number must be 11 digits and start with '09'">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control my-2" required>
            </div>
            <div class="form-group position-relative">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control my-2" required>
                    <button type="button" id="toggle-password" class="btn btn-light border"
                        style="height: 39px; top: 7px;">
                        <i id="password-icon" class="fas fa-eye"></i>
                    </button>
                </div>
                <!-- Password Strength Progress Bar -->
                <div class="progress my-2" style="height: 10px;">
                    <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%;"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <small id="password-strength" class="form-text"></small>
            </div>
            <div class="form-group position-relative">
                <label for="confirm-password">Confirm Password</label>
                <div class="input-group">
                    <input type="password" id="confirm-password" name="confirm_password" class="form-control my-2"
                        required>
                    <button type="button" id="toggle-confirm-password" class="btn btn-light border"
                        style="height: 39px; top: 7px;">
                        <i id="confirm-password-icon" class="fas fa-eye"></i>
                    </button>
                </div>
                <small id="password-match" class="form-text"></small>
            </div>
            <div class="form-check my-3">
                <input type="checkbox" id="terms" name="terms" class="form-check-input">
                <label for="terms" class="form-check-label">
                    I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and
                        Conditions</a>.
                </label>
            </div>
            <button type="submit" name="signup" class="btn btn-warning btn-block">Sign Up</button>
        </form>
    </div>

    <!-- JavaScript for AJAX and SweetAlert -->
    <script>
        $(document).ready(function () {
            $('#signup-form').on('submit', function (e) {
                e.preventDefault(); // Prevent form from submitting normally

                $.ajax({
                    type: 'POST',
                    url: 'create_account.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        const res = JSON.parse(response);
                        Swal.fire({
                            title: 'Success!',
                            text: res.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was an error processing your request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>