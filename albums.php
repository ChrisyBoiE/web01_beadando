<?php
include ('favicon.php');
include 'config.php';
?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>BEATBOUTIQUE</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="music-player">

        <aside id="sidebar">
            <div class="logo">
                <img src="logo.png" alt="Zenei Portál Logó">
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="albums.php" class="active">Songs</a></li>
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
                <div class="search-container">
                    <input type="search" placeholder="Keresés..." id="search-box">
                    <button type="submit" id="search-btn">Keresés</button>
                </div>
                <div class="user-controls">
                    <div class="notifications">
                        <img src="notification-icon.png" alt="Értesítések">
                    </div>
                    <div class="user-profile">
                        <img src="user-avatar.png" alt="Profilkép" class="profile-pic">
                        <span class="username">Felhasználónév</span>
                        <div class="profile-dropdown">
                            <!-- Profilkezelési lehetőségek lenyíló menü -->
                        </div>
                    </div>
                </div>
            </header>

            <section id="playlist">
                <h2 class="section-title">Kurátált lejátszási lista</h2>
                <div class="playlist-items">
                    <div class="playlist-item">
                        <div class="album-cover">
                            <img src="album-cover1.jpg" alt="Album borító">
                        </div>
                        <div class="song-info">
                            <h3 class="song-title">Dal címe 1</h3>
                            <p class="artist-name">Előadó neve 1</p>
                        </div>
                        <div class="play-time">
                            <span class="duration">3:30</span>
                        </div>
                        <button class="play-button">Lejátszás</button>
                    </div>
                    <!-- További playlist item-ek hasonló szerkezettel -->
                </div>
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
                    <!-- További előadó kártyák -->
                    <!-- ... -->
                </div>
            </section>

        </main>

    </div>

    <script src="script.js"></script>
</body>

</html>