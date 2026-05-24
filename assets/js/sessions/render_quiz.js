let questions = null;
const progressBar = document.querySelector(".progress-bar-current");
const progressExit = document.querySelector(".progress-exit");
const progressExitPercentage = document.querySelector(
  ".progress-exit-percentage",
);
const questionCount = document.querySelector(".question-count");
const answeredCount = document.querySelector(".answered-count");
let pageNums = null;
const next = document.querySelector(".next");
const previous = document.querySelector(".previous");
const submitQuiz = document.querySelector(".submit-quiz");
const exitQuiz = document.querySelector(".exit-quiz");
const allAnswered = document.querySelector(".all-answered");
let quizSet = null;

let student_answers = [];
let correct_answers = [];
let questions_length = 0;
let score = 0;
let currentIndex = 0;

next.onclick = nextPage;
previous.onclick = previousPage;
submitQuiz.onclick = submitQuizNow;
// exitQuiz.onclick = exitQuizNow;

function nextPage() {
  if (currentIndex + 1 > questions_length - 1) return;
  currentIndex++;
  updateDOM();
}

function previousPage() {
  if (currentIndex - 1 < 0) return;
  currentIndex--;
  updateDOM();
}

function updateDOM() {
  questions.forEach((question) => {
    if (!question.classList.contains("hidden")) {
      question.classList.add("hidden");
    }
  });

  pageNums.forEach((page) => {
    if (page.classList.contains("bg-blue-700", "bg-blue-700")) {
      page.classList.replace("bg-blue-700", "bg-gray-50");
      page.classList.replace("text-white", "text-gray-700");
    }
  });

  student_answers.forEach((answer, i) => {
    if (answer !== null) {
      pageNums[i].classList.replace("bg-gray-50", "bg-green-300");
      pageNums[i].classList.replace("text-white", "text-gray-700");
    }
  });

  if (pageNums[currentIndex].classList.contains("bg-green-300")) {
    pageNums[currentIndex].classList.replace("bg-green-300", "bg-blue-700");
  }

  pageNums[currentIndex].classList.replace("bg-gray-50", "bg-blue-700");
  pageNums[currentIndex].classList.replace("text-gray-700", "text-white");

  questionCount.textContent = `Questions: ${currentIndex + 1}/${questions_length}`;
  answeredCount.textContent = `Answered: ${student_answers.filter((a) => a !== null).length}/${questions_length}`;

  progressBar.style.width = `${Math.floor((student_answers.filter((a) => a !== null).length / questions_length) * 100)}%`;

  questions[currentIndex].classList.remove("hidden");
}

function getAnswer(answer) {
  student_answers[currentIndex] = answer;
  answeredCount.textContent = `Answered: ${student_answers.filter((a) => a !== null).length}/${questions_length}`;
  progressBar.style.width = `${Math.floor((student_answers.filter((a) => a !== null).length / questions_length) * 100)}%`;
  submitQuiz.disabled =
    student_answers.filter((a) => a !== null).length != correct_answers.length;

  if (
    student_answers.filter((a) => a !== null).length == correct_answers.length
  ) {
    allAnswered.classList.remove("hidden");
  }
}

function computeScore() {
  const xp_earned_quiz = document.querySelector(".xp-earned-quiz");
  const resultModal = document.querySelector("#resultOverlay");
  const scoreCont = document.querySelector(".score-cont");
  score = student_answers.filter(
    (ans, i) => ans.answer === correct_answers[i],
  ).length;
  let xp_earned = Math.floor(score * 2) + 20; //twenty is for completing the quiz, two xp per correct answer as well
  xp_earned_quiz.textContent = `+${xp_earned}`;
  scoreCont.textContent = `${score}/${questions_length}`;
  const answerCont = document.querySelector(".answer-cont");
  quizSet.forEach((question, i) => {
    answerCont.innerHTML += `<div class="border-t py-4 border-slate-300">
                            <p>${i + 1}.) ${question.question}</p>
                            <br/>
                            <p>Correct Answer: <b>${question.correct_answer}</b></p>
                            <p>Your answer: <span class="${question.correct_answer == student_answers[i].answer ? "text-green-600" : "text-red-600"}">${student_answers[i].answer}</span></p>
                        </div>`;
  });
  resultModal.classList.remove("opacity-0", "pointer-events-none", "scale-95");

  const exitNgayon = document.querySelector("#exit-ngayon");
  exitNgayon.onclick = exitQuizNow;
}

async function submitQuizNow() {
  const result = await Swal.fire({
    title: "Quiz Submission",
    text: "Do you want to submit this quiz now?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });
  if (result.isConfirmed) {
    computeScore();
  }
}

async function exitQuizNow() {
  //mark quiz as complete
  //log the answers
  //log the xp based on correct answers
  //param to pass is quiz id only

  //log first xp
  let xp_earned = Math.floor(score * 2) + 20; //twenty is for completing the quiz, two xp per correct answer as well
  const fd2 = new FormData();
  fd2.append("userID", userID);
  fd2.append("semesterID", semesterID);
  fd2.append("reason", "QUIZ_COMPLETION");
  fd2.append("xp", xp_earned);
  await fetch(`/${BASE_URL}/actions/log_xp.php`, {
    method: "POST",
    body: fd2,
  });

  const fd = new FormData();
  fd.append("quizID", quizID);
  fd.append("studAnswer", JSON.stringify(student_answers));
  fd.append("score", score);
  fd.append("total_questions", questions_length);
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/submit_quiz.php`,
    {
      method: "POST",
      body: fd,
    },
  );
  const data = await response.json();
  if (data.success) {
    window.location.href = "study-session.php";
  }
}

const abandon = document.getElementById("abandon-quiz");
abandon.onclick = abandonQuiz;
async function abandonQuiz() {
  const fd = new FormData();
  fd.append("quizID", quizID);
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/abandon_quiz.php`,
    {
      method: "POST",
      body: fd,
    },
  );
  const data = await response.json();
  if (data.success) {
    window.location.href = "study-session.php";
  }
}

//code for modal functionalities (open, close, create) from readymadeui
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const overlay = document.getElementById("modalOverlay");
const dialog = overlay.querySelector("[role='dialog']");

// Open modal and lock body scroll
exitQuiz.onclick = () => {
  overlay.classList.remove("opacity-0");
  overlay.classList.remove("pointer-events-none");
  overlay.classList.remove("scale-95");
  document.body.style.overflow = "hidden";
  dialog.focus();
  progressExit.style.width = `${Math.floor((student_answers.filter((a) => a !== null).length / questions_length) * 100)}%`;
  progressExitPercentage.textContent = `${Math.floor((student_answers.filter((a) => a !== null).length / questions_length) * 100)}%`;
};

// Close modal and restore focus/scroll
function closeModal() {
  overlay.classList.add("opacity-0");
  overlay.classList.add("pointer-events-none");
  overlay.classList.add("scale-95");
  document.body.style.overflow = "";
  openBtn.focus();
}

closeBtn.onclick = cancelBtn.onclick = closeModal;

// Close when clicking outside the dialog
overlay.onclick = (e) => {
  if (e.target === overlay) closeModal();
};

document.addEventListener("DOMContentLoaded", async () => {
  await fetchQuizzes();
});
