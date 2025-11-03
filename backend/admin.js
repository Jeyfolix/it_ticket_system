document.addEventListener("DOMContentLoaded", () => {
  const searchForm = document.getElementById("searchForm");
  const searchInput = document.getElementById("search_regno");
  const loadingSpinner = document.getElementById("loadingSpinner");
  const resultsSection = document.getElementById("resultsSection");
  const noResults = document.getElementById("noResults");
  const tableBody = document.getElementById("tableBody");
  const resultsCount = document.getElementById("resultsCount");
  const alertContainer = document.getElementById("alertContainer");

  searchForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const searchQuery = searchInput.value.trim();
    if (!searchQuery) {
      showAlert("Please enter a registration number", "error");
      return;
    }

    loadingSpinner.classList.remove("hidden");
    resultsSection.classList.add("hidden");
    noResults.classList.add("hidden");
    alertContainer.innerHTML = "";

    try {
      const formData = new FormData();
      formData.append("search_regno", searchQuery);

      const response = await fetch("../backend/dashboard.php", {
        method: "POST",
        body: formData,
      });

      const data = await response.json();
      loadingSpinner.classList.add("hidden");

      if (data.success) {
        if (data.data.length === 0) {
          noResults.classList.remove("hidden");
        } else {
          displayResults(data.data);
          resultsSection.classList.remove("hidden");
          resultsCount.textContent = `Search Results (${data.data.length} found)`;
        }
      } else {
        showAlert(data.message || "Search failed", "error");
      }
    } catch (error) {
      loadingSpinner.classList.add("hidden");
      console.error("Error:", error);
      showAlert("An error occurred while searching", "error");
    }
  });

  function displayResults(students) {
    tableBody.innerHTML = "";
    students.forEach((student) => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${escapeHtml(student.fullname)}</td>
        <td>${escapeHtml(student.email)}</td>
        <td>${escapeHtml(student.regno)}</td>
        <td>${escapeHtml(student.phone)}</td>
        <td>${escapeHtml(student.faculty)}</td>
        <td>${escapeHtml(student.department)}</td>
        <td>${escapeHtml(student.course)}</td>
        <td class="actions">
          <a href="update-student.html?id=${student.id}" class="btn btn-sm btn-info">Edit</a>
         <a href="reset_password.html?id=${student.id}" class="btn btn-sm btn-warning">Reset Pass</a>
          <button class="btn btn-sm btn-danger" onclick="deleteStudent(${student.id}, '${escapeHtml(student.fullname)}')">Delete</button>
        </td>
      `;
      tableBody.appendChild(row);
    });
  }

  function showAlert(message, type) {
    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    alertContainer.innerHTML = "";
    alertContainer.appendChild(alertDiv);
  }

  function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
  }
});
function deleteStudent(studentId, studentName) {
  // Show modal
  const modal = document.getElementById("passwordModal");
  const studentDisplay = document.getElementById("studentNameDisplay");
  const passwordInput = document.getElementById("adminPasswordInput");

  studentDisplay.textContent = `Confirm deletion of ${studentName}`;
  modal.style.display = "flex";
  passwordInput.value = "";
  passwordInput.focus();

  // Handle confirm
  document.getElementById("confirmDeleteBtn").onclick = function () {
    const adminPassword = passwordInput.value.trim();
    if (!adminPassword) {
      alert("Please enter admin password.");
      return;
    }

    if (confirm(`Are you sure you want to delete ${studentName}? This action cannot be undone.`)) {
      fetch("../backend/delete_student.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id: studentId,
          admin_password: adminPassword
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            alert("✅ Student deleted successfully!");
            location.reload();
          } else {
            alert("❌ Error: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("⚠️ An error occurred while deleting the student.");
        });
    }

    // Hide modal after action
    modal.style.display = "none";
  };

  // Handle cancel
  document.getElementById("cancelDeleteBtn").onclick = function () {
    modal.style.display = "none";
  };
}

