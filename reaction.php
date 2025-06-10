<?php

session_start();

// reaction.php

ini_set('display_errors', 1);
error_reporting(E_ALL);


include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $profileId = $_POST['profile_id'] ?? '';

    // Validate input
    if (!in_array($action, ['like', 'dislike']) || !is_numeric($profileId)) {
        http_response_code(400);
        echo "Invalid input.";
        exit;
    }

    // Get user from session
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo "Invalid session or user not logged in.";
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Ensure user exists
    $checkUser = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $checkUser->execute([$userId]);
    if ($checkUser->rowCount() === 0) {
        http_response_code(400);
        echo "Invalid user ID. User does not exist.";
        exit;
    }

    try {
        if ($action === 'like') {
            $stmt = $conn->prepare("INSERT INTO likes (liker_id, liked_id, liked_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $profileId]);
        } elseif ($action === 'dislike') {
            $stmt = $conn->prepare("INSERT INTO dislikes (disliker_id, disliked_id, disliked_at) VALUES (?, ?, NOW())");
            $stmt->execute([$userId, $profileId]);
        }

        echo "Reaction saved successfully!";
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Error saving reaction: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Method not allowed";
}
