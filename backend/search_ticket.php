<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['reg_number'])) {
    $reg_number = trim($_GET['reg_number']);

    try {
        $stmt = $pdo->prepare("SELECT fullname, regno, email, phone, category, title, description, priority, attachment 
                               FROM student_ticket 
                               WHERE regno = :regno");
        $stmt->bindParam(':regno', $reg_number, PDO::PARAM_STR);
        $stmt->execute();

        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            echo json_encode([
                "status" => "success",
                "data" => $ticket
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "No record found for Reg Number: " . htmlspecialchars($reg_number)
            ]);
        }

    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request or missing registration number."
    ]);
}
?>
