<?php
session_start();

// Student session variables
$student_id = $_SESSION['student_id'] ?? null;
$student_name = $_SESSION['student_name'] ?? null;

// Staff session variables
$staff_id = $_SESSION['staff_id'] ?? null;
$staff_name = $_SESSION['staff_name'] ?? null;

// Admin session variables (optional)
$admin_id = $_SESSION['admin_id'] ?? null;
$admin_name = $_SESSION['admin_name'] ?? null;
?>
