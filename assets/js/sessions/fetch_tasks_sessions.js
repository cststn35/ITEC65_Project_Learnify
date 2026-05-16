async function fetchTasks(userID) {
  console.log(userID);
  const subjectInput = document.getElementById("tasks");
  try {
    const response = await fetch(
      `/${BASE_URL}/actions/sessions/fetch_tasks_sessions.php?userID=${userID}&semester_id=${semesterID}`,
    );

    // check HTTP status first
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      console.log(data.data);

      data.data.forEach((task) => {
        subjectInput.innerHTML += `<option value="${task.tasks_id}">${task.title}</option>`;
      });
    } else {
      console.log("Server returned error:", data.error || "Unknown error");
    }
  } catch (error) {
    console.error("Fetch failed:", error);
  }
}

fetchTasks(userID);
