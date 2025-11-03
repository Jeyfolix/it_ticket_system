<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffno = trim($_POST['staffno'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    // 🔹 Check all required fields
    if (!$staffno || !$fullname || !$email || !$department || !$specialization || !$role || !$phone || !$password || !$confirm_password) {
        $errors[] = "All fields are required.";
    }

    // 🔹 Email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format. Use a valid email address (e.g., example@gmail.com).";
    }

    // 🔹 Kenyan phone number format validation
    if (!preg_match('/^(?:\+254|0)?7\d{8}$/', $phone)) {
        $errors[] = "Invalid phone number. Use +2547XXXXXXXX or 07XXXXXXXX.";
    }

    // 🔹 Password strength validation
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        $errors[] = "Password must contain at least 8 characters, including uppercase, lowercase, number, and special character.";
    }

    // 🔹 Confirm password match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // 🔹 If any validation fails
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => $errors]);
        exit;
    }

    try {
        // 🔹 Check if staffno or email already exists
        $check = $pdo->prepare("SELECT * FROM staff WHERE staffno = ? OR email = ?");
        $check->execute([$staffno, $email]);
        if ($check->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Staff number or email already exists.']);
            exit;
        }

        // 🔹 Hash password securely
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 🔹 Insert into database
        $stmt = $pdo->prepare("INSERT INTO staff (staffno, fullname, email, department, specialization, role, phone, password)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$staffno, $fullname, $email, $department, $specialization, $role, $phone, $hashedPassword]);

        echo json_encode(['status' => 'success', 'message' => '✅ Staff registered successfully!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
