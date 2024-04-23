<?php
// Include your config file and ensure session is started
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

// Ebben a változóban tároljuk, hogy az aktuális felhasználó admin-e
$is_admin = isset($_SESSION['roles']) && $_SESSION['roles'] == 'admin';

// Lekérdezzük az adatokat az adatbázisból
try {
    $stmt = $db->prepare("SELECT * FROM Songs WHERE user_id = :id");
    $stmt->bindParam(':id', $_SESSION['id']);
    $stmt->execute();
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

try {
    $stmt2 = $db->prepare("SELECT COUNT(*) as count FROM Songs WHERE user_id = ?");
    $stmt2->execute([$_SESSION['id']]);
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);
    $songs_count = $result['count'];
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
    <link rel="stylesheet" href="css/styles2.css">
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
                    <li><a href="contactus.php">Upload</a></li>
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
                        <li><a href="local-file.php" class="active">Locale Files</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </aside>



        <main id="content" style="padding: 15px 15px">
            <h2>My uploads <?php echo '('.$songs_count.')' ?></h2>
            <header id="top-bar2">
                <div id="song-list">
                    <?php foreach ($songs as $song): ?>
                        <div class="song">
                            <img src="<?php echo $song['music_photo'] ?>" alt="Album Image">
                            <div class="song-info">
                                <h4><?php echo htmlspecialchars($song['title']); ?></h4>
                                <p><?php echo htmlspecialchars($song['artist']); ?></p>
                                <span class="genre"><?php echo htmlspecialchars($song['genre']); ?></span>
                            </div>
                            <div class="song-actions">
                                <a href="<?php echo $song['file_path'] ?>" download>
                                    <i class="fas fa-download"></i>
                                </a>
                                <?php if ($is_admin || $_SESSION['id'] == $song['user_id']): ?>
                                    <div class="more-options">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <div class="dropdown-menu">
                                            <a href="secondary/edit.php?id=<?php echo $song['song_id']; ?>">Edit</a>
                                            <a href="secondary/delete.php?id=<?php echo $song['song_id']; ?>">Delete</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </header>
        </main>
    </div>
    <script src="js/script.js"></script>
</body>

</html>