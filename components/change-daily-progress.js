document
  .getElementById("updateGoalBtn")
  .addEventListener("click", async function () {
    const daily_goal = document.getElementById("daily_goal").value;
    const errorBox = document.querySelector(".daily-goal-error"); // reuse or change if you want

    errorBox.textContent = "";

    if (!daily_goal || daily_goal < 1) {
      errorBox.textContent = "Please enter a valid daily goal.";
      return;
    }

    try {
      const res = await fetch("../components/update-daily-goal.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ daily_goal }),
      });

      const data = await res.json();

      if (!data.success) {
        errorBox.textContent = data.message;
        return;
      }

      Swal.fire({
        icon: "success",
        title: "Daily Progress Changed!",
        text: "The daily progress has been successfully changed",
      });
    } catch (err) {
      Swal.fire({
        icon: "error",
        title: "Failed!",
        text: "There was en error changing the daily progress",
      });
      errorBox.textContent = "Something went wrong.";
    }
  });
