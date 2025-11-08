<?php
require 'connection.php';
session_start();

// Ensure staff is logged in
if (!isset($_SESSION['staff_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access – staff not logged in']);
    exit;
}

$staff_id = $_SESSION['staff_id'];

// Collect input data
$contact_id = $_POST['contact_id'] ?? null; // this is the student_id
$role = $_POST['role'] ?? null;
$message = trim($_POST['message'] ?? '');
$file_path = null;

// Validate inputs
if (!$contact_id || !$role) {
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
    if ($role === 'student') {
        $stmt = $pdo->prepare("
            INSERT INTO messages (student_id, staff_id, sender, message, file_path, created_at)
            VALUES (?, ?, 'staff', ?, ?, NOW())
        ");
        $stmt->execute([$contact_id, $staff_id, $message, $file_path]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid recipient role']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Message sent successfully', 'file_path' => $file_path]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
