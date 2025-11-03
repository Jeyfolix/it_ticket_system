document.getElementById('staffLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new URLSearchParams();
    formData.append('staffno', document.getElementById('staffno').value.trim());
    formData.append('password', document.getElementById('password').value.trim());

    const msgDiv = document.getElementById('message');
    msgDiv.innerHTML = '';

    fetch('../backend/staff_login.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // ✅ Store staff details locally
            localStorage.setItem('staff_id', data.staff_id);
            localStorage.setItem('staffno', data.staffno);
            localStorage.setItem('fullname', data.fullname);

            msgDiv.innerHTML = `<div class="msg success">${data.message}</div>`;
            
            // Wait for 2 seconds before redirecting
            setTimeout(() => {
                window.location.href = '../frontend/staff_dashboard.html';
            }, 2000);
        } else {
            let message = Array.isArray(data.message) ? data.message.join('<br>') : data.message;
            msgDiv.innerHTML = `<div class="msg error">${message}</div>`;
        }
    })
    .catch(err => {
        msgDiv.innerHTML = '<div class="msg error">Error connecting to server.</div>';
        console.error(err);
    });
});
