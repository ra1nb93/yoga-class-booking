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

// Get token
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

// Read the comment_id
$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['comment_id'] ?? null;

if (!$commentId) {
    http_response_code(400);
    echo json_encode(['error' => 'comment_id is required']);
    exit;
}

try {
    // Check if user already liked this comment
    $stmt = $pdo->prepare("SELECT id FROM comment_likes WHERE comment_id = ? AND user_id = ?");
    $stmt->execute([$commentId, $userId]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($like) {
        // Already liked -> remove it
        $stmt = $pdo->prepare("DELETE FROM comment_likes WHERE id = ?");
        $stmt->execute([$like['id']]);
        $message = 'Comment unliked';
    } else {
        // Not liked -> insert a new row
        $stmt = $pdo->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
        $stmt->execute([$commentId, $userId]);
        $message = 'Comment liked';
    }

    // Return updated like count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = ?");
    $stmt->execute([$commentId]);
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
