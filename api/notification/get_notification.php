<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require '../../db.php';

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

    // Recupera le notifiche per l'utente
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$userId]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($notifications);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
