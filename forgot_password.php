<?php
session_start();
include 'config.php'; // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (!$email || !$new_password) {
        exit("Please fill out all fields.");
    }

    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

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
            $stmt->execute([$hashed_new_password, $email]);
            echo "Your password has been updated successfully.";
        } else {
            echo "No account found with that email address.";
        }
    } catch (PDOException $e) {
        exit("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>

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