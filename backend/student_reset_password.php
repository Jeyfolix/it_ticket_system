<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$input = json_decode(file_get_contents('php://input'), true);
$regno = trim($input['regno'] ?? '');
$newPassword = $input['newPassword'] ?? '';
$code = trim($input['code'] ?? '');

if (!$regno || !$newPassword || !$code) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

// ✅ Verify code validity
$stmt = $pdo->prepare("SELECT reset_code, reset_expires FROM student WHERE regno=?");
$stmt->execute([$regno]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || $row['reset_code'] !== $code) {
    echo json_encode(['success' => false, 'message' => 'Invalid verification code.']);
    exit;
}

if (strtotime($row['reset_expires']) < time()) {
    echo json_encode(['success' => false, 'message' => 'Code expired.']);
    exit;
}

// ✅ Check if student already reset password 3 times in the past 7 days
$check = $pdo->prepare("
    SELECT COUNT(*) FROM password_reset_log
    WHERE regno = ? AND reset_time >= (NOW() - INTERVAL 7 DAY)
");
$check->execute([$regno]);
$resetCount = $check->fetchColumn();

if ($resetCount >= 3) {
    echo json_encode([
        'success' => false,
        'message' => 'You can only reset your password 3 times per week. Try again later.'
    ]);
    exit;
}

// ✅ Hash and update password
$hashed = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare("UPDATE student SET password_hash=?, reset_code=NULL, reset_expires=NULL WHERE regno=?");
$update->execute([$hashed, $regno]);

// ✅ Log this reset action
$log = $pdo->prepare("INSERT INTO password_reset_log (regno) VALUES (?)");
$log->execute([$regno]);

echo json_encode(['success' => true, 'message' => 'Password reset successful.']);
?>
