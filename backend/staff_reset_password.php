<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$staffno = trim($input['staffno'] ?? '');
$newPassword = $input['newPassword'] ?? '';
$code = trim($input['code'] ?? '');

if (!$staffno || !$newPassword || !$code) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$stmt = $pdo->prepare("SELECT reset_code, reset_expires FROM staff WHERE staffno=?");
$stmt->execute([$staffno]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || $row['reset_code'] !== $code) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code.']);
    exit;
}

if (strtotime($row['reset_expires']) < time()) {
    echo json_encode(['success' => false, 'message' => 'Code expired.']);
    exit;
}

$hashed = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE staff SET password=?, reset_code=NULL, reset_expires=NULL WHERE staffno=?");
$update->execute([$hashed, $staffno]);

echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
