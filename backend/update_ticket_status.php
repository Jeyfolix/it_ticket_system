<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$data = json_decode(file_get_contents('php://input'), true);
$ticketId = $data['ticketId'] ?? '';
$status = $data['status'] ?? '';

if (empty($ticketId) || empty($status)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields.']);
    exit;
}

// Only allow valid transitions
$allowed = ['solved', 'pending'];
if (!in_array($status, $allowed)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid status update.']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE student_ticket SET status = ? WHERE id = ?");
    $stmt->execute([$status, $ticketId]);

    echo json_encode(['status' => 'success', 'message' => 'Ticket marked as ' . $status]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage()]);
}
?>
