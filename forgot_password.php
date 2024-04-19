<?php
session_start();
include 'config.php'; // Include your database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    if (!$email || !$new_password || !$old_password) {
        exit("Please fill out all fields.");
    }

    try {
        $db = new PDO($dsn, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetching the user's password hash from the database
        $stmt = $db->prepare("SELECT password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Debug: Output the fetched data and the submitted passwords
        echo "Fetched password from DB: " . ($user ? $user['password'] : 'No user found') . "<br/>";
        echo "Submitted old password, plain: $old_password<br/>";
        echo "Submitted old password, hash: " . password_hash($old_password, PASSWORD_DEFAULT) . "<br/>";
        echo "Submitted new password, hash: " . password_hash($new_password, PASSWORD_DEFAULT) . "<br/>";

        if ($user && password_verify($old_password, $user['password'])) {
            // A régi jelszó helyes, frissíthető az új jelszó
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashed_new_password, $email]);
            echo "Password has been updated successfully.";
        } else {
            echo "Invalid email or old password.";
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
    <title>Password Reset</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>

    <div class="password-reset-container">
        <form class="password-reset-form" method="POST">
            <h2>Reset Password</h2>
            <div class="input-group">
                <input type="email" id="email" name="email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <input type="password" id="old_password" name="old_password" required>
                <label for="old_password">Old Password</label>
            </div>
            <div class="input-group">
                <input type="password" id="new_password" name="new_password" required>
                <label for="new_password">New Password</label>
            </div>
            <button type="submit" class="reset-button">Change Password</button>
        </form>
    </div>

</body>

</html>