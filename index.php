<?php
include_once 'config.php';
include ('favicon.php');

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
        die('User ID is not set. Please log in again.');
    }

    // Process the music file
    $musicFilePath = 'uploads/music/' . basename($_FILES['musicFile']['name']);
    if (move_uploaded_file($_FILES['musicFile']['tmp_name'], $musicFilePath)) {
        echo "Music file uploaded successfully.";
    } else {
        die("Failed to upload music file.");
    }

    // Process the optional photo file
    $photoFilePath = "";
    if (!empty($_FILES['musicPhoto']['name'])) {
        if ($_FILES['musicPhoto']['error'] == UPLOAD_ERR_OK) {
            $photoFilePath = 'uploads/imgs/' . basename($_FILES['musicPhoto']['name']);
            if (!move_uploaded_file($_FILES['musicPhoto']['tmp_name'], $photoFilePath)) {
                die("Failed to upload photo.");
            }
        } else {
            die("Error uploading photo.");
        }
    }

    // Inserting data into the database
    try {
        include 'config.php'; // Ensure database connection is available
        $query = "INSERT INTO Songs (user_id, title, artist, album, genre, file_path, upload_date, music_photo) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId, $title, $artist, $album, $genre, $musicFilePath, $photoFilePath]);
        echo "Song data successfully uploaded!";
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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <div id="music-player">

        <aside id="sidebar">
            <div class="logo">
                <img src="logo.png" alt="Zenei Portál Logó">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="albums.php">Songs</a></li>
                    <li><a href="aboutus.php">About us</a></li>
                    <li><a href="contactus.php">Contact us</a></li>
                    <li><?php if ($is_logged_in): ?>
                            <a href="logout.php" class="navbar-item">Sign Out</a>
                        <?php else: ?>
                            <a href="login.php" class="navbar-item">Sign In</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-section">
                <h2>Saját Zene</h2>
                <ul>
                    <li><a href="#recently-played">Recently Played</a></li>
                    <li><a href="#favorite-songs">Favorite Songs</a></li>
                    <li><a href="#local-file">Locale Files</a></li>
                </ul>
            </div>
        </aside>

        <main id="content">
            <header id="top-bar">
                <!-- <div class="search-container">
                    <input type="search" placeholder="Keresés..." id="search-box">
                    <button type="submit" id="search-btn">Keresés</button>
                </div> -->
                <div class="user-controls">
                    <div class="user-profile">
                        <?php if ($is_logged_in): ?>
                            <img src="img/<?php echo $avatar_image; ?>" alt="Profilkép" class="profile-pic">
                            <span class="username"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <section id="playlist">
                <h2 class="section-title">CURATED PLAYLIST</h2>
            </section>

            <section id="popular-artists">
                <h2 class="section-title">Popular Artist</h2>
                <div class="artists-grid">
                    <!-- Egy előadó kártyája -->
                    <div class="artist-card">
                        <img src="https://ew.com/thmb/4pgQGiJuJkiOs-rH_C-T62P8jGg=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/The-Weeknd-c1ee39aa91df4baf91560c1b07e71ad8.jpg"
                            alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">The Creator</h3>
                        </div>
                    </div>
                    <div class="artist-card">
                        <img src="https://m.media-amazon.com/images/M/MV5BMzcyOTM2NDA5OF5BMl5BanBnXkFtZTgwMTYzMTQzNzM@._V1_.jpg"
                            alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">21 Savage</h3>
                        </div>
                    </div>
                    <div class="artist-card">
                        <img src="https://cdn.ripost.hu/2023/01/dzwJOrl5S7XmTH7B5Cr95T7ixKtVMNGIVjWymtvDpGc/fit/1108/622/no/1/aHR0cHM6Ly9jbXNjZG4uYXBwLmNvbnRlbnQucHJpdmF0ZS9jb250ZW50L2VmNjBjODRhODY0MDRlMTBhMjA2YjQ5MmNmMGZhMjEz.jpg"
                            alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">Zámbó Jimmy</h3>
                        </div>
                    </div>
                    <div class="artist-card">
                        <img src="https://story.hu/uploads/2023/10/Azahriah.png" alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">Azahriah</h3>
                        </div>
                    </div>
                    <div class="artist-card">
                        <img src="https://story.hu/uploads/2023/10/Azahriah.png" alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">Azahriah</h3>
                        </div>
                    </div>
                    <div class="artist-card">
                        <img src="https://story.hu/uploads/2023/10/Azahriah.png" alt="The Creator" class="artist-photo">
                        <div class="artist-box">
                            <h3 class="artist-name">Azahriah</h3>
                        </div>
                    </div>
                    <!-- További előadó kártyák -->
                    <!-- ... -->
                </div>
            </section>


            <div class="music-player-controls">
                <div class="current-track">
                    <div class="track-artwork">
                        <img src="https://s.24.hu/app/uploads/2024/04/central-0762013799-1024x576.jpg"
                            alt="Track Artwork">
                    </div>
                    <div class="track-info">
                        <span class="track-title">Azahriah</span>
                        <span class="track-artist">Cipoe</span>
                    </div>
                </div>
                <div class="playback-controls">
                    <button class="control-button" onclick="prevTrack()"><i class="fas fa-step-backward"></i></button>
                    <button class="control-button play-pause" onclick="togglePlayPause()"><i
                            class="fas fa-play"></i></button>
                    <button class="control-button" onclick="nextTrack()"><i class="fas fa-step-forward"></i></button>
                </div>
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <span class="current-time">0:00</span>
                    <span class="total-time">3:12</span>
                </div>
                <div class="volume-controls">
                    <button class="control-button" onclick="toggleMute()">
                        <i class="fas fa-volume-up"></i>
                    </button>
                    <input type="range" class="volume-slider" min="0" max="100" value="50"
                        oninput="setVolume(this.value)">
                </div>
            </div>

            <div class="teszt">
                <h2>Zene Feltöltése</h2>
                <form action="index.php" method="post" enctype="multipart/form-data">
                    <label for="title">Cím:</label>
                    <input type="text" id="title" name="title" required><br><br>

                    <label for="artist">Előadó:</label>
                    <input type="text" id="artist" name="artist" required><br><br>

                    <label for="album">Album:</label>
                    <input type="text" id="album" name="album"><br><br>

                    <label for="genre">Műfaj:</label>
                    <input type="text" id="genre" name="genre"><br><br>

                    <label for="musicFile">Zenefájl:</label>
                    <input type="file" id="musicFile" name="musicFile" required><br><br>

                    <label for="musicPhoto">Borítókép:</label>
                    <input type="file" id="musicPhoto" name="musicPhoto"><br><br>

                    <button type="submit" name="submit">Feltöltés</button>
                </form>
            </div>

        </main>

    </div>



    <script src="js/script.js"></script>
</body>

</html>