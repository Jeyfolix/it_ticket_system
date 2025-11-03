<?php
session_start();
require_once '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Fetch student data
    $student_id = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT id, fullname, email, regno, phone, faculty, department, course FROM students WHERE id = ?");
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
    // Update student data
    $data = json_decode(file_get_contents('php://input'), true);
    
    $student_id = (int)($data['id'] ?? 0);
    $fullname = trim($data['fullname'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $faculty = trim($data['faculty'] ?? '');
    $department = trim($data['department'] ?? '');
    $course = trim($data['course'] ?? '');
    
    if (empty($fullname) || empty($email) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("UPDATE students SET fullname = ?, email = ?, phone = ?, faculty = ?, department = ?, course = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $phone, $faculty, $department, $course, $student_id]);
        
        echo json_encode(['success' => true, 'message' => 'Student updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()]);
    }
    exit;
}
?>
