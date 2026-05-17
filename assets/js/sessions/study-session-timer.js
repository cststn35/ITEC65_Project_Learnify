//CODE FOR TIMER
const progressCircle = document.querySelector(".progress-circle");

const timeDisplay = document.getElementById("time");

const radius = 200;
const circumference = 2 * Math.PI * radius;

progressCircle.style.strokeDasharray = circumference;

let quiz_decision = null;
let start_time = null;
let pause_start_time = null;
let total_pause_duration = 0;
let maxSeconds = 0;
let timer = null;
let isRunning = false;

function getElapsedSeconds() {
  if (!start_time) return 0;

  const start = new Date(start_time).getTime();
  const now = Date.now();

  return Math.floor((now - start) / 1000) - total_pause_duration;
}

const progreso = document.querySelector(".progreso");
const progreso2 = document.querySelector(".progreso-2");
const remainingMin = document.querySelector(".remaining-min");
const elapsedMins = document.querySelector(".elapsed-minutes");
const pausedMinutes = document.querySelector(".paused-minutes");

function updateTimer() {
  const elapsed = getElapsedSeconds();

  const minutes = Math.floor(elapsed / 60) % 60;
  const seconds = elapsed % 60;

  let plus = "";

  if (getElapsedSeconds() > maxSeconds) {
    plus = "+";
    document.querySelector(".timer-status").textContent = "Goal Reached! 🎇";
  }

  timeDisplay.textContent = `${plus}${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

  const progress = maxSeconds ? Math.min(elapsed / maxSeconds, 1) : 0;

  progreso.textContent = (progress * 100).toFixed(2) + "%";
  progreso2.textContent = (progress * 100).toFixed(2) + "%";
  remainingMin.textContent = Math.max(
    ((maxSeconds - elapsed) / 60).toFixed(1),
    0,
  );
  elapsedMins.textContent = Math.floor(elapsed / 60);

  const offset = circumference - progress * circumference;
  progressCircle.style.strokeDashoffset = offset;
}

async function startTimer() {
  await fetchSessionInfo(sessionID);

  if (isRunning) return;
  isRunning = true;

  //log start time only once
  if (!start_time) {
    const fd = new FormData();
    fd.append("userID", userID);
    fd.append("semesterID", semesterID);
    fd.append("sessionID", sessionID);

    await fetch(`/${BASE_URL}/actions/sessions/log_start_time.php`, {
      method: "POST",
      body: fd,
    });

    await fetchSessionInfo(sessionID);
  }

  //resuming from pause
  if (pause_start_time && isValidDateTime(pause_start_time)) {
    const pauseStart = new Date(pause_start_time).getTime();

    const pauseDuration = Math.floor((Date.now() - pauseStart) / 1000);

    total_pause_duration += pauseDuration;

    pause_start_time = null;

    const fd = new FormData();
    fd.append("userID", userID);
    fd.append("semesterID", semesterID);
    fd.append("sessionID", sessionID);
    fd.append("total_pause_seconds", total_pause_duration); //update total pause seconds in db once we resume
    fd.append("from_resume", true);

    await fetch(`/${BASE_URL}/actions/sessions/log_pause_start_time.php`, {
      method: "POST",
      body: fd,
    });
    pausedMinutes.textContent = Math.floor(total_pause_duration / 60);
  }

  if (timer) clearInterval(timer);

  timer = setInterval(updateTimer, 1000);
  updateTimer();
  document.querySelector(".resume-status").classList.add("hidden");
}

function getLocalDatetime() {
  const d = new Date();
  return (
    d.getFullYear() +
    "-" +
    String(d.getMonth() + 1).padStart(2, "0") +
    "-" +
    String(d.getDate()).padStart(2, "0") +
    " " +
    String(d.getHours()).padStart(2, "0") +
    ":" +
    String(d.getMinutes()).padStart(2, "0") +
    ":" +
    String(d.getSeconds()).padStart(2, "0")
  );
}

async function pauseTimer() {
  if (timer) clearInterval(timer); //clearInterval if its running
  isRunning = false;

  const nowLocal = getLocalDatetime();

  pause_start_time = nowLocal;

  const fd = new FormData();
  fd.append("userID", userID);
  fd.append("semesterID", semesterID);
  fd.append("sessionID", sessionID);
  fd.append("pause_start_time", nowLocal);
  fd.append("total_pause_seconds", total_pause_duration);
  fd.append("actual_duration_seconds", getElapsedSeconds());

  await fetch(`/${BASE_URL}/actions/sessions/log_pause_start_time.php`, {
    method: "POST",
    body: fd,
  });

  document.querySelector(".resume-status").classList.remove("hidden");
}

// function resetTimer() {
//   clearInterval(timer);
//   isRunning = false;

//   elapsed = 0;

//   timeDisplay.textContent = "00:00";

//   progressCircle.style.strokeDashoffset = circumference;
// }

const endSessionModal = document.querySelector("#end-modal");

function endSession() {
  pauseTimer();
  const studiedMinuto = document.querySelector(".studied-minuto");
  const goalMinuto = document.querySelector(".goal-minuto");
  const progressPercentage = document.querySelector(".progress-percentage");
  const progressWidth = document.querySelector(".progress-width");

  const studiedMinute = Math.floor(getElapsedSeconds() / 60);
  studiedMinuto.textContent = studiedMinute;
  const goalMinute = Math.floor(maxSeconds / 60);
  goalMinuto.textContent = goalMinute;
  const percentage = Math.min(
    Math.floor((getElapsedSeconds() / maxSeconds) * 100),
    100,
  );
  progressPercentage.textContent = `${percentage}%`;
  progressWidth.style.width = `${percentage}%`;
  const remaining = document.querySelector(".minutes-remaining");
  remaining.textContent = ((maxSeconds - getElapsedSeconds()) / 60).toFixed(1);

  const finished = document.querySelector(".finished");
  const mayContinue = document.querySelector(".may-continue");

  endSessionModal.classList.remove(
    "opacity-0",
    "pointer-events-none",
    "scale-95",
  );

  if (getElapsedSeconds() >= maxSeconds) {
    if (!mayContinue.classList.contains("hidden")) {
      mayContinue.classList.add("hidden");
    }

    finished.classList.remove("hidden");
  } else {
    if (!finished.classList.contains("hidden")) {
      finished.classList.add("hidden");
    }

    mayContinue.classList.remove("hidden");
  }
}

const endSessionBtn = document.getElementById("end-session-btn");
const sesCompleteModal = document.getElementById("sesCompleteOverlay");

endSessionBtn.addEventListener("click", showSessionCompletion);

function showSessionCompletion() {
  endSessionModal.classList.add("opacity-0", "pointer-events-none", "scale-95");

  const studied_minutes = document.querySelector(".end-studied-minutes");
  studied_minutes.textContent = Math.floor(getElapsedSeconds() / 60);
  const paused_minutes = document.querySelector(".end-paused-minutes");
  paused_minutes.textContent = Math.floor(total_pause_duration / 60);
  const goal_minutes = document.querySelector(".end-goal-minutes");
  goal_minutes.textContent = Math.floor(maxSeconds / 60);

  sesCompleteModal.classList.remove(
    "opacity-0",
    "pointer-events-none",
    "scale-95",
  );

  quizDecision();
}

function quizDecision() {
  console.log(quiz_decision);
  if (quiz_decision == "yes") {
    document.querySelector(".yes-quiz").classList.remove("hidden");
  } else if (quiz_decision == "later") {
    document.querySelector(".later-quiz").classList.remove("hidden");
  } else {
    document.querySelector(".complete").classList.remove("hidden");
  }
}

//handle tab switches and refresh
document.addEventListener("visibilitychange", () => {
  if (document.hidden) {
    console.log("user switched tab");
    pauseTimer();
  } else {
    console.log("user came back");
    startTimer();
  }
});

window.addEventListener("beforeunload", () => {
  console.log("closing tab or refreshing");
  pauseTimer();
});

//fullscreen code

let isFullScreen = false;
function triggerFullscreen() {
  const timerContainer = document.querySelector(".timer-container");
  if (!isFullScreen) {
    isFullScreen = true;
    timerContainer.classList.replace("h-140", "h-full");
    timerContainer.classList.replace("relative", "absolute");
    timerContainer.classList.add("z-100", "top-0", "left-0");
  } else {
    isFullScreen = false;
    timerContainer.classList.replace("h-full", "h-140");
    timerContainer.classList.replace("absolute", "relative");
    timerContainer.classList.remove("z-100", "top-0", "left-0");
  }
}

async function logEndTime() {
  const fd = new FormData();
  fd.append("userID", userID);
  fd.append("semesterID", semesterID);
  fd.append("sessionID", sessionID);
  fd.append("total_pause_seconds", total_pause_duration);
  fd.append("actual_duration_seconds", getElapsedSeconds());

  const response = await fetch(
    `/${BASE_URL}/actions/sessions/log_end_time.php`,
    {
      method: "POST",
      body: fd,
    },
  );

  const data = response.json();
  if (data.success) {
    console.log("successfully logged end time");
  } else {
    console.log("failed to log to end time");
  }
}

//code for uploading file to php and quiz generate
let selectedNumber = "";

const fileInput = document.querySelector(".file-input");
const uploadText = document.querySelector(".upload-text");
const selectedFile = document.querySelector(".selected-file");
const uploadStatus = document.querySelector(".upload-status");

function triggerUpload() {
  fileInput.click();
}

const generationStatus = document.querySelector(".generation-status");
const quizButtons = document.querySelector(".quiz-buttons");

fileInput.addEventListener("change", () => {
  if (!quizButtons.classList.contains("hidden")) {
    quizButtons.classList.add("hidden");
  }
  if (fileInput.files.length > 0) {
    console.log("File uploaded");
    uploadText.textContent = "Change file";
    generationStatus.textContent = "Loading...";
    const fileName = fileInput.files[0].name;
    uploadStatus.classList.remove("hidden");
    selectedFile.textContent = fileName;
    uploadFilePHP();
  }
});

async function uploadFilePHP() {
  const file = fileInput.files[0];
  const formData = new FormData();
  formData.append("file", file);
  formData.append("count", selectedNumber);

  try {
    const response = await fetch(
      `/${BASE_URL}/actions/sessions/quiz-generate.php`,
      {
        method: "POST",
        body: formData,
      },
    );
    const data = await response.json();
    if (data.success) {
      generationStatus.textContent = "Successful!";
      quizButtons.classList.remove("hidden");
    } else {
      generationStatus.textContent = "Failed! Try again";
    }
  } catch (error) {
    console.error("Upload failed:", error);
    generationStatus.textContent = "Failed! Try again";
  }
}

//for ask later option
const takeQuiz = document.querySelector("#take-quiz");
const laterNalang = document.querySelector("#later-nalang");

takeQuiz.addEventListener("click", async () => {
  await uploadQuestionsTODB();
  await logEndTime();
  window.location.href = "study-session-quiz.php";
});

laterNalang.addEventListener("click", async () => {
  await uploadQuestionsTODB();
  await logEndTime();
  window.location.href = "study-session.php";
});

//for yes option
const sesTake = document.getElementById("ses-take");
const sesLater = document.getElementById("ses-later");
sesTake.addEventListener("click", async () => {
  await logEndTime();
  window.location.href = "study-session-quiz.php";
});

sesLater.addEventListener("click", async () => {
  await logEndTime();
  window.location.href = "study-session.php";
});

//for no option
const sesClose = document.querySelector(".close-btn");
sesClose.addEventListener("click", async () => {
  await logEndTime();
  window.location.href = "study-session.php";
});

function chooseNumber(number) {
  const allNumbers = document.querySelectorAll(".number");

  allNumbers.forEach((number) => {
    number.classList.remove("bg-blue-500", "border-blue-500", "text-white");
    number.classList.add("border-slate-300", "bg-white");
  });

  const chosenNumber = document.querySelector("." + number);
  chosenNumber.classList.remove("border-slate-300", "bg-white");
  chosenNumber.classList.add("bg-blue-500", "border-blue-500", "text-white");

  selectedNumber = number;
}

//modal code

//for ending sessions
const closeBtnEnd = document.getElementById("closeModalEnd");
const cancelBtnEnd = document.getElementById("cancelBtnEnd");
const overlayEnd = document.getElementById("end-modal");
// const dialog = overlay.querySelector("[role='dialog']");

//for session completion

// Close modal and restore focus/scroll
function closeModal() {
  overlayEnd.classList.add("opacity-0");
  overlayEnd.classList.add("pointer-events-none");
  overlayEnd.classList.add("scale-95");
  document.body.style.overflow = "";
  // openBtn.focus();
}

closeBtnEnd.onclick = cancelBtnEnd.onclick = closeModal;

// Close when clicking outside the dialog
overlayEnd.onclick = (e) => {
  if (e.target === overlayEnd) closeModal();
};
