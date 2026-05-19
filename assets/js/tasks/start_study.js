let isSessionOngoing = false;
let isQuizActive = false;

async function checkOngoingSession() {
  isSessionOngoing = false;
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/check_ongoing_session.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    isSessionOngoing = data.isOngoing;
  }
}

async function checkActiveQuiz() {
  isQuizActive = false;
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/check_ongoing_quiz.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    isQuizActive = data.isOngoing;
  }
}

async function startStudy(taskID, userID) {
  await checkOngoingSession();
  if (isSessionOngoing) {
    const result = await Swal.fire({
      title: "Ongoing Session Detected",
      text: "Do you want to continue this session?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
    });

    if (result.isConfirmed) {
      window.location.href = "study-session-timer.php";
    }

    return;
  }

  await checkActiveQuiz();
  if (isQuizActive) {
    const result = await Swal.fire({
      title: "Unanswered Quiz Detected",
      text: "Do you want to answer this quiz now?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
    });

    if (result.isConfirmed) {
      window.location.href = "study-session-quiz.php";
    }

    return;
  }

  const result = await Swal.fire({
    title: "Start Study Session?",
    text: "Do you want to start a study session?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  if (!result.isConfirmed) return;

  const response = await fetch(
    `/${BASE_URL}/actions/tasks/start_study.php?taskID=${taskID}`,
  );
  const data = await response.json();
  if (data.success) {
    window.location.href = "study-session.php";
  }
}
