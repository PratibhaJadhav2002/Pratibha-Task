<?php
// Database connection
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

// Function to get user posts
function getUserPosts($userId, PDO $db) {
    $stmt = $db->prepare("SELECT id, title, content, created_at FROM posts WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get user ID from request (sanitize input)
$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

if ($userId > 0) {
    $posts = getUserPosts($userId, $db);
    echo json_encode(['status' => 'success', 'posts' => $posts]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
}

