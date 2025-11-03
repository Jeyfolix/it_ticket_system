document.getElementById('staffForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new URLSearchParams();
    formData.append('staffno', document.getElementById('staffno').value.trim());
    formData.append('fullname', document.getElementById('fullname').value.trim());
    formData.append('email', document.getElementById('email').value.trim());
    formData.append('department', document.getElementById('department').value.trim());
    formData.append('specialization', document.getElementById('specialization').value.trim());
    formData.append('role', document.getElementById('role').value.trim());
    formData.append('phone', document.getElementById('phone').value.trim());
    formData.append('password', document.getElementById('password').value);
    formData.append('confirm_password', document.getElementById('confirm_password').value);

    const msgDiv = document.getElementById('message');
    msgDiv.innerHTML = '';
    msgDiv.style.display = 'block';

    fetch('../backend/register_staff.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error('Network error');
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // SUCCESS: Show message + auto-redirect
            msgDiv.innerHTML = `<div class="msg success">${data.message}</div>`;
            document.getElementById('staffForm').reset();

            // Auto redirect after 1.5 seconds
            setTimeout(() => {
                window.location.href = '../frontend/staff_login.html'; // Adjust path if needed
            }, 1500);

        } else {
            // ERROR: Show all messages
            let errorMsg = Array.isArray(data.message) 
                ? data.message.join('<br>') 
                : data.message;

            msgDiv.innerHTML = `<div class="msg error">${errorMsg}</div>`;
        }
    })
    .catch(err => {
        console.error('Registration error:', err);
        msgDiv.innerHTML = '<div class="msg error">Error connecting to server. Please try again.</div>';
    });
});
