<?php
include 'db.php';

// Allow both GET and POST methods
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

try {
    // Delete from child tables first
    $conn->exec("DELETE FROM likes");
    $conn->exec("DELETE FROM dislikes");

    // Then delete users
    $conn->exec("DELETE FROM users");
    $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");

    // Reinsert fresh users
    $conn->exec("
        INSERT INTO users (name, age, photo) VALUES
        ('Amy', 25, 'img/1.jpg'),
        ('Bob', 27, 'img/2.jpg'),
        ('Charlie', 24, 'img/3.jpg'),
        ('Diana', 26, 'img/4.jpg'),
        ('Eve', 22, 'img/5.jpg'),
        ('Frank', 29, 'img/6.jpg'),
        ('Grace', 23, 'img/7.jpg'),
        ('Hank', 30, 'img/8.jpg')
    ");

    // Optional: reset session to first user
    session_start();
    $_SESSION['user_id'] = 1;

    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
