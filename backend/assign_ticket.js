// assign_ticket.js
let staffCache = null;

// Load staff once
async function loadStaff() {
  if (staffCache) return staffCache;
  const res = await fetch('../backend/assign_ticket.php?action=get_staff');
  const data = await res.json();
  staffCache = data;
  return data;
}

// Load tickets (query optional)
async function loadTickets(q = '') {
  const tbody = document.querySelector('#ticketTable tbody');
  const msg = document.getElementById('message');
  msg.textContent = '';
  tbody.innerHTML = '<tr><td colspan="9" class="loading">Loading...</td></tr>';

  const url = q === ''
    ? '../backend/assign_ticket.php?action=search'
    : `../backend/assign_ticket.php?action=search&query=${encodeURIComponent(q)}`;

  try {
    const res = await fetch(url);
    const tickets = await res.json();

    tbody.innerHTML = '';
    if (!tickets || tickets.length === 0) {
      tbody.innerHTML = '<tr><td colspan="9">No unassigned tickets found.</td></tr>';
      return;
    }

    const staff = await loadStaff();

    tickets.forEach(t => {
      const row = document.createElement('tr');
      row.innerHTML = `
        <td>${esc(t.regno)}</td>
        <td>${esc(t.fullname)}</td>
        <td>${esc(t.category)}</td>
        <td>${esc(t.title)}</td>
        <td>${esc(t.description).substring(0,80)}...</td>
        <td>${esc(t.priority)}</td>
        <td><input type="text" id="tn-${t.id}" placeholder="Ticket #" style="width:80px"></td>
        <td>
          <select id="staff-${t.id}">
            <option value="">Select Staff</option>
            ${staff.map(s => `<option value="${s.id}">${esc(s.fullname)} (${esc(s.role)})</option>`).join('')}
          </select>
        </td>
        <td><button onclick="assign(${t.id})">Assign</button></td>
      `;
      tbody.appendChild(row);
    });
  } catch (e) {
    tbody.innerHTML = '<tr><td colspan="9">Error loading tickets.</td></tr>';
    console.error(e);
  }
}

// Assign ticket
window.assign = async function(id) {
  const num = document.getElementById(`tn-${id}`).value.trim();
  const staff = document.getElementById(`staff-${id}`).value;
  const msg = document.getElementById('message');

  if (!num || !staff) {
    showMsg('Enter ticket # and select staff.', 'red');
    return;
  }

  const res = await fetch('../backend/assign_ticket.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({action:'assign', ticketId:id, ticketNumber:num, staffId:staff})
  });
  const data = await res.json();

  showMsg(data.message, data.status==='success'?'green':'red');
  if (data.status==='success') {
    document.getElementById('searchInput').value = '';
    loadTickets();
  }
};

function showMsg(txt, col) {
  const m = document.getElementById('message');
  m.textContent = txt;
  m.style.color = col;
  setTimeout(() => m.textContent='', 5000);
}
function esc(t) {
  const d = document.createElement('div');
  d.textContent = t ?? '';
  return d.innerHTML;
}

/* ==== EVENT LISTENERS ==== */
document.addEventListener('DOMContentLoaded', () => {
  loadStaff();
  loadTickets();               // <-- auto-load on open
});

document.getElementById('searchBtn').addEventListener('click', () => {
  const q = document.getElementById('searchInput').value.trim();
  loadTickets(q);
});

let to;
document.getElementById('searchInput').addEventListener('input', function () {
  clearTimeout(to);
  to = setTimeout(() => loadTickets(this.value.trim()), 400);
});
