<?php

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require '../../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $classId = $data['class_id'] ?? null;

    // Estrai il token dall'header
    $headers = getallheaders();
    $token = str_replace('Bearer ', '', $headers['Authorization'] ?? '');

    try {
        // Decodifica il token JWT
        $payload = explode('.', $token)[1];
        $decodedPayload = json_decode(base64_decode($payload), true);
        if ($decodedPayload['exp'] < time()) {
            http_response_code(401);
            echo json_encode(['error' => 'Token expired']);
            exit;
        }
        $userId = $decodedPayload['user_id'];

        // Cancella la prenotazione
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE user_id = ? AND class_id = ?");
        $stmt->execute([$userId, $classId]);

        echo json_encode(['success' => 'Booking canceled successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
