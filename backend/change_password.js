// ✅ Change Password Page (Student)
document.addEventListener("DOMContentLoaded", async () => {
  const urlParams = new URLSearchParams(window.location.search);
  let studentId = urlParams.get("id");

  // 🟢 If not in URL, get from localStorage (set after login)
  if (!studentId) {
    studentId = localStorage.getItem("student_id");
  }

  const loadingSpinner = document.getElementById("loadingSpinner");
  const formContainer = document.getElementById("formContainer");
  const alertContainer = document.getElementById("alertContainer");
  const changePasswordForm = document.getElementById("changePasswordForm");

  // 🟢 Fetch student data on load
  if (!studentId) {
    showAlert("⚠️ Missing student ID. Please log in again.", "error");
    setTimeout(() => {
      window.location.href = "login.html";
    }, 1500);
    return;
  }

  loadingSpinner.classList.remove("hidden");

  try {
    const response = await fetch(`../backend/change_password.php?id=${studentId}`);
    const data = await response.json();
    loadingSpinner.classList.add("hidden");

    if (data.success && data.data) {
      populateStudentInfo(data.data);
      formContainer.classList.remove("hidden");
    } else {
      showAlert(data.message || "Student not found", "error");
    }
  } catch (error) {
    loadingSpinner.classList.add("hidden");
    console.error("Error loading student:", error);
    showAlert("An error occurred while loading student data", "error");
  }

  // 🟢 Handle form submission
  changePasswordForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const currentPassword = document.getElementById("current_password").value.trim();
    const newPassword = document.getElementById("new_password").value.trim();
    const confirmPassword = document.getElementById("confirm_password").value.trim();

    if (!currentPassword || !newPassword || !confirmPassword) {
      showAlert("All fields are required", "error");
      return;
    }

    if (newPassword !== confirmPassword) {
      showAlert("New passwords do not match", "error");
      return;
    }

    loadingSpinner.classList.remove("hidden");

    try {
      const response = await fetch(`../backend/change_password.php?id=${studentId}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          current_password: currentPassword,
          new_password: newPassword,
        }),
      });

      const result = await response.json();
      loadingSpinner.classList.add("hidden");

      if (result.success) {
        showAlert(result.message || "Password changed successfully!", "success");
        setTimeout(() => {
          window.location.href = "dashboard.html";
        }, 1500);
      } else {
        showAlert(result.message || "Password change failed", "error");
      }
    } catch (error) {
      loadingSpinner.classList.add("hidden");
      console.error("Error changing password:", error);
      showAlert("An error occurred while changing password", "error");
    }
  });

  // 🟢 Display student info
  function populateStudentInfo(student) {
    document.getElementById("studentName").textContent = student.fullname;
    document.getElementById("studentRegno").textContent = student.regno;
  }

  // 🟢 Display alerts
  function showAlert(message, type) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertContainer.innerHTML = "";
    alertContainer.appendChild(alertDiv);
  }
});
