<?php
// backend/admin_dashboard.php
header('Content-Type: application/json');

// Show errors during development (disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);
//atsk_8d70b523fb257397a735f6805001376523c8b849c8f3397df7643e622e03ec7392569d49
// Include PDO connection
require_once __DIR__ . '/connection.php';

try {
    // Ensure $pdo is available
    if (!isset($pdo)) {
        echo json_encode(["status" => "error", "message" => "Database connection not found."]);
        exit;
    }

    // Fetch all tickets
    $stmt = $pdo->prepare("
        SELECT 
            regno, fullname, email, phone, category, title, description, 
            attachment, priority, status, created_at
        FROM student_ticket
        ORDER BY created_at DESC
    ");
    $stmt->execute();

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tickets)) {
        echo json_encode([
            "status" => "error",
            "message" => "No tickets found"
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "tickets" => $tickets
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database query failed: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Unexpected error: " . $e->getMessage()
    ]);
}
?>
