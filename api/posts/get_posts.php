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
require '../../vendor/autoload.php';

try {
    // 1) Fetch posts with a subselect that counts likes
    $stmt = $pdo->query("
        SELECT 
            p.id, 
            p.content, 
            p.created_at, 
            p.user_id, 
            u.name AS author,
            -- Subselect to get total post likes
            (SELECT COUNT(*) 
             FROM post_likes pl 
             WHERE pl.post_id = p.id
            ) AS like_count
        FROM posts p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2) Fetch comments for each post
    foreach ($posts as &$post) {
        $stmtComments = $pdo->prepare("
            SELECT 
                c.id, 
                c.content, 
                c.created_at, 
                c.user_id,
                u.name AS author,
                -- Subselect for comment likes
                (SELECT COUNT(*) 
                 FROM comment_likes cl 
                 WHERE cl.comment_id = c.id
                ) AS like_count
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at ASC
        ");
        $stmtComments->execute([$post['id']]);
        $post['comments'] = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
    }

    http_response_code(200);
    echo json_encode($posts);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
