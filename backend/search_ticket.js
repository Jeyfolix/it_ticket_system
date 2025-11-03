// search.js

document.addEventListener("DOMContentLoaded", () => {
  const searchBtn = document.getElementById("searchBtn");
  const searchInput = document.getElementById("searchInput");
  const resultDiv = document.getElementById("searchResult");

  searchBtn.addEventListener("click", async () => {
    const regNumber = searchInput.value.trim();

    if (regNumber === "") {
      resultDiv.innerHTML = "<p style='color:red;'>Please enter a registration number.</p>";
      return;
    }

    try {
      const response = await fetch(`backend/search_ticket.php?reg_number=${encodeURIComponent(regNumber)}`);
      const data = await response.json();

      if (data.status === "success") {
        const t = data.data;
        resultDiv.innerHTML = `
          <div class="ticket-card">
            <h3>🎓 Student Ticket Details</h3>
            <p><strong>Full Name:</strong> ${t.fullname}</p>
            <p><strong>Reg Number:</strong> ${t.regno}</p>
            <p><strong>Email:</strong> ${t.email}</p>
            <p><strong>Phone:</strong> ${t.phone}</p>
            <p><strong>Service / Category:</strong> ${t.category}</p>
            <p><strong>Title:</strong> ${t.title}</p>
            <p><strong>Description:</strong> ${t.description}</p>
            <p><strong>Priority:</strong> ${t.priority}</p>
            <p><strong>Attachment:</strong> ${t.attachment ? t.attachment : "No attachment"}</p>
          </div>
        `;
      } else {
        resultDiv.innerHTML = `<p style='color:orange;'>${data.message}</p>`;
      }
    } catch (error) {
      resultDiv.innerHTML = `<p style='color:red;'>Error: ${error.message}</p>`;
    }
  });
});
