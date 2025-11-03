<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_regno'])) {
    header('Content-Type: application/json');

    $search_query = trim($_POST['search_regno'] ?? '');

    if (empty($search_query)) {
        echo json_encode(['success' => false, 'message' => 'Please enter a registration number']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, fullname, email, regno, phone, faculty, department, course 
                               FROM student 
                               WHERE regno LIKE ?");
        $stmt->execute(['%' . $search_query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $results]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Search failed: ' . $e->getMessage()]);
    }
    exit;
}
