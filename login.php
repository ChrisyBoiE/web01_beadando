<?php
session_start();
include 'config.php'; // Include your database configuration

$message = ""; // Initialize an empty message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare a select statement to fetch user data
        $stmt = $db->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session variables and redirect to main.php
            $_SESSION["user_id"] = $user['id'];
            header('Location: main.php');
            exit();
        } else {
            // If the login was unsuccessful
            $message = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
}

// Store message in session to display it
if (!empty($message)) {
    $_SESSION['message'] = $message;
    header("Location: login.php"); // Redirect back to the login page
    exit();
}
?>


<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Panel</title>
<link rel="stylesheet" href="/css/styles.css">
</head>
<body>

<?php if (isset($_SESSION['message'])): ?>
    <div id="message" class="message"><?php echo $_SESSION['message']; ?></div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<div class="login-container">
    <form class="login-form" method="POST" action="login.php">
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
            <button type="button" class="signup-button" onclick="goto('signup')">SIGN UP</button>
        </div>
    </form>
</div>

<script src="/js/login.js"></script>
</body>
</html>