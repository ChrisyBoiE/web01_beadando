<?php
// session_start(); // Start the session at the beginning of the script
include 'config.php'; // Include your database configuration file

if (!isset($_SESSION['isloggedin']) || !$_SESSION['isloggedin']) {
    header('Location: ../login_register/login.php'); // Redirect to login page if not logged in
    exit;
}

$songId = $_GET['id'] ?? null; // Get the song ID from the query string
if (!$songId) {
    die('Song ID is required'); // Ensure the song ID is provided
}

// Fetch the song details
$stmt = $db->prepare("SELECT * FROM Songs WHERE song_id = ?");
$stmt->bindParam(1, $songId); // Bind the song ID to the query
$stmt->execute();
$song = $stmt->fetch();

if (!$song) {
    die('Song not found'); // Check if the song exists
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission if form is submitted
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $genre = $_POST['genre'];

    // Update the song details
    $stmt = $db->prepare("UPDATE Songs SET title = ?, artist = ?, genre = ? WHERE song_id = ?");
    $stmt->execute([$title, $artist, $genre, $songId]); // Execute the update statement

    header('Location: ../albums.php'); // Redirect after successful update
    exit;
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Edit Song</title>
    <link rel="stylesheet" href="../css/login_register.css">
</head>

<body>
    <div class="back-to-login">
        <a href="../local-file.php">&#8592; Back to Songs</a>
    </div>
    <div class="login-container">
        <form class="login-form" method="post">
            <h1>Edit Song</h1>
            <div class="input-group">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($song['title']); ?>" required>
            </div>
            <div class="input-group">
                <label>Artist:</label>
                <input type="text" name="artist" value="<?php echo htmlspecialchars($song['artist']); ?>" required>
            </div>
            <div class="input-group">
                <label>Genre:</label>
                <input type="text" name="genre" value="<?php echo htmlspecialchars($song['genre']); ?>" required>
            </div>
            <button class="login-button" type="submit">Update</button>
        </form>
    </div>
</body>

</html>