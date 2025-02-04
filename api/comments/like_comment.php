<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle the OPTIONS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require '../../db.php';
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Retrieve JWT from headers
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization token missing or invalid']);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);

// Decode JWT
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

// Read JSON body for comment_id
$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['comment_id'] ?? null;

if (!$commentId) {
    http_response_code(400);
    echo json_encode(['error' => 'comment_id is required']);
    exit;
}

try {
    // Check if user already liked this comment
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = ? AND user_id = ?");
    $stmt->execute([$commentId, $userId]);
    $alreadyLiked = (int)$stmt->fetchColumn();

    if ($alreadyLiked > 0) {
        // Already liked; do nothing or return a simple message
        echo json_encode(['message' => 'Comment already liked']);
    } else {
        // Insert a new like
        $stmt = $pdo->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
        $stmt->execute([$commentId, $userId]);
        echo json_encode(['message' => 'Like added']);
    }

    // Optionally return updated like count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = ?");
    $stmt->execute([$commentId]);
    $likeCount = (int)$stmt->fetchColumn();

    http_response_code(200);
    echo json_encode(['like_count' => $likeCount]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
