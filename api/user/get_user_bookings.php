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
        $stmt = $pdo->prepare("
            SELECT b.id, c.title, c.date 
            FROM bookings b
            JOIN classes c ON b.class_id = c.id
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($bookings);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
