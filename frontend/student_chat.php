<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../frontend/login.html");
    exit;
}
$studentName = $_SESSION['student_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Chat | IT Ticket System</title>
  <link rel="stylesheet" href="../styles/staff_chat.css" />
</head>
<body>
  <div class="chat-wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2 id="chatTitle">My Chats – <?php echo htmlspecialchars($studentName); ?></h2>
      <ul id="contactList"></ul>
    </div>

    <!-- Chat Area -->
    <div class="chat-area">
      <div class="chat-header" id="chatHeader">Select a person to chat with</div>
      <div id="chatMessages" class="chat-box"></div>

      <form id="chatForm" enctype="multipart/form-data" class="chat-form">
        <input type="text" id="message" name="message" placeholder="Type a message..." autocomplete="off" />
        <label for="fileInput" class="file-label">📎</label>
        <input type="file" id="fileInput" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />
        <button type="submit">➤</button>
      </form>
    </div>
  </div>

  <script src="../backend/student_chat.js"></script>
</body>
</html>
