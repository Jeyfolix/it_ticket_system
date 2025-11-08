<?php
session_start();
if (!isset($_SESSION['staff_id'])) {
    header("Location: staff_login.html");
    exit;
}
$staffName = $_SESSION['staff_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Staff Chat | IT Ticket System</title>
  <link rel="stylesheet" href="../styles/staff_chat.css" />
</head>
<body>
  <div class="chat-wrapper">
    <div class="sidebar">
      <h2 id="chatTitle">My Chats – <?php echo htmlspecialchars($staffName); ?></h2>
      <ul id="contactList"></ul>
    </div>

    <div class="chat-area">
      <div class="chat-header" id="chatHeader">Select a student to chat with</div>
      <div id="chatMessages" class="chat-box"></div>

      <form id="chatForm" enctype="multipart/form-data" class="chat-form">
        <input type="hidden" name="student_id" id="student_id">
        <input type="text" id="message" name="message" placeholder="Type a message..." autocomplete="off" required />
        <label for="fileInput" class="file-label">📎</label>
        <input type="file" id="fileInput" name="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" />
        <button type="submit" id="sendBtn">➤</button>
      </form>
    </div>
  </div>

  <script src="../backend/staff_chat.js"></script>
</body>
</html>
