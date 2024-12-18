<?php 
// ADMIN LOGIN PROCESS
if (isset($_POST['loginAdmin'])) {
    $result = $oop->loginAdmin($_POST['username'] ,$_POST['password']);
    if ($result == 1) {
        $msgAlert = 'Login successfully';
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Login successfully',
                showConfirmButton: false,
                timer: 2000
            }).then(function() {
                window.location = "index.php?page=dashboard";
            });
        </script>
        <?php
    } elseif ($result == 10) {
        $msgAlert = 'Username doesn\'t exist';
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Username doesn\'t exist',
                showConfirmButton: true
            });
        </script>
        <?php
    } elseif ($result == 20) {
        $msgAlert = 'Incorrect password';
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Incorrect password',
                showConfirmButton: true
            });
        </script>
        <?php
    }
}
?>
<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
