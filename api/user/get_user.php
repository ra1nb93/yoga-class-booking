<?php
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require '../../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    if (!$userId) {
        echo json_encode(['error' => 'User ID is required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['error' => 'User not found']);
        } else {
            echo json_encode($user);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
