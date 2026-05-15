//CODE FOR TIMER
const progressCircle = document.querySelector(".progress-circle");

const timeDisplay = document.getElementById("time");

const radius = 200;
const circumference = 2 * Math.PI * radius;

progressCircle.style.strokeDasharray = circumference;

let elapsed = 0;
let timer = null;
let isRunning = false;

const maxSeconds = 10;
// full circle every 60 seconds

function updateTimer() {
  elapsed++;

  const minutes = Math.floor(elapsed / 60);

  const seconds = elapsed % 60;

  timeDisplay.textContent = `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

  const progress = (elapsed % maxSeconds) / maxSeconds;

  const offset = circumference - progress * circumference;

  progressCircle.style.strokeDashoffset = offset;
}

function startTimer() {
  if (isRunning) return;

  isRunning = true;

  timer = setInterval(updateTimer, 1000);
}

function pauseTimer() {
  clearInterval(timer);
  isRunning = false;
}

function resetTimer() {
  clearInterval(timer);
  isRunning = false;

  elapsed = 0;

  timeDisplay.textContent = "00:00";

  progressCircle.style.strokeDashoffset = circumference;
}

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
