<?php
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require '../../db.php';
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Get token from headers
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;
if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization token missing or invalid']);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);

// Decode the JWT
try {
    $secretKey = 'your_secret_key';
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userId = $decoded->user_id ?? null;
    if (!$userId) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token: ' . $e->getMessage()]);
    exit;
}

// Read the post_id from request body
$data = json_decode(file_get_contents('php://input'), true);
$postId = $data['post_id'] ?? null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id is required']);
    exit;
}

try {
    // Check if user already liked this post
    $stmt = $pdo->prepare("SELECT id FROM post_likes WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$postId, $userId]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($like) {
        // Already liked -> remove the like (unlike)
        $stmt = $pdo->prepare("DELETE FROM post_likes WHERE id = ?");
        $stmt->execute([$like['id']]);
        $message = 'Post unliked';
    } else {
        // Not liked yet -> insert a new like
        $stmt = $pdo->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$postId, $userId]);
        $message = 'Post liked';
    }

    // Return the updated like count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = ?");
    $stmt->execute([$postId]);
    $likeCount = (int)$stmt->fetchColumn();

    http_response_code(200);
    echo json_encode([
        'message' => $message,
        'like_count' => $likeCount,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
