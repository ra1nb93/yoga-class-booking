<?php
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

require '../../db.php';
require '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization token missing or invalid']);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);

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

$data = json_decode(file_get_contents("php://input"), true);
$postId = $data['post_id'] ?? null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'Post ID is required']);
    exit;
}

try {
    // Verifica se l'utente è il proprietario del post
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post || $post['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized to delete this post']);
        exit;
    }

    // Elimina il post
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$postId]);

    http_response_code(200);
    echo json_encode(['success' => 'Post deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
