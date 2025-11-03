const message = document.getElementById("message");
const staffnoInput = document.getElementById("staffno");
const codeInput = document.getElementById("code");

// Step 1: Request verification code
document.getElementById("sendCodeBtn").addEventListener("click", async () => {
  const staffno = staffnoInput.value.trim();
  if (!staffno) return showMsg("Enter your staff number.", "red");

  const res = await fetch("../backend/staff_request_code.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ staffno }),
  });

  const data = await res.json();
  showMsg(data.message, data.success ? "green" : "red");

  if (data.success) {
    document.getElementById("step1").style.display = "none";
    document.getElementById("step2").style.display = "block";
  }
});

// Step 2: Verify code
document.getElementById("verifyBtn").addEventListener("click", async () => {
  const staffno = staffnoInput.value.trim();
  const code = codeInput.value.trim();
  if (!code) return showMsg("Enter verification code.", "red");

  const res = await fetch("../backend/staff_verify_code.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ staffno, code }),
  });

  const data = await res.json();
  showMsg(data.message, data.success ? "green" : "red");

  if (data.success) {
    document.getElementById("step2").style.display = "none";
    document.getElementById("resetForm").style.display = "block";
  }
});

// Step 3: Reset password
document.getElementById("resetForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const staffno = staffnoInput.value.trim();
  const code = codeInput.value.trim();
  const newPassword = document.getElementById("newPassword").value.trim();
  const confirmPassword = document.getElementById("confirmPassword").value.trim();

  // ✅ Validate password match
  if (newPassword !== confirmPassword)
    return showMsg("Passwords do not match.", "red");

  // ✅ Validate password strength
  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

  if (!passwordRegex.test(newPassword)) {
    return showMsg(
      "Password must be at least 8 characters long and contain uppercase, lowercase, number, and special character.",
      "red"
    );
  }

  // ✅ Proceed to reset
  const res = await fetch("../backend/staff_reset_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ staffno, code, newPassword }),
  });

  const data = await res.json();
  showMsg(data.message, data.success ? "green" : "red");

  if (data.success) {
    setTimeout(() => (window.location.href = "../frontend/staff_login.html"), 3000);
  }
});

function showMsg(text, color) {
  message.textContent = text;
  message.style.color = color;
}
