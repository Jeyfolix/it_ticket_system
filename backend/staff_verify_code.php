<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$staffno = trim($input['staffno'] ?? '');
$code = trim($input['code'] ?? '');

$stmt = $pdo->prepare("SELECT reset_code, reset_expires FROM staff WHERE staffno=?");
$stmt->execute([$staffno]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo json_encode(['success' => false, 'message' => 'Staff not found.']);
    exit;
}

if ($row['reset_code'] !== $code) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code.']);
    exit;
}

if (strtotime($row['reset_expires']) < time()) {
    echo json_encode(['success' => false, 'message' => 'Code has expired.']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'Code verified successfully.']);
