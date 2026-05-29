async function showQuizResult(id) {
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/fetch_quiz_result.php?quizID=${id}`,
  );
  const data = await response.json();
  if (data.success) {
    renderQuizResult(data.data);
  }
}

function renderQuizResult(data) {
  openModalRes();
  const answerCont = document.querySelector(".answer-cont");
  answerCont.innerHTML = "";
  data.forEach((question, i) => {
    answerCont.innerHTML += `<div class="border-b py-4 border-slate-300">
                              <p>${i + 1}.) ${question.question}</p>
                              <br/>
                              <p>Correct Answer: <b>${question.correct_answer}</b></p>
                              <p>Your answer: <span class="${question.correct_answer == question.my_answer ? "text-green-600" : "text-red-600"}">${question.my_answer}</span></p>
                          </div>`;
  });
}

//code for modal functionalities
const cancelBtnRes = document.getElementById("exit-ngayon");
const overlay2 = document.getElementById("modalOverlay2");

function openModalRes() {
  overlay2.classList.remove("opacity-0");
  overlay2.classList.remove("pointer-events-none");
  overlay2.classList.remove("scale-95");
  document.body.style.overflow = "hidden";
}

// Close modal and restore focus/scroll
function closeModalRes() {
  overlay2.classList.add("opacity-0");
  overlay2.classList.add("pointer-events-none");
  overlay2.classList.add("scale-95");
  document.body.style.overflow = "";
}

cancelBtnRes.onclick = closeModalRes;

// Close when clicking outside the dialog
overlay2.onclick = (e) => {
  if (e.target === overlay2) closeModalRes();
};
