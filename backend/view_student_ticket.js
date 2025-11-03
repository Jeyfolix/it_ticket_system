document.addEventListener("DOMContentLoaded", () => {
  fetch(`../backend/view_student_ticket.php?regno=${regno}&status=${statusFilter}`)
    .then(res => res.json())
    .then(data => {
      const tableBody = document.getElementById("ticketTableBody");
      tableBody.innerHTML = "";

      if (data.status === "success" && data.tickets.length > 0) {
        data.tickets.forEach((ticket, index) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${index + 1}</td>
            <td>${ticket.category}</td>
            <td>${ticket.title}</td>
            <td>${ticket.description}</td>
            <td>${ticket.priority}</td>
            <td>${ticket.status}</td>
            <td>${ticket.attachment ? `<a href="../uploads/${ticket.attachment}" target="_blank">View</a>` : 'N/A'}</td>
            <td>${ticket.created_at}</td>
          `;
          tableBody.appendChild(row);
        });
      } else {
        tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">No ${statusFilter} tickets found.</td></tr>`;
      }
    })
    .catch(err => {
      console.error(err);
      alert("Error loading tickets.");
    });
});
