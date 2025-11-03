<?php
header("Content-Type: application/json");
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status"=>"error","message"=>"Method not allowed"]);
    exit;
}

require_once __DIR__ . '/connection.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo json_encode(["status"=>"error","message"=>"Both fields are required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, fullname, regno, email, password_hash FROM student WHERE regno = ? LIMIT 1");
    $stmt->execute([$username]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(["status"=>"error","message"=>"Username not found"]);
        exit;
    }

    if (password_verify($password, $student['password_hash'])) {
        // STORE ALL NEEDED DATA IN SESSION
        $_SESSION['student_id']   = $student['id'];
        $_SESSION['student_name'] = $student['fullname'];
        $_SESSION['regno']        = $student['regno'];     // Auto-fill
        $_SESSION['email']        = $student['email'];     // Auto-fill

        echo json_encode(["status"=>"success","message"=>"Login successful"]);
        exit;
    } else {
        echo json_encode(["status"=>"error","message"=>"Incorrect password"]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(["status"=>"error","message"=>"Database error"]);
    exit;
}
?>
