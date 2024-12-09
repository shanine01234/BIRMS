<?php
require('../../inc/function.php');

// Start the session
session_start();

// Redis configuration
$redis = new Redis();
$redis->connect('127.0.0.1', 6379); // Update Redis host and port if needed

// Logout function to destroy all sessions for the user
function logoutUser($redis) {
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        $sessionKey = "user:$userId:sessions";

        // Fetch all active session IDs for this user
        $sessions = $redis->sMembers($sessionKey);

        // Invalidate all sessions
        foreach ($sessions as $sessionId) {
            // Destroy each session
            session_id($sessionId);
            session_start();
            session_destroy();
            $redis->del("PHPREDIS_SESSION:$sessionId"); // Delete session data from Redis
        }

        // Remove the session tracking key for this user
        $redis->del($sessionKey);

        // Destroy the current session
        session_unset();
        session_destroy();
    }
}

// Call the logout function
logoutUser($redis);

?>
<script>
    window.location = "../login.php";
</script>
