<?php
require 'inc/header.php';

if (isset($_POST['verify-code'])) {
    $email = $_POST['email'];
    $input_code = $_POST['reset-code'];

    // Check database connection
    if (!isset($conn)) {
        die("<script>Swal.fire('Error', 'Database connection error.', 'error');</script>");
    }

    // Check if the reset_code matches
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND reset_code = ?");
    $stmt->bind_param("ss", $email, $input_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Reset code is correct; mark the reset process as completed
        $update_stmt = $conn->prepare("UPDATE users SET reset_code = NULL WHERE email = ?");
        $update_stmt->bind_param("s", $email);
        $update_stmt->execute();

        echo "<script>
                Swal.fire('Success', 'Your password reset has been successfully verified. You can now log in.', 'success').then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
    } else {
        echo "<script>Swal.fire('Error', 'Invalid verification code.', 'error');</script>";
    }
}
?>

<form method="post">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
    <label for="reset-code">Enter Verification Code</label>
    <input type="text" name="reset-code" id="reset-code" class="form-control" required>
    <button type="submit" name="verify-code" class="btn btn-primary">Verify</button>
</form>
