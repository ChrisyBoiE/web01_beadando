<?php
session_start();
require_once('config.php'); // Include your database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a select statement to fetch user data using a parameterized query
        $stmt = $db->prepare("SELECT id, password FROM users WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && sha1($password) === $user['password']) {
            $_SESSION['message'] = 'Successfully logged in!';
            $_SESSION["user_id"] = $user['id'];
            header('Location: main.php');
            exit();
        } else {
            // If the login was unsuccessful
            $_SESSION['message'] = "Invalid username or password.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        header("Location: login.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <?php if (isset($_SESSION['message'])): ?>
        <div id="message" class="message"><?php echo $_SESSION['message']; ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>Login</h2>
            <div class="input-group">
                <input type="text" id="username" name="username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" required>
                <label for="password">Password</label>
                <div class="forgot-password" onclick="goto('forgot_password')">Forgot password?</div>
            </div>
            <button type="submit" class="login-button">LOGIN</button>
            <div class="signup">
                <p>Or Sign Up Using</p>
                <button na type="button" class="signup-button" onclick="goto('signup')">SIGN UP</button>
            </div>
        </form>
    </div>

    <script src="js/login.js"></script>
</body>

</html>