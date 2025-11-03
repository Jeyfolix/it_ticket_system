<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $studentId = $data['id'] ?? null;
    $adminPassword = $data['admin_password'] ?? null;

    if (!$studentId || !$adminPassword) {
        echo json_encode(['success' => false, 'message' => 'Student ID and admin password are required']);
        exit;
    }

    try {
        // 🔹 Step 1: Get admin password hash from the database
        // You can adjust this query depending on how you identify the logged-in admin
        $stmt = $pdo->prepare("SELECT password_hash FROM admins WHERE id = 3"); 
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            echo json_encode(['success' => false, 'message' => 'Admin not found']);
            exit;
        }

        // 🔹 Step 2: Verify password
        if (!password_verify($adminPassword, $admin['password_hash'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid admin password. Deletion aborted.']);
            exit;
        }

        // 🔹 Step 3: Delete student if password is correct
        $stmt = $pdo->prepare("DELETE FROM student WHERE id = ?");
        $stmt->execute([$studentId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => '✅ Student deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Student not found']);
        }

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
