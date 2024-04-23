<?php
include 'secondary/config.php';
include ('secondary/favicon.php');

$is_logged_in = isset($_SESSION['isloggedin']) && $_SESSION['isloggedin'] === true;

if ($is_logged_in) {
    $username = $_SESSION['username'];
    $gender = $_SESSION['gender'];
    $avatar_image = ($gender == 1) ? 'men.png' : 'women.png';
} else {
    $username = 'Vendég';
    $avatar_image = 'default.png'; // Egy alapértelmezett kép, ha nincs bejelentkezve a felhasználó
}

if (isset($_POST['submit']) && $_FILES['musicFile']['error'] == UPLOAD_ERR_OK) {
    // Validate file size and type if needed, here we assume they are correct
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'] ?? ''; // Using the null coalescing operator to handle optional fields
    $genre = $_POST['genre'];
    $userId = $_SESSION['id'] ?? null; // Safeguarding against undefined index

    if ($userId === null) {
        echo 'User ID is not set. Please log in again.';
        die('User ID is not set. Please log in again.');
    }

    // Process the music file
    $musicFilePath = 'uploads/music/' . basename($_FILES['musicFile']['name']);
    if (move_uploaded_file($_FILES['musicFile']['tmp_name'], $musicFilePath)) {
    } else {
        echo 'Failed to upload music file.';
        die("Failed to upload music file.");
    }

    // Process the optional photo file
    $photoFilePath = "";
    if (!empty($_FILES['musicPhoto']['name'])) {
        if ($_FILES['musicPhoto']['error'] == UPLOAD_ERR_OK) {
            $photoFilePath = 'uploads/imgs/' . basename($_FILES['musicPhoto']['name']);
            if (!move_uploaded_file($_FILES['musicPhoto']['tmp_name'], $photoFilePath)) {
                echo 'Failed to upload photo';
                die("Failed to upload photo.");
            }
        } else {
            echo 'Error uploading photo.';
            die("Error uploading photo.");
        }
    }

    // Inserting data into the database
    try { // Ensure database connection is available
        $query = "INSERT INTO Songs (user_id, title, artist, album, genre, file_path, upload_date, music_photo) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId, $title, $artist, $album, $genre, $musicFilePath, $photoFilePath]);
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    if (isset($_POST['submit'])) {
        // This means there was an error with the file upload
        echo "Error with file upload: " . $_FILES['musicFile']['error'];
    }
}
?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>BEATBOUTIQUE</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <div id="music-player">
        <aside id="sidebar">
            <div class="user-profile">
                <?php if ($is_logged_in): ?>
                    <img src="img/<?php echo $avatar_image; ?>" alt="Profilkép" class="profile-pic">
                    <span class="username"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php else: ?>
                    <div class="logo">
                        <img src="img/logo.png">
                    </div>
                <?php endif; ?>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="albums.php">Songs</a></li>
                    <?php if ($is_logged_in): ?>
                        <li><a href="contactus.php" class="active">Upload</a></li>
                    <?php endif; ?>
                    <li><a href="aboutus.php">About us</a></li>
                    <li><?php if ($is_logged_in): ?>
                            <a href="login_register/logout.php">Sign Out</a>
                        <?php else: ?>
                            <a href="login_register/login.php">Sign In</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
            <?php if ($is_logged_in): ?>
                <div class="sidebar-section">
                    <h2>My music</h2>
                    <ul>
                        <li><a href="local-file.php">Locale Files</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>
        <main id="content" style="padding: 15px 15px">
            <div class="teszt">
                <h2>Uploading music</h2>
                <form action="contactus.php" method="post" enctype="multipart/form-data">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required><br><br>

                    <label for="artist">Artist:</label>
                    <input type="text" id="artist" name="artist" required><br><br>

                    <label for="album">Album:</label>
                    <input type="text" id="album" name="album"><br><br>

                    <label for="genre">Genre:</label>
                    <input type="text" id="genre" name="genre"><br><br>

                    <label for="musicFile">Music file:</label>
                    <input type="file" id="musicFile" name="musicFile" required><br><br>

                    <label for="musicPhoto">Cover image:</label>
                    <input type="file" id="musicPhoto" name="musicPhoto"><br><br>

                    <button type="submit" name="submit">Upload</button>
                </form>
            </div>


        </main>
    </div>
    <script src="js/script.js"></script>
</body>

</html>