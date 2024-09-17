<?php
session_start(); // Start the session
require_once('../inc/db.php');       // Include PDO initialization
require_once('../inc/function.php'); // Include the functions

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id']; // Admin or Owner ID

// Fetch sent and received messages
$sentMessages = getSentMessages($user_id);
$receivedMessages = getReceivedMessages($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include your meta and CSS links here -->
    <title>Admin Messages</title>
</head>
<body>
    <h1>Admin Messages</h1>

    <h2>Sent Messages</h2>
    <table>
        <thead>
            <tr>
                <th>Recipient</th>
                <th>Message</th>
                <th>Sent At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sentMessages as $message): ?>
                <tr>
                    <td>
                        <?php
                        $receiver = getOwnerById($message['receiver_id']);
                        echo $receiver['firstname'] . ' ' . $receiver['lastname'];
                        ?>
                    </td>
                    <td><?= htmlspecialchars($message['message']) ?></td>
                    <td><?= $message['sent_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Received Messages</h2>
    <table>
        <thead>
            <tr>
                <th>Sender</th>
                <th>Message</th>
                <th>Received At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receivedMessages as $message): ?>
                <tr>
                    <td>
                        <?php
                        $sender = getAdminById($message['sender_id']);
                        echo $sender['username'];
                        ?>
                    </td>
                    <td><?= htmlspecialchars($message['message']) ?></td>
                    <td><?= $message['sent_at'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
