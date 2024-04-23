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
    $stmt = $db->query("SELECT * FROM Songs");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

try {
    $stmt2 = $db->query("SELECT COUNT(*) as count FROM Songs");
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

        <div class="navbar-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

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
                    <li><a href="albums.php" class="active">Songs</a></li>
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

        <main id="content" style="padding: 15px 15px">
            <h2>Songs <?php echo '(' . $songs_count . ')' ?></h2>
            <header id="top-bar2">
                <div id="song-list">
                    <?php foreach ($songs as $song): ?>
                        <div class="song">
                            <img src="<?php echo $song['music_photo'] ?>" alt="Album Image" class="album-image">
                            <div class="song-info">
                                <h4><?php echo htmlspecialchars($song['title']); ?></h4>
                                <p><?php echo htmlspecialchars($song['artist']); ?></p>
                                <span class="genre"><?php echo htmlspecialchars($song['genre']); ?></span>
                            </div>
                            <div class="song-actions" style="width=300px">
                                <a href="<?php echo $song['file_path'] ?>" download>
                                    <i class="fas fa-download"></i>
                                </a>
                                <button onclick="playAudio(this)" data-file="<?php echo $song['file_path']; ?>"
                                    data-title="<?php echo htmlspecialchars($song['title']); ?>"
                                    data-artist="<?php echo htmlspecialchars($song['artist']); ?>"
                                    data-image="<?php echo $song['music_photo']; ?>">
                                    <i class="fas fa-play"></i>
                                </button>
                                <button class="like-button" data-id="<?php echo $song['song_id']; ?>">
                                    <i class="fas fa-heart" style="color: red"></i>
                                </button>
                                <span class="likes-count"><?php echo $song['likes'] ?> likes</span>
                                <?php if ($is_admin): ?>
                                    <div class="more-options">
                                        <i class="fas fa-ellipsis-v"></i>
                                        <div class="dropdown-menu">
                                            <a href="secondary/edit2.php?id=<?php echo $song['song_id']; ?>">Edit</a>
                                            <a href="secondary/delete.php?id=<?php echo $song['song_id']; ?>">Delete</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="containerbox">
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
                            <button class="control-button play-pause" onclick="togglePlayPause()"><i
                                    class="fas fa-play"></i></button>
                        </div>
                        <div class="progress-container">
                            <span class="current-time">0:00</span>
                            <div class="progress-bar">
                                <div class="progress"></div>
                            </div>
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
            </header>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.like-button').click(function () {
                    var songId = $(this).data('id');
                    var button = $(this);
                    $.ajax({
                        url: 'secondary/like_song.php',
                        type: 'POST',
                        data: { song_id: songId },
                        success: function (response) {
                            var data = JSON.parse(response);
                            button.find('i').css('color', data.liked ? 'red' : 'red');
                            button.siblings('.likes-count').text(data.likes + ' likes');
                        }
                    });
                });
            });
        </script>
    </div>
    <script src="js/script.js"></script>
</body>

</html>