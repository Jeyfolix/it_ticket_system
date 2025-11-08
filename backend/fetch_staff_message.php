<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

session_start();
if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['error' => 'Unauthorized access – staff not logged in']);
    exit;
}

$staff_id = $_SESSION['staff_id'];
$student_id = $_GET['contact_id'] ?? null;

if (!$student_id) {
    echo json_encode(['error' => 'Missing student ID']);
    exit;
}

try {
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
        WHERE (student_id = :student_id AND staff_id = :staff_id)
           OR (student_id = :student_id AND staff_id = :staff_id)
        ORDER BY created_at ASC
    ");
    $stmt->execute([
        ':student_id' => $student_id,
        ':staff_id' => $staff_id
    ]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ensure relative paths for download
    foreach ($messages as &$msg) {
        if (!empty($msg['file_path'])) {
            $msg['file_path'] = $msg['file_path']; // already relative
        }
    }

    echo json_encode($messages);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
