<?php
session_start();
include 'config.php';

// CREATE TABLE `web01`.`users` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(50) NOT NULL , `password` VARCHAR(50) NOT NULL , `email` VARCHAR(100) NOT NULL , `gender` VARCHAR NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

// Adatbázis kapcsolat létrehozása
try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Adatbázis kapcsolati hiba: " . $e->getMessage());
}

// Az űrlap adatainak kezelése
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $gender_value = ($gender === 'female') ? 0 : 1;

    // Jelszavak egyezésének ellenőrzése
    if ($password !== $confirm_password) {
        die('A jelszavak nem egyeznek.');
    }

    // Jelszó hashelése SHA1 használatával
    $password_hash = sha1($password);

    // Az adatok beszúrása az adatbázisba
    try {
        $stmt = $db->prepare("INSERT INTO users (username, password, email, gender) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password_hash, $email, $gender_value]);
        $_SESSION['message'] = 'Successfully registered, you can now login!';
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        die("Adatbázis hiba: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Panel</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <div class="back-to-login">
        <a href="login.php">&#8592; Back to Login</a>
    </div>
    <div class="signup-container">
        <form class="signup-form" id="signupForm" method="POST">
            <h2>Sign Up</h2>

            <div class="input-group">
                <input type="text" id="username" name="username" required>
                <label for="username">Username</label>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Password</label>
                <div id="capsLockWarning" style="display: none; color: red; font-size:0.75em;opacity:0.75;">
                    Caps Lock is on.
                </div>
            </div>

            <div class="input-group">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <label for="confirm_password">Confirm Password</label>
                <div id="passwordMismatch" style="display: none; color: red; font-size:0.75em;opacity:0.75;">
                    Passwords do not match!
                </div>
            </div>

            <div class="input-group">
                <input type="email" id="email" name="email" required>
                <label for="email">Email Address</label>
            </div>

            <div class="gender-group">
                <input type="radio" id="female" name="gender" value="female" required>
                <label for="female">Female</label>
                <input type="radio" id="male" name="gender" value="male" required>
                <label for="male">Male</label>
            </div>

            <div class="terms-group">
                <input type="checkbox" id="terms" required>
                <label for="terms">I accept all terms and conditions</label>
            </div>

            <button type="submit" name="register" class="signup-button" disabled>REGISTER</button>
        </form>
    </div>

    <script src="js/signup.js"></script>
</body>

</html>