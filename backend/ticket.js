document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("ticketForm");
  const nameInput  = document.querySelector('input[name="fullname"]');
  const regnoInput = document.querySelector('input[name="regno"]');
  const emailInput = document.querySelector('input[name="email"]');

  // Save original values (from PHP value="")
  const original = {
    name:  nameInput.value,
    regno: regnoInput.value,
    email: emailInput.value
  };

  // Ensure readonly
  nameInput.readOnly = true;
  regnoInput.readOnly = true;
  nameInput.style.backgroundColor = '#f0f0f0';
  regnoInput.style.backgroundColor = '#f0f0f0';

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    try {
      const response = await fetch("../backend/ticket.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (!response.ok) {
        alert("Server error. Please try again later.");
        return;
      }

      if (result.success) {
        alert(result.message);
        form.reset();

        // RESTORE VALUES AFTER RESET
        nameInput.value  = original.name;
        regnoInput.value = original.regno;
        emailInput.value = original.email;
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error("Network Error:", error);
      alert("Network error while submitting the ticket.");
    }
  });
});
