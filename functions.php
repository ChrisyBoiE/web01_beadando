<?php
if (!function_exists('displayMessage')) {
    function displayMessage() {
        if (isset($_SESSION['message'])) {
            $messageType = $_SESSION['message_type'] ?? 'success'; // Default to 'success'
            echo "<div id='message' class='message {$messageType}'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
    }
}
