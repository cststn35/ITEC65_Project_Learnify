//CODE FOR TIMER
const progressCircle = document.querySelector(".progress-circle");

const timeDisplay = document.getElementById("time");

const radius = 200;
const circumference = 2 * Math.PI * radius;

progressCircle.style.strokeDasharray = circumference;

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

  const minutes = Math.floor(elapsed / 60);
  const seconds = elapsed % 60;

  timeDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

  const progress = maxSeconds ? (elapsed % maxSeconds) / maxSeconds : 0;
  progreso.textContent = (progress * 100).toFixed(2) + "%";
  progreso2.textContent = (progress * 100).toFixed(2) + "%";
  remainingMin.textContent = ((maxSeconds - elapsed) / 60).toFixed(1);
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
}

function resetTimer() {
  clearInterval(timer);
  isRunning = false;

  elapsed = 0;

  timeDisplay.textContent = "00:00";

  progressCircle.style.strokeDashoffset = circumference;
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

//code for upload file
let selectedAnswer = "";
let selectedNumber = "";
function changeBorder(choice) {
  console.log(choice);
  const allChoices = document.querySelectorAll(".answer");
  const uploadInput = document.querySelector(".uploadInput");

  //reset all borders
  allChoices.forEach((choice) => {
    choice.classList.remove("border-blue-400", "bg-blue-100");
    choice.classList.add("border-slate-300");
    const innerDiv = choice.querySelector("div");
    const circle = innerDiv.querySelector("div");
    innerDiv.classList.remove("border");
    innerDiv.classList.add("bg-black");
    circle.classList.add("hidden");
    uploadInput.classList.add("hidden");
  });

  const choiceItem = document.querySelector(choice);

  choiceItem.classList.add("border-blue-400", "bg-blue-100");
  choiceItem.classList.remove("border-slate-300");
  const innerDiv = choiceItem.querySelector("div");
  const circle = innerDiv.querySelector("div");
  innerDiv.classList.add("border");
  innerDiv.classList.remove("bg-black");
  circle.classList.remove("hidden");

  if (choice == ".yes-answer") {
    uploadInput.classList.remove("hidden");
  }

  selectedAnswer = choice; //database purposes
}

const fileInput = document.querySelector(".file-input");
const uploadText = document.querySelector(".upload-text");
const selectedFile = document.querySelector(".selected-file");
const uploadStatus = document.querySelector(".upload-status");

function triggerUpload() {
  fileInput.click();
}

fileInput.addEventListener("change", () => {
  if (fileInput.files.length > 0) {
    console.log("File uploaded");
    uploadText.textContent = "Change file";
    const fileName = fileInput.files[0].name;
    uploadStatus.classList.remove("hidden");
    selectedFile.textContent = fileName;
  }
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
