<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendEmailNotification($email, $subject, $message)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shaninezaspa179@gmail.com';
        $mail->Password = 'hglesxkasgmryjxq';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('shaninezaspa179@gmail.com', 'Restaurant Admin');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if (isset($_POST['approve'])) {
    $id = $_POST['id'];
    $result = $oop->approveOwner($id);

    $ownerEmail = $oop->getOwnerEmailById($id);

    if ($result == 1) {
        $msgAlert = $oop->alert('Accepted successfully', 'warning', 'check-circle');

        $subject = 'Restaurant Owner Approval';
        $message = 'Congratulations! Your restaurant ownership has been approved.';
        if (sendEmailNotification($ownerEmail, $subject, $message)) {
            $msgAlert .= ' Email sent successfully.';
        } else {
            $msgAlert .= ' Failed to send email.';
        }
    } elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occurred', 'danger', 'x-circle');
    }
}

// DECLINE OWNER
if (isset($_POST['decline'])) {
    $id = $_POST['id'];
    $result = $oop->declineOwner($id);

    // Fetch the owner's email
    $ownerEmail = $oop->getOwnerEmailById($id); // Create this function to fetch email by ID

    if ($result == 1) {
        $msgAlert = $oop->alert('Declined successfully', 'warning', 'check-circle');

        // Send decline notification email
        $subject = 'Restaurant Owner Decline';
        $message = 'We regret to inform you that your restaurant ownership request has been declined.';
        if (sendEmailNotification($ownerEmail, $subject, $message)) {
            $msgAlert .= ' Email sent successfully.';
        } else {
            $msgAlert .= ' Failed to send email.';
        }
    } elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occurred', 'danger', 'x-circle');
    }
}

// DELETE owner
if (isset($_POST['deleteOwner'])) {
    $id = $_POST['id'];
    $result = $oop->deleteOwner($id);
    if ($result == 1) {
        $msgAlert = $oop->alert('Deleted successfully', 'warning', 'check-circle');
    } elseif ($result == 10) {
        $msgAlert = $oop->alert('An error occurred', 'danger', 'x-circle');
    }
}
?>

