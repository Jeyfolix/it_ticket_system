<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$regno = trim($input['regno'] ?? '');
$code = trim($input['code'] ?? '');

// ✅ Step 1: Validate input
if (empty($regno) || empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Registration number and code are required.']);
    exit;
}

// ✅ Step 2: Fetch stored reset code and expiry from student table
$stmt = $pdo->prepare("SELECT reset_code, reset_expires FROM student WHERE regno = ?");
$stmt->execute([$regno]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ✅ Step 3: Validate existence
if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Student not found.']);
    exit;
}

// ✅ Step 4: Check code validity
if ($row['reset_code'] !== $code) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code.']);
    exit;
}

// ✅ Step 5: Check expiry time
if (strtotime($row['reset_expires']) < time()) {
    echo json_encode(['success' => false, 'message' => 'Verification code has expired.']);
    exit;
}

// ✅ Step 6: Return success
echo json_encode(['success' => true, 'message' => 'Code verified successfully.']);
?>
