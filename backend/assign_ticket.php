<?php
header('Content-Type: application/json');
require_once __DIR__ . '/connection.php';

/* ---------- 1. SEARCH / LOAD ALL UNASSIGNED ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'search') {

    // ----  THIS LINE IS THE FIX  ----
    $query = trim($_GET['query'] ?? '');   // <-- if query not sent → empty string

    if ($query === '') {
        $stmt = $pdo->prepare("
            SELECT id, regno, fullname, category, title, description, priority
            FROM student_ticket
            WHERE assigned_to IS NULL OR assigned_to = 0
            ORDER BY created_at DESC
        ");
        $stmt->execute();
    } else {
        $like = '%' . $query . '%';
        $stmt = $pdo->prepare("
            SELECT id, regno, fullname, category, title, description, priority
            FROM student_ticket
            WHERE (regno   LIKE ?
                OR fullname LIKE ?
                OR category LIKE ?
                OR title    LIKE ?)
              AND (assigned_to IS NULL OR assigned_to = 0)
            ORDER BY created_at DESC
        ");
        $stmt->execute([$like, $like, $like, $like]);
    }

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* ---------- 2. GET STAFF LIST ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'get_staff') {
    $stmt = $pdo->query("SELECT id, fullname, role FROM staff ORDER BY fullname");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;
}

/* ---------- 3. ASSIGN TICKET ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['ticketId'], $data['ticketNumber'], $data['staffId'])) {
        echo json_encode(['status'=>'error','message'=>'Missing fields.']);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE student_ticket
        SET ticket_number = ?, assigned_to = ?, assigned_at = NOW(), status = 'assigned'
        WHERE id = ?
    ");
    $ok = $stmt->execute([$data['ticketNumber'], $data['staffId'], $data['ticketId']]);

    echo json_encode([
        'status'  => $ok ? 'success' : 'error',
        'message' => $ok ? 'Ticket assigned!' : 'Assign failed.'
    ]);
    exit;
}
?>
