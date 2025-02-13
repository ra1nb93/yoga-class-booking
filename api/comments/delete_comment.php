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
$commentId = $data['comment_id'] ?? null;

if (!$commentId) {
    http_response_code(400);
    echo json_encode(['error' => 'Comment ID is required']);
    exit;
}

try {
    // Verifica se l'utente è il proprietario del commento
    $stmt = $pdo->prepare("SELECT user_id FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$comment || $comment['user_id'] != $userId) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized to delete this comment']);
        exit;
    }

    // Elimina il commento
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);

    http_response_code(200);
    echo json_encode(['success' => 'Comment deleted successfully']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
