const taskInput = document.getElementById("tasks");

async function startToStudy() {
  //function in auto filling study session once the user clicks start study in tasks page
  if (startStudy === null) return;

  const title = document.getElementById("title");
  const tasks = document.getElementById("tasks");
  const subject = document.getElementById("subject");
  const time = document.getElementById("time");

  title.value = startStudy["title"];
  tasks.querySelector(`option[value="${startStudy["tasks_id"]}"]`).selected =
    true;
  subject.querySelector(
    `option[value="${startStudy["subject_id"]}"]`,
  ).selected = true;
  time.value = Math.floor(startStudy["estimated_seconds"] / 60) ?? null;

  await unsetStartStudy();

  overlay.classList.remove("opacity-0");
  overlay.classList.remove("pointer-events-none");
  overlay.classList.remove("scale-95");
  document.body.style.overflow = "hidden";
  dialog.focus();
}

async function unsetStartStudy() {
  //unsets the session variable to prevent auto-filling if not from task page
  const response = await fetch(
    `/${BASE_URL}/actions/tasks/unset_session_study.php`,
  );
  const data = await response.json();
}

async function fetchTasks(userID) {
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

      data.data.forEach((task) => {
        taskInput.innerHTML += `<option value="${task.tasks_id}">${task.title}</option>`;
      });
    } else {
      console.log("Server returned error:", data.error || "Unknown error");
    }
  } catch (error) {
    console.error("Fetch failed:", error);
  }

  startToStudy();
}

fetchTasks(userID);

const subjectInput = document.querySelector("#subject");
const oras = document.querySelector("#time");
const title = document.querySelector("#title");
taskInput.addEventListener("change", async (e) => {
  if (e.target.value == "N/A") {
    subjectInput.selectedIndex = 0;
    oras.value = 0;
    title.value = "";
    return;
  }
  const taskID = e.target.value;
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/auto_fill_sessions_form.php?taskID=${taskID}`,
  );
  const data = await response.json();
  if (data.success) {
    const task = data.data[0];
    subjectInput.querySelector(`option[value='${task.subject_id}']`).selected =
      true;
    oras.value = Math.floor(task.estimated_seconds / 60) ?? 0;
    title.value = task.title;
  }
});
