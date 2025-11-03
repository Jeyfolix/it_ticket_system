<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$regno = $_GET['regno'] ?? '';
$status = $_GET['status'] ?? 'pending';
$status = $_GET['status'] ?? 'solved';
$status = $_GET['status'] ?? 'assigned';



if (empty($regno)) {
    echo json_encode(["status" => "error", "message" => "Missing registration number"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT category, title, description, priority, status, attachment, created_at 
                           FROM student_ticket 
                           WHERE regno = :regno AND status = :status 
                           ORDER BY created_at DESC");
    $stmt->execute([':regno' => $regno, ':status' => $status]);
    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "tickets" => $tickets
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
