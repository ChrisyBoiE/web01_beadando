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
        </main>
    </div>
    <script src="js/script.js"></script>
</body>

</html>