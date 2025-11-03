<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Ticket - Admin Panel</title>
  <link rel="stylesheet" href="../styles/assign_ticket.css">
  <style>
    body {font-family:Arial,sans-serif;background:#f5f6fa;}
    .container{width:90%;margin:30px auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 0 8px rgba(0,0,0,.1);}
    table{width:100%;border-collapse:collapse;margin-top:15px;}
    th,td{border:1px solid #ddd;padding:10px;text-align:left;}
    th{background:#0077cc;color:#fff;}
    input,select,button{padding:8px;margin:5px 0;border-radius:5px;border:1px solid #ccc;}
    button{background:#0077cc;color:#fff;cursor:pointer;border:none;}
    button:hover{background:#005fa3;}
    #message{margin-top:10px;font-weight:bold;}
    .loading{color:#0077cc;font-style:italic;}
    #searchInput{width:300px;}
  </style>
</head>
<body>
  <div class="container">
    <h2>Assign Ticket to Staff</h2>

    <div style="margin-bottom:15px;">
      <input type="text" id="searchInput" placeholder="Search by RegNo, Name, Category, Title..." autocomplete="off">
      <button id="searchBtn">Search</button>
    </div>

    <div id="message"></div>

    <table id="ticketTable">
      <thead>
        <tr>
          <th>RegNo</th>
          <th>Name</th>
          <th>Category</th>
          <th>Title</th>
          <th>Description</th>
          <th>Priority</th>
          <th>Ticket #</th>
          <th>Select Staff</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr><td colspan="9" class="loading">Loading tickets...</td></tr>
      </tbody>
    </table>
  </div>

    <script src="../backend/assign_ticket.js"></script>
</body>
</html>
