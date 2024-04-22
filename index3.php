<?php
include ('favicon.php');
include 'config.php';
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>
        BEATBOUTIQUE
    </title>
    <link rel="stylesheet" href="css/styles2.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-brand"><img src="logo.png" alt="logo" height="50px"></a>
            <div class="navbar-menu">
                <a href="index.php" class="navbar-item active">Home</a>
                <a href="albums.php" class="navbar-item">Albums</a>
                <a href="aboutus.php" class="navbar-item">About us</a>
                <a href="contactus.php" class="navbar-item">Contact us</a>
                <?php if ($is_logged_in): ?>
                    <a href="logout.php" class="navbar-item">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="navbar-item">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container"><img src="img/home.jpg" alt=""></div>

</body>

</html>