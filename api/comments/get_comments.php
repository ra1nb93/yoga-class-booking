<?php
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require '../../db.php';

$postId = $_GET['post_id'] ?? null;

if (!$postId) {
    http_response_code(400);
    echo json_encode(['error' => 'post_id is required']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            c.id, 
            c.content, 
            c.created_at,
            c.user_id,
            u.name AS author,
            (SELECT COUNT(*) FROM comment_likes cl WHERE cl.comment_id = c.id) AS like_count
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = ?
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([$postId]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($comments);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
