<?php
session_start();

$is_logged_in = false;
    if (isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] === true) {
        $is_logged_in = true;
}
?>

<?php
$dsn = 'mysql:host=localhost;dbname=web01;charset=utf8';
$db_host = 'localhost';
$db_name = 'web01';
$db_user = 'web01';
$db_password = 'L5Dve.uU*H4yrNMw';
?>

<?php
$dsn = 'mysql:host=localhost;dbname=web01;charset=utf8'; // Adatbázis nevet állítsd be
$db_user = 'web01'; // Adatbázis felhasználóneved
$db_password = 'L5Dve.uU*H4yrNMw'; // Adatbázis jelszavad
?>

<?php
function displayMessage() {
    if (isset($_SESSION['message'])) {
        $messageType = $_SESSION['message_type'] ?? 'success'; // Alapértelmezett a 'success'
        echo "<div id='message' class='message {$messageType}'>" . htmlspecialchars($_SESSION['message']) . "</div>";
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}
?>
