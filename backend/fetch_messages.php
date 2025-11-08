<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/session_start.php';

$student_id = $_SESSION['student_id'] ?? null;
$contact_id = $_GET['contact_id'] ?? null;
$role = $_GET['role'] ?? null;

if (!$student_id || !$contact_id || !$role) {
    echo json_encode([]);
    exit;
}

try {
    if ($role === 'staff') {
        $stmt = $pdo->prepare("
            SELECT 
                id,
                student_id,
                staff_id,
                sender,
                message,
                file_path,
                created_at
            FROM messages
            WHERE student_id = :student_id AND staff_id = :staff_id
            ORDER BY created_at ASC
        ");
        $stmt->execute([':student_id' => $student_id, ':staff_id' => $contact_id]);
    } else {
        $stmt = $pdo->prepare("
            SELECT 
                id,
                student_id,
                admin_id,
                sender,
                message,
                file_path,
                created_at
            FROM messages
            WHERE student_id = :student_id AND admin_id = :admin_id
            ORDER BY created_at ASC
        ");
        $stmt->execute([':student_id' => $student_id, ':admin_id' => $contact_id]);
    }

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as &$msg) {
        if (!empty($msg['file_path'])) {
            $msg['file_path'] = $msg['file_path'];
        }
    }

    echo json_encode($messages);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
