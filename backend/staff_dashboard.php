<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

// 🔹 Get tickets assigned to a specific staff
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getTickets') {
    $staffId = $_GET['staffId'] ?? null;

    if (!$staffId) {
        echo json_encode(["status" => "error", "message" => "Missing staff ID"]);
        exit;
    }

    // Fetch tickets assigned to this staff
    $stmt = $pdo->prepare("
        SELECT id, regno, fullname, email, phone, category, title, description, attachment, priority, status, created_at 
        FROM student_ticket 
        WHERE assigned_to = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$staffId]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count totals by status
    $countStmt = $pdo->prepare("
        SELECT 
            SUM(status = 'assigned') AS assignedCount,
            SUM(status = 'solved') AS solvedCount,
            SUM(status = 'pending') AS pendingCount
        FROM student_ticket WHERE assigned_to = ?
    ");
    $countStmt->execute([$staffId]);
    $counts = $countStmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "tickets" => $tickets,
        "counts" => $counts
    ]);
    exit;
}

// 🔹 Update ticket status (Solved or Pending)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $ticketId = $data['ticketId'] ?? null;
    $newStatus = $data['status'] ?? null;

    if (!$ticketId || !$newStatus) {
        echo json_encode(["status" => "error", "message" => "Missing parameters"]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE student_ticket SET status = ? WHERE id = ?");
    $updated = $stmt->execute([$newStatus, $ticketId]);

    echo json_encode([
        "status" => $updated ? "success" : "error",
        "message" => $updated ? "Ticket marked as $newStatus" : "Failed to update ticket"
    ]);
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
?>
