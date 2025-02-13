<?php
header("Access-Control-Allow-Origin: https://yoga-class-booking.netlify.app");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require '../../db.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $data['user_id'] ?? null;
    $classId = $data['class_id'] ?? null;

    if (!$userId || !$classId) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request. Missing user_id or class_id.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // Check if user already booked the class
        $stmt = $pdo->prepare("SELECT * FROM bookings WHERE user_id = ? AND class_id = ?");
        $stmt->execute([$userId, $classId]);
        if ($stmt->rowCount() > 0) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['error' => 'You have already booked this class']);
            exit;
        }

        // Check if class has available spots
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM bookings WHERE class_id = ?");
        $stmt->execute([$classId]);
        $bookingCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        $stmt = $pdo->prepare("SELECT max_participants FROM classes WHERE id = ?");
        $stmt->execute([$classId]);
        $maxParticipants = $stmt->fetch(PDO::FETCH_ASSOC)['max_participants'];

        if ($bookingCount >= $maxParticipants) {
            $pdo->rollBack();
            http_response_code(400);
            echo json_encode(['error' => 'Class is fully booked']);
            exit;
        }

        // Insert the booking
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, class_id) VALUES (?, ?)");
        $stmt->execute([$userId, $classId]);

        // Insert a notification
        $message = "You have successfully booked the class: " . $classId;
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$userId, $message]);

        $pdo->commit();

        http_response_code(200);
        echo json_encode(['success' => 'Class booked successfully']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
}
?>
