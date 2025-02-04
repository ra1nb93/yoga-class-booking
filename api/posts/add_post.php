<?php
// Impostazioni CORS e metodi ammessi
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Gestione preflight request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require '../../db.php';

// Percorso assoluto per autoload.php
$autoloadPath = '../../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require $autoloadPath;
    error_log("Autoload file loaded successfully from: $autoloadPath");
} else {
    error_log("Autoload file not found at: $autoloadPath");
    exit('Failed to load autoload.php');
}

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Recupera il token JWT dall'intestazione Authorization
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? null;

if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    error_log("Token JWT mancante o invalido.");
    http_response_code(401);
    echo json_encode(['error' => 'Authorization token missing or invalid']);
    exit;
}

$token = str_replace('Bearer ', '', $authHeader);

// Decodifica del token JWT
try {
    $secretKey = 'your_secret_key'; // Sostituisci con la tua chiave segreta
    $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
    $userId = $decoded->user_id ?? null;

    if (!$userId) {
        error_log("Token decodificato, ma user_id non valido.");
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit;
    }
    error_log("Token decodificato con successo: user_id = $userId");
} catch (Exception $e) {
    error_log("Errore nella decodifica del token JWT: " . $e->getMessage());
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token: ' . $e->getMessage()]);
    exit;
}

// Recupera i dati dal corpo della richiesta (content)
$data = json_decode(file_get_contents("php://input"), true);
$content = trim($data['content'] ?? '');

if (!$content) {
    error_log("Content mancante nella richiesta.");
    http_response_code(400);
    echo json_encode(['error' => 'Content missing']);
    exit;
}

// Salvataggio del post associato all'utente dal token
try {
    $pdo->beginTransaction();

    error_log("Inserimento del post con user_id: $userId e content: $content");

    // Inserisci il post, associando l’utente dal token
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, content, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $content]);

    $pdo->commit();

    error_log('Post inserito con successo');
    echo json_encode(['success' => 'Post added successfully']);
} catch (Exception $e) {
    $pdo->rollBack();
    error_log('Errore durante l\'inserimento del post: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}
?>
