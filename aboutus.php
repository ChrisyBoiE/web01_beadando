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
                        <li><a href="contactus.php">Upload</a></li>
                    <?php endif; ?>
                    <li><a href="aboutus.php" class="active">About us</a></li>
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
        </main>
    </div>
    <script src="js/script.js"></script>
</body>

</html>