<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$type = $_GET['type'] ?? '';
$regno = $_GET['regno'] ?? '';

if (empty($type) || empty($regno)) {
    echo json_encode(["status" => "error", "message" => "Missing parameters"]);
    exit;
}

try {
    if ($type === 'student') {
        $stmt = $pdo->prepare("
            SELECT fullname, email, regno, phone, faculty, department, course, password, created_at 
            FROM student 
            WHERE regno = ?
        ");
    } elseif ($type === 'ticket') {
        $stmt = $pdo->prepare("
            SELECT regno, fullname, email, phone, category, title, description, attachment, priority, status, created_at 
            FROM student_ticket 
            WHERE regno = ?
        ");
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid search type"]);
        exit;
    }

    $stmt->execute([$regno]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results && count($results) > 0) {
        echo json_encode(["status" => "success", "records" => $results]);
    } else {
        echo json_encode(["status" => "error", "message" => "No records found"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
