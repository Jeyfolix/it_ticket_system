<?php
require 'connection.php';
session_start();

// Ensure student is logged in
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access – student not logged in']);
    exit;
}

$student_id = $_SESSION['student_id'];
$contact_id = $_POST['contact_id'] ?? null; // staff/admin id
$role = $_POST['role'] ?? null;
$message = trim($_POST['message'] ?? '');
$file_path = null;

// Validate inputs
if (!$student_id || !$contact_id || !$role) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Handle file upload (if any)
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $targetDir = __DIR__ . "/uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . "_" . basename($_FILES["file"]["name"]);
    $file_path = "uploads/" . $fileName; // relative path for DB

    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $targetDir . $fileName)) {
        echo json_encode(['success' => false, 'message' => 'File upload failed']);
        exit;
    }
}

// Insert message into database
try {
    if ($role === 'staff') {
        $stmt = $pdo->prepare("
            INSERT INTO messages (student_id, staff_id, sender, message, file_path, created_at)
            VALUES (?, ?, 'student', ?, ?, NOW())
        ");
        $stmt->execute([$student_id, $contact_id, $message, $file_path]);
    } elseif ($role === 'admin') {
        $stmt = $pdo->prepare("
            INSERT INTO messages (student_id, admin_id, sender, message, file_path, created_at)
            VALUES (?, ?, 'student', ?, ?, NOW())
        ");
        $stmt->execute([$student_id, $contact_id, $message, $file_path]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid recipient role']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Message sent successfully', 'file_path' => $file_path]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
