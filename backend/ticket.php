<?php
ob_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

session_start();

// Must be logged in
if (!isset($_SESSION['student_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

require_once __DIR__ . '/connection.php';

// Student data from session (trusted source)
$student_id = $_SESSION['student_id'];
$fullname   = $_SESSION['student_name'];
$regno      = $_SESSION['regno'];

// Form data (user can update these)
$email        = trim($_POST['email'] ?? '');
$phone        = trim($_POST['phone'] ?? '');
$category     = trim($_POST['category'] ?? '');
$title        = trim($_POST['title'] ?? '');
$description  = trim($_POST['description'] ?? '');
$priority     = trim($_POST['priority'] ?? '');
$attachmentPath = "";

// Validate required fields
if (
    empty($email) || empty($phone) || empty($category) ||
    empty($title) || empty($description) || empty($priority)
) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

// Handle file upload
if (!empty($_FILES['attachment']['name'])) {
    $targetDir = __DIR__ . '/../uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $fileName = time() . "_" . basename($_FILES["attachment"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
        $attachmentPath = 'uploads/' . $fileName;
    }
}

try {
    // Insert ticket with trusted student_id
    $stmt = $pdo->prepare("
        INSERT INTO student_ticket 
        (id, fullname, email, regno, phone, category, title, description, priority, attachment)
        VALUES 
        (:id, :fullname, :email, :regno, :phone, :category, :title, :description, :priority, :attachment)
    ");

    $success = $stmt->execute([
        ':id'           => $id,
        ':fullname'     => $fullname,
        ':email'        => $email,
        ':regno'        => $regno,
        ':phone'        => $phone,
        ':category'     => $category,
        ':title'        => $title,
        ':description'  => $description,
        ':priority'     => $priority,
        ':attachment'   => $attachmentPath
    ]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Ticket submitted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit ticket.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
}

ob_end_flush();
?>
