<?php
include 'db.php';
$query = $conn->query("SELECT DATABASE()")->fetchColumn();
echo "Connected to DB: " . $query;
$stmt = $conn->query("SHOW COLUMNS FROM likes");
$likesCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($likesCols);

$stmt = $conn->query("SHOW COLUMNS FROM dislikes");
$dislikesCols = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($dislikesCols);

?>
