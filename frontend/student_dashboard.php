<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <link rel="stylesheet" href="../styles/student_dash.css">
</head>
<body>
  <?php
  session_start();
  if (!isset($_SESSION['student_id'])) {
      header("Location: ../frontend/login.html");
      exit;
  }
  $name  = htmlspecialchars($_SESSION['student_name'] ?? 'Student');
  $regno = htmlspecialchars($_SESSION['regno'] ?? '');
  $email = htmlspecialchars($_SESSION['email'] ?? '');
  ?>

  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <div class="logo">Student Portal</div>
    </div>
    <div class="header-right">
      <div class="user-info">
        <div class="avatar">CU</div>
        <span id="studentName"><?php echo $name; ?></span>
      </div>
      <a href="../backend/logout.php" class="nav-item">Logout</a>
    </div>
  </header>

  <div class="main-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-section">
        <div class="sidebar-title">Navigation</div>
        <div class="sidebar-item active">Dashboard</div>
        <div class="sidebar-item">Open Tickets</div>
        <div class="sidebar-item">Request History</div>
        <div class="sidebar-item">Ticket Details</div>
        <div class="sidebar-item">Self-Help</div>
        <div class="sidebar-item">Notifications</div>
        <div class="sidebar-item">Quick Actions</div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="content">
      <section class="card welcome-card">
        <h2>WHATS UP NOW <span id="studentWelcomeName"><?php echo $name; ?></span></h2>
      </section>

      <!-- Ticket Creation Panel -->
      <section class="card ticket-form">
        <h3>Submit Your Issue</h3>
        <form id="ticketForm" method="POST" action="../backend/ticket.php" enctype="multipart/form-data">

          <!-- AUTO-FILLED FIELDS (using data-value) -->
          <label>Full Name</label>
          <input 
              type="text" 
              name="fullname" 
              value="<?php echo $name; ?>" 
              placeholder="Your full name" 
              required 
              readonly 
              style="background-color:#f0f0f0;"
          />

          <label>Registration Number</label>
          <input 
              type="text" 
              name="regno" 
              value="<?php echo $regno; ?>" 
              placeholder="Your registration number" 
              required 
              readonly 
              style="background-color:#f0f0f0;"
          />

          <label>Email Address</label>
          <input 
              type="email" 
              name="email" 
              value="<?php echo $email; ?>" 
              placeholder="Your email address" 
              required 
          />

          <label>Phone Number</label>
          <input type="tel" name="phone" placeholder="Enter your phone number" required />

          <!-- Ticket Information -->
          <label>Category / Service</label>
          <select name="category" required>
            <option value="">--Select--</option>
            <option>password reset</option>
            <option>LMS</option>
            <option>course registration</option>
            <option>missing marks</option>
            <option>Transcript</option>
            <option>Access to learning material</option>
            <option>Gate Pass Picking</option>
            <option>Fee Statement request</option>
            <option>Scholarship</option>
            <option>Administration</option>
            <option>Accommodation</option>
          </select>

          <label>Title</label>
          <input type="text" name="title" placeholder="Enter ticket title" required />

          <label>Description</label>
          <textarea name="description" placeholder="Describe your issue" required></textarea>

          <label>Attachment</label>
          <input type="file" name="attachment" />

          <label>Priority</label>
          <select name="priority">
            <option>Low</option>
            <option selected>Medium</option>
            <option>High</option>
          </select>

          <button type="submit">Submit Ticket</button>
        </form>
      </section>

      <!-- Notifications -->
      <section class="card">
        <h3>Notification Settings</h3>
        <label><input type="checkbox"> Email Alerts</label>
        <label><input type="checkbox"> SMS Alerts</label>
      </section>

      <!-- Quick Actions -->
     <section class="card quick-actions">
  <h3>Quick Actions</h3>
  <button onclick="window.location.href='view_student_ticket.php?tab=assigned'">
    Assigned Ticket
  </button>
  <button onclick="window.open('view_student_ticket.php?tab=pending', '_self')">
    View Pending Tickets
  </button>
  <button onclick="window.open('view_student_ticket.php?tab=solved', '_self')">
    View Resolved Tickets
  </button>
</section>

    </main>
  </div>

  <script src="../backend/ticket.js"></script>
</body>
</html>
