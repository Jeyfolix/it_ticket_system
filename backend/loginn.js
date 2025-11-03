document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new URLSearchParams();
    formData.append('username', document.getElementById('username').value.trim());
    formData.append('password', document.getElementById('password').value.trim());

    fetch('../backend/login.php', {
        method: 'POST',
        body: formData
    })
    .then(res => {
        if (!res.ok) throw new Error('Network response was not ok');
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            // Redirect to .php (NOT .html)
            window.location.href = '../frontend/student_dashboard.php';
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Login error:', err);
        alert("Error connecting to server. Please try again.");
    });
});
