<?php
include '../secondary/config.php';  // Make sure your config file has correct database credentials

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (!$email || !$new_password) {
        $_SESSION['message'] = "Please fill out all fields.";
        header("Location: forgot_password.php");
        exit();
    }

    $new_pass = sha1($new_password);  // Using sha1 for demonstration, though it's not recommended for production

    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the email exists in the database
        $stmt = $db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Update the user's password in the database
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
            $result = $stmt->execute([$new_pass, $email]);
            if ($result) {
                $_SESSION['message'] = "Your password has been updated successfully.";
                $_SESSION['message_type'] = 'success';
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['message'] = "Failed to update password.";
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = "No account found with that email address.";
            $_SESSION['message_type'] = 'error';
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        $_SESSION['message_type'] = 'error';
    }
    header("Location: forgot_password.php");  // Redirect back to the forgot password page to show message
    exit();
}
?>


<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../css/login_register.css">
</head>

<body>
    <?php
    displayMessage();
    ?>
    <div class="password-reset-container">
        <div class="back-to-login">
            <a href="login.php">&#8592; Back to Login</a>
        </div>
        <form class="password-reset-form" action="forgot_password.php" method="POST">
            <h2>Reset Password</h2>
            <div class="input-group">
                <input type="email" id="email" name="email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <input type="password" id="new_password" name="new_password" required>
                <label for="new_password">New Password</label>
            </div>
            <button type="submit" class="reset-button">Update Password</button>
        </form>
    </div>

</body>

</html>