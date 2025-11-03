<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffno = $_POST['staffno'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($staffno) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM staff WHERE staffno = ?");
        $stmt->execute([$staffno]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff && password_verify($password, $staff['password'])) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful! Redirecting...',
                'staff_id' => $staff['id'],
                'staffno' => $staff['staffno'],
                'fullname' => $staff['fullname']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid staff number or password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
