<?php
session_start();
if (!isset($_SESSION['regno'])) {
    header("Location: login.html");
    exit;
}
$regno = $_SESSION['regno'];
$statusFilter = $_GET['tab'] ?? 'pending'; // Default tab is pending
$statusFilter = $_GET['tab'] ?? 'solved'; // Default tab is pending
$statusFilter = $_GET['tab'] ?? 'assigned'; // Default tab is pending
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Tickets</title>
  <link rel="stylesheet" href="../styles/student_dash.css">
  <style>
    .ticket-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .ticket-table th, .ticket-table td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    .ticket-table th { background-color: #f0f0f0; }
    .back-btn { margin-top: 20px; background: #004080; color: white; padding: 8px 15px; border: none; cursor: pointer; }
    .back-btn:hover { background: #002b5c; }
  </style>
</head>
<body>

  <header class="header">
    <div class="header-left"><div class="logo">My Tickets</div></div>
    <div class="header-right">
      <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['student_name']); ?></strong></span>
      <a href="student_dashboard.php" class="nav-item">Dashboard</a>
    </div>
  </header>

  <main class="content">
    <h2>
      <?php echo ucfirst($statusFilter); ?> Tickets
    </h2>

    <table class="ticket-table" id="ticketTable">
      <thead>
        <tr>
          <th></th>
          <th>Category</th>
          <th>Title</th>
          <th>Description</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Attachment</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody id="ticketTableBody">
        <!-- Fetched tickets will appear here -->
      </tbody>
    </table>

    <button class="back-btn" onclick="window.location.href='student_dashboard.php'">Back to Dashboard</button>
  </main>

  <script src="../backend/view_student_ticket.js"></script>
  <script>
    // Pass PHP variable to JS
    const regno = "<?php echo $regno; ?>";
    const statusFilter = "<?php echo $statusFilter; ?>";
  </script>
</body>
</html>
