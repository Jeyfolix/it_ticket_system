<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Fetch student data
    $student_id = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT id, fullname, email, regno FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$student) {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
            exit;
        }
        
        echo json_encode(['success' => true, 'data' => $student]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error fetching student: ' . $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reset password
    $data = json_decode(file_get_contents('php://input'), true);
    
    $student_id = (int)($data['id'] ?? 0);
    $new_password = trim($data['new_password'] ?? '');
    $confirm_password = trim($data['confirm_password'] ?? '');
    
    if (empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all password fields']);
        exit;
    }
    
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        exit;
    }
    
    if (strlen($new_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
        exit;
    }
    
    try {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $student_id]);
        
        echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Password reset failed: ' . $e->getMessage()]);
    }
    exit;
}
?>
