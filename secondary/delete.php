<?php
include 'config.php'; // Include your database configuration file

if (!isset($_SESSION['isloggedin']) || !$_SESSION['isloggedin']) {
    header('Location: ../login_register/login.php'); // Redirect to login page
    exit;
}

if (!isset($_GET['id'])) {
    die('Song ID is required');
}

$songId = $_GET['id'];

// Check if the current user is the owner or an admin
$stmt = $db->prepare("SELECT * FROM Songs WHERE song_id = ?");
$stmt->execute([$songId]);
$song = $stmt->fetch();

if (!$song) {
    die('Song not found');
}

if ($song['user_id'] != $_SESSION['id'] && $_SESSION['roles'] !== 'admin') {
    die('You do not have permission to delete this song');
}

// Delete the song
$stmt = $db->prepare("DELETE FROM Songs WHERE song_id = ?");
$stmt->execute([$songId]);
header('Location: ../local-file.php'); // Redirect after deletion
exit;
?>
