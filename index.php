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
        die('User ID is not set. Please log in again.');
    }

    // Process the music file
    $musicFilePath = 'uploads/music/' . basename($_FILES['musicFile']['name']);
    if (move_uploaded_file($_FILES['musicFile']['tmp_name'], $musicFilePath)) {
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

<?php
$query = "SELECT DISTINCT artist, music_photo FROM Songs ORDER BY RAND() LIMIT 6";
try {
    // Prepare and execute the query
    $stmt = $db->prepare($query);
    $stmt->execute();
    // Fetch all the unique artists
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
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
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="albums.php">Songs</a></li>
                    <?php if ($is_logged_in): ?>
                        <li><a href="contactus.php">Upload</a></li>
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

        <main id="content">
            <section id="playlist">
                <img src="img/hd.jpg" alt="" srcset="">
                <h2 class="section-title">CURATED PLAYLIST</h2>
                <h3 class="section-title">Azahriah - skatulya l</h3>
                <p>Enjoy vivid emotions with this stunning music album. Each track is a story.</p>
            </section>

            <section id="popular-artists">
                <h2 class="section-title">Popular Artists</h2>
                <div class="artists-grid">
                    <?php foreach ($artists as $artist): ?>
                        <div class="artist-card">
                            <img src="<?php echo htmlspecialchars($artist['music_photo']); ?>"
                                alt="<?php echo htmlspecialchars($artist['artist']); ?>" class="artist-photo">
                            <div class="artist-box">
                                <h3 class="artist-name"><?php echo htmlspecialchars($artist['artist']); ?></h3>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- <div class="music-player-controls">
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
            </div> -->
        </main>

    </div>
    <script src="js/script.js"></script>
</body>

</html>