// backend/admin_dashboard.js
document.addEventListener("DOMContentLoaded", () => {
  fetchTickets();

  // Search button
  const searchBtn = document.getElementById("searchBtn");
  if (searchBtn) searchBtn.addEventListener("click", searchTicket);
});

async function fetchTickets() {
  const tableBody = document.getElementById("ticketTableBody");
  if (!tableBody) return;
  tableBody.innerHTML = "<tr><td colspan='11' style='text-align:center'>Loading tickets...</td></tr>";

  try {
    const res = await fetch("../backend/admin_dashboard.php");
    const data = await res.json();

    // If endpoint returned an error message
    if (!data || data.status !== "success") {
      const msg = (data && data.message) ? data.message : "No tickets available.";
      tableBody.innerHTML = `<tr><td colspan="11" style="text-align:center">${msg}</td></tr>`;
      return;
    }

    const tickets = data.tickets;
    if (!Array.isArray(tickets) || tickets.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="11" style="text-align:center">No tickets available.</td></tr>`;
      return;
    }

    // Render rows
    tableBody.innerHTML = "";
    tickets.forEach(ticket => {
      const row = document.createElement("tr");

      row.innerHTML = `
        <td>${escapeHtml(ticket.regno)}</td>
        <td>${escapeHtml(ticket.fullname)}</td>
        <td>${escapeHtml(ticket.email)}</td>
        <td>${escapeHtml(ticket.phone)}</td>
        <td>${escapeHtml(ticket.category)}</td>
        <td>${escapeHtml(ticket.title)}</td>
        <td>${escapeHtml(ticket.description)}</td>
        <td>${ticket.attachment ? `<a href="../uploads/${encodeURIComponent(ticket.attachment)}" target="_blank">View</a>` : 'N/A'}</td>
        <td>${escapeHtml(ticket.priority)}</td>
        <td>${escapeHtml(ticket.status)}</td>
        <td>${escapeHtml(ticket.created_at)}</td>
      `;

      // Click row -> show details in the textarea
      row.addEventListener("click", () => {
        const details = `
Reg Number: ${ticket.regno}
Name: ${ticket.fullname}
Email: ${ticket.email}
Phone: ${ticket.phone}
Service: ${ticket.category}
Title: ${ticket.title}
Description:
${ticket.description}

Priority: ${ticket.priority}
Status: ${ticket.status}
Created At: ${ticket.created_at}
Attachment: ${ticket.attachment ? ticket.attachment : 'None'}
        `.trim();

        const ta = document.getElementById("ticketDetailsArea");
        if (ta) ta.value = details;
      });

      tableBody.appendChild(row);
    });

  } catch (err) {
    console.error("Error fetching tickets:", err);
    tableBody.innerHTML = `<tr><td colspan="11" style="text-align:center">Error loading tickets.</td></tr>`;
  }
}

// simple search (by regno) — hides rows that don't match
function searchTicket() {
  const query = document.getElementById("searchInput").value.trim().toLowerCase();
  const rows = document.querySelectorAll("#ticketTableBody tr");
  rows.forEach(row => {
    const regnoCell = row.cells[0];
    const regno = regnoCell ? regnoCell.textContent.toLowerCase() : "";
    row.style.display = regno.includes(query) ? "" : "none";
  });
}

// small HTML-escape helper
function escapeHtml(str = "") {
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}
