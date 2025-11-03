document.addEventListener('DOMContentLoaded', () => {
    const staffId = localStorage.getItem('staff_id');
    const staffName = localStorage.getItem('fullname');

    if (!staffId) {
        alert("Please log in first!");
        window.location.href = '../frontend/staff_login.html';
        return;
    }

    document.querySelector('header h2').innerText = `Welcome, ${staffName}`;

    loadTickets(staffId);

    document.getElementById('searchInput').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#ticketBody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
});

function loadTickets(staffId) {
    fetch(`../backend/fetch_staff_ticket.php?staff_id=${staffId}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                renderTickets(data.tickets);
                updateStats(data.stats);
            } else {
                document.getElementById('ticketBody').innerHTML =
                    `<tr><td colspan="12">${data.message}</td></tr>`;
            }
        })
        .catch(err => console.error(err));
}

function renderTickets(tickets) {
    const tbody = document.getElementById('ticketBody');
    tbody.innerHTML = tickets.map(ticket => `
        <tr>
            <td>${ticket.regno}</td>
            <td>${ticket.fullname}</td>
            <td>${ticket.email}</td>
            <td>${ticket.phone}</td>
            <td>${ticket.category}</td>
            <td>${ticket.title}</td>
            <td>${ticket.description}</td>
            <td>${ticket.attachment ?? ''}</td>
            <td>${ticket.priority}</td>
            <td>${ticket.status}</td>
            <td>${ticket.created_at}</td>
            <td>
                ${ticket.status !== 'solved' ? `<button onclick="markSolved(${ticket.id})">Solved</button>` : ''}
                ${ticket.status !== 'pending' ? `<button onclick="markPending(${ticket.id})">Pending</button>` : ''}
            </td>
        </tr>
    `).join('');
}

function updateStats(stats) {
    document.getElementById('assignedCount').innerText = stats.assigned;
    document.getElementById('pendingCount').innerText = stats.pending;
    document.getElementById('solvedCount').innerText = stats.solved;
}

function markSolved(ticketId) {
    updateStatus(ticketId, 'solved');
}

function markPending(ticketId) {
    updateStatus(ticketId, 'pending');
}

function updateStatus(ticketId, status) {
    fetch('../backend/update_ticket_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ticketId, status })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        const staffId = localStorage.getItem('staff_id');
        loadTickets(staffId); // reload list & stats
    })
    .catch(err => console.error(err));
}
