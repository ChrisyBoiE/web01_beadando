<?php

// CREATE TABLE `web01`.`users` (`id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(50) NOT NULL , `password` VARCHAR(50) NOT NULL , `email` VARCHAR(100) NOT NULL , `gender` VARCHAR NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

session_start();

$host = 'localhost'; // vagy az adatbázis szervered címe
$dbname = 'web01';
$db_user = 'web01';
$db_pass = 'L5Dve.uU*H4yrNMw';

// Adatbázis kapcsolat létrehozása
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $db_user, $db_pass);
    // Beállítjuk, hogy hiba esetén kivételt dobjon a PDO.
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Adatbázis kapcsolati hiba: " . $e->getMessage());
}

// Az űrlap adatainak kezelése
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);
    $gender_value = ($gender === 'female') ? 0 : 1;


    // Itt lehetnek további érvényesítések...

    // Jelszavak egyezésének ellenőrzése
    if ($password !== $confirm_password) {
        die('A jelszavak nem egyeznek.');
    }

    // Jelszó hashelése
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

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
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>

    <div class="signup-container">
        <div class="back-to-login">
            <a href="login.php">&#8592; Back to Login</a>
        </div>
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

            <button type="submit" class="signup-button" disabled>REGISTER</button>
        </form>
    </div>

    <script src="/js/signup.js"></script>
</body>

</html>