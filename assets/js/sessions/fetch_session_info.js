async function fetchSessionInfo(sesID) {
  try {
    const response = await fetch(
      `/${BASE_URL}/actions/sessions/fetch_session_info.php?userID=${userID}&semesterID=${semesterID}&session_id=${sesID}`,
    );

    // check HTTP status first
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      renderData(data.data[0]);
    } else {
      console.log("Server returned error:", data.error || "Unknown error");
    }
  } catch (error) {
    console.error("Fetch failed:", error);
  }
}

function isValidDateTime(dt) {
  return dt && dt !== "0000-00-00 00:00:00";
}

function renderData(data) {
  start_time = isValidDateTime(data.start_time) ? data.start_time : null;

  pause_start_time = isValidDateTime(data.pause_start_time)
    ? data.pause_start_time
    : null;

  total_pause_duration = Number(data.total_pause_seconds) || 0;

  maxSeconds = (Number(data.target_duration_minutes) || 0) * 60;

  document.querySelector(".title-tab").textContent = data.title;

  document.querySelector(".subjectName").textContent = data.subject_name;

  document.querySelector(".taskName").textContent =
    data.task_title ?? "General Study";

  const targetMinutes = Number(data.target_duration_minutes) || 0;

  document.querySelector(".goalDuration").textContent = targetMinutes;
  document.querySelector(".planned-duration").textContent = targetMinutes;
  document.querySelector(".goal-planned-duration").textContent = targetMinutes;

  document.querySelector(".fileName").textContent = data.file_name?.trim()
    ? data.file_name
    : "N/A";

  const qc = Number(data.question_count) || 0;

  document.querySelector(".questionsCount").textContent =
    `${qc} questions generated`;

  document.querySelector(".quizStatus").textContent =
    qc > 0 ? "Quiz Prepared ✅" : "No Quiz Prepared";

  //initial displaying of time
  const elapsed = start_time ? getElapsedSeconds() : 0;

  const minutes = Math.floor(elapsed / 60);
  const seconds = elapsed % 60;

  timeDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

  const progress = maxSeconds ? (elapsed % maxSeconds) / maxSeconds : 0;

  progressCircle.style.strokeDashoffset =
    circumference - progress * circumference;
}

async function uploadQuestionsTODB() {
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/upload-questions.php?sessionID=${sessionID}`,
  );
  const data = await response.text();
  console.log(data);
  // if (data.success) {
  //   console.log("questions uploaded");
  // } else {
  //   console.log("try again");
  // }
}

if (toUploadTODB) {
  console.log("will upload");
  console.log(questions);
  uploadQuestionsTODB();
} else {
  console.log("will not upload");
}

fetchSessionInfo(sessionID);
