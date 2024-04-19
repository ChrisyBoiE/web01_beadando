<?php
session_start();
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
    <form class="login-form">
        <h2>Login</h2>
        <div class="input-group">
            <input type="text" id="username" required>
            <label for="username">Username</label>
        </div>
        <div class="input-group">
            <input type="password" id="password" required>
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