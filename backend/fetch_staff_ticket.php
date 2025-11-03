<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$staff_id = $_GET['staff_id'] ?? '';

if (empty($staff_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing staff ID.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT * FROM student_ticket 
        WHERE assigned_to = ? 
        AND status IN ('assigned', 'pending', 'solved')
        ORDER BY created_at DESC
    ");
    $stmt->execute([$staff_id]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count status totals
    $assigned = count(array_filter($tickets, fn($t) => $t['status'] === 'assigned'));
    $pending  = count(array_filter($tickets, fn($t) => $t['status'] === 'pending'));
    $solved   = count(array_filter($tickets, fn($t) => $t['status'] === 'solved'));

    echo json_encode([
        'status' => 'success',
        'tickets' => $tickets,
        'stats' => [
            'assigned' => $assigned,
            'pending' => $pending,
            'solved' => $solved
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB error: ' . $e->getMessage()]);
}
?>
