<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard</title>
  <link rel="stylesheet" href="../styles/staff_dashboard.css">

  <style>
    .nav-buttons {
      display: flex;
      gap: 10px;
      margin-left: 15px;
    }
    .nav-buttons button {
      background-color: #28a745;
      color: #fff;
      border: none;
      padding: 6px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }
    .nav-buttons button:hover {
      background-color: #218838;
    }
    .stats {
      display: flex;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <header>
      <h2>Staff Dashboard</h2>
      <div class="stats">
        <div class="stat-box">Assigned: <span id="assignedCount">0</span></div>
        <div class="stat-box">Solved: <span id="solvedCount">0</span></div>
        <div class="stat-box">Pending: <span id="pendingCount">0</span></div>

        <div class="nav-buttons">
          <button onclick="window.location.href='staff_chat.php'">Message</button>
          <button onclick="window.location.href='staff_dashboard.php'">Dashboard</button>
        </div>
      </div>
    </header>

    <section class="search-section">
      <input type="text" id="searchInput" placeholder="Search ticket by title, regno, or category...">
    </section>

    <section class="ticket-section">
      <table id="ticketTable">
        <thead>
          <tr>
            <th>Reg No</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Category</th>
            <th>Title</th>
            <th>Description</th>
            <th>Attachment</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="ticketBody"></tbody>
      </table>
    </section>

    <section id="chatSection" class="chat-section hidden">
      <h3>Chat with Student</h3>
      <div id="chatBox" class="chat-box"></div>
      <textarea id="chatMessage" placeholder="Type message..."></textarea>
      <button id="sendChat">Send</button>
    </section>
  </div>

  <script src="../backend/staff_dashboard.js"></script>
</body>
</html>
