<?php
include '../secondary/config.php';
$is_logged_in = false;
$_SESSION['message'] = 'Successfully logged out!';
$_SESSION['message_type'] = 'error';
session_unset(); // Törli a munkamenet változókat
session_destroy(); // Megszakítja a munkamenetet
header('Location: ../index.php'); // Átirányítás a bejelentkezési oldalra
exit();
?>
