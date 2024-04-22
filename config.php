<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define the database host and name
$db_host = 'localhost';
$db_name = 'web01';
$db_user = 'web01';
$db_password = 'L5Dve.uU*H4yrNMw';

// Use the defined variables to construct the DSN
$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";

try {
    $db = new PDO($dsn, $db_user, $db_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

require_once 'functions.php';
?>