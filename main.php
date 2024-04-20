<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Main Page</title>
</head>
<body>
<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
<p>Successful login.</p>
</body>
</html>
