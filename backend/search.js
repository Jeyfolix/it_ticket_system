document.getElementById("searchBtn").addEventListener("click", () => {
  const regno = document.getElementById("searchInput").value.trim();
  const type = document.getElementById("searchType").value;

  if (!regno) {
    alert("Please enter a registration number.");
    return;
  }

  fetch(`../backend/search.php?type=${type}&regno=${encodeURIComponent(regno)}`)
    .then(res => res.json())
    .then(data => {
      const header = document.getElementById("tableHeader");
      const body = document.getElementById("resultBody");

      header.innerHTML = "";
      body.innerHTML = "";

      if (data.status !== "success") {
        body.innerHTML = `<tr><td colspan="10" style="text-align:center;color:red;">${data.message}</td></tr>`;
        return;
      }

      const records = data.records;

      // Get column names dynamically
      const keys = Object.keys(records[0]);
      keys.push("Actions");

      // Header
      keys.forEach(key => {
        const th = document.createElement("th");
        th.textContent = key.toUpperCase();
        header.appendChild(th);
      });

      // Rows
      records.forEach(rowData => {
        const tr = document.createElement("tr");

        keys.forEach(key => {
          const td = document.createElement("td");
          if (key === "Actions") {
            td.classList.add("actions");
            td.innerHTML = `
              <button class="btn-update">Update</button>
              <button class="btn-delete">Delete</button>
              <button class="btn-block">Block</button>
              <button class="btn-reset">Reset Password</button>
            `;
          } else {
            td.textContent = rowData[key.toLowerCase()] || "—";
          }
          tr.appendChild(td);
        });

        body.appendChild(tr);
      });
    })
    .catch(err => {
      console.error("Fetch error:", err);
      document.getElementById("resultBody").innerHTML =
        `<tr><td colspan="10" style="text-align:center;color:red;">Error fetching data.</td></tr>`;
    });
});
