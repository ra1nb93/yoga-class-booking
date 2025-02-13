<?php
// register.php
require '../../db.php';
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Se la richiesta è di tipo OPTIONS (usata dai browser per verificare CORS), termina qui.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($data['name']);
    $email = trim($data['email']);
    $password = trim($data['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode(['error' => 'Password must be at least 6 characters']);
        exit;
    }

    try {
        // Controlla se l'email esiste già
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            echo json_encode(['error' => 'Email already registered']);
            exit;
        }

        // Registra il nuovo utente
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hashedPassword]);

        echo json_encode(['success' => 'User registered successfully']);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}