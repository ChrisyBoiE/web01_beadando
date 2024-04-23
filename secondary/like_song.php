<?php
include 'config.php';
if (isset($_POST['song_id'])) {
    $songId = $_POST['song_id'];
    $userId = $_SESSION['id']; // A felhasználó azonosítójának lekérése

    // Ellenőrizzük, hogy a felhasználó már lájkolta-e a számot
    $stmt = $db->prepare("SELECT * FROM user_likes WHERE user_id = ? AND song_id = ?");
    $stmt->execute([$userId, $songId]);
    if ($stmt->rowCount() > 0) {
        // Lájk törlése
        $db->prepare("DELETE FROM user_likes WHERE user_id = ? AND song_id = ?")->execute([$userId, $songId]);
        $liked = false;
    } else {
        // Lájk hozzáadása
        $db->prepare("INSERT INTO user_likes (user_id, song_id) VALUES (?, ?)")->execute([$userId, $songId]);
        $liked = true;
    }

    // Lájkok számának frissítése a songs táblában
    if ($liked) {
        $db->prepare("UPDATE songs SET likes = likes + 1 WHERE song_id = ?")->execute([$songId]);
    } else {
        $db->prepare("UPDATE songs SET likes = likes - 1 WHERE song_id = ?")->execute([$songId]);
    }

    // Visszaadjuk az új lájk számot és a lájkolt állapotot
    $likes = $db->prepare("SELECT likes FROM songs WHERE song_id = ?");
    $likes->execute([$songId]);
    $likesCount = $likes->fetchColumn();

    echo json_encode(['likes' => $likesCount, 'liked' => $liked]);
}
function userLiked($songId) {
    require 'config.php'; // Adatbázis kapcsolat

    // Ellenőrizzük, hogy a felhasználó már lájkolta-e a zenét
    $userId = $_SESSION['id']; // Feltételezzük, hogy a felhasználó azonosítója rendelkezésre áll
    $stmt = $db->prepare("SELECT * FROM user_likes WHERE user_id = ? AND song_id = ?");
    $stmt->execute([$userId, $songId]);
    
    // Ha találunk egyezést, akkor a felhasználó már lájkolta a zenét
    return $stmt->rowCount() > 0;
}
?>
