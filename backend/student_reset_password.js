const message = document.getElementById("message");
const regnoInput = document.getElementById("regno");
const codeInput = document.getElementById("code");

// Step 1: Request verification code
document.getElementById("sendCodeBtn").addEventListener("click", async () => {
  const regno = regnoInput.value.trim();
  if (!regno) return showMsg("Enter your registration number.", "red");

  const res = await fetch("../backend/student_request_code.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ regno }),
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
  const regno = regnoInput.value.trim();
  const code = codeInput.value.trim();
  if (!code) return showMsg("Enter verification code.", "red");

  const res = await fetch("../backend/student_verify_code.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ regno, code }),
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

  const regno = regnoInput.value.trim();
  const code = codeInput.value.trim();
  const newPassword = document.getElementById("newPassword").value.trim();
  const confirmPassword = document.getElementById("confirmPassword").value.trim();

  // ✅ Validate password match
  if (newPassword !== confirmPassword)
    return showMsg("Passwords do not match.", "red");

  // ✅ Validate password strength
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_\-+=<>?{}[\]~]).{8,}$/;
  if (!passwordRegex.test(newPassword)) {
    return showMsg(
      "Password must be at least 8 characters and include uppercase, lowercase, and a special character.",
      "red"
    );
  }

  // ✅ Send request to backend
  const res = await fetch("../backend/student_reset_password.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ regno, code, newPassword }),
  });
  const data = await res.json();
  showMsg(data.message, data.success ? "green" : "red");

  if (data.success) {
    setTimeout(() => (window.location.href = "../frontend/login.html"), 3000);
  }
});

function showMsg(text, color) {
  message.textContent = text;
  message.style.color = color;
}
