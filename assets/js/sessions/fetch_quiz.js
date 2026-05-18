async function fetchQuizzes() {
  const response = await fetch(
    `/${BASE_URL}/actions/sessions/fetch_quiz.php?quizID=${quizID}`,
  );
  const data = await response.json();
  if (data.success) {
    renderData(data.data);
  }
}

const sessionTitle = document.querySelector(".session-title");
const quizContainer = document.querySelector(".quiz-container");
const pageBtnContainer = document.querySelector(".pageBtn-container");

function renderData(data) {
  sessionTitle.textContent = data[0].title;

  quizSet = data;

  data.forEach((question, i) => {
    //put hidden here
    quizContainer.innerHTML += `<div class="questions space-y-3 hidden">
                    <span
                        class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-blue-100 text-blue-700 border border-blue-200">Question
                        ${i + 1}</span>
                    <div class="text-xl font-semibold">
                        ${question.question}
                    </div>
                    <div class="choices-container flex flex-col gap-2" id=${question.id}>
                        <label class="cursor-pointer choice">
                            <input type="radio" name="choice${i + 1}" value="${question.choice_a}" class="peer hidden">
                            <div
                                class="w-full rounded-md bg-slate-100 h-10 p-5 flex items-center border border-gray-300 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:border-2">
                                <span>A. ${question.choice_a}</span>
                            </div>
                        </label>
                        <label class="cursor-pointer choice">
                            <input type="radio" name="choice${i + 1}" value="${question.choice_b}" class="peer hidden">
                            <div
                                class="w-full rounded-md bg-slate-100 h-10 p-5 flex items-center border border-gray-300 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:border-2">
                                <span>B. ${question.choice_b}</span>
                            </div>
                        </label>
                        <label class="cursor-pointer choice">
                            <input type="radio" name="choice${i + 1}" value="${question.choice_c}" class="peer hidden">
                            <div
                                class="w-full rounded-md bg-slate-100 h-10 p-5 flex items-center border border-gray-300 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:border-2">
                                <span>C. ${question.choice_c}</span>
                            </div>
                        </label>
                        <label class="cursor-pointer choice">
                            <input type="radio" name="choice${i + 1}" value="${question.choice_d}" class="peer hidden">
                            <div
                                class="w-full rounded-md bg-slate-100 h-10 p-5 flex items-center border border-gray-300 hover:border-blue-500 peer-checked:border-blue-600 peer-checked:bg-blue-100 peer-checked:border-2">
                                <span>D. ${question.choice_d}</span>
                            </div>
                        </label>
                    </div>
                </div>`;
    pageBtnContainer.innerHTML += `<div
                        class="pageNum rounded-full w-7 h-7 text-xs font-medium inline-flex justify-center items-center bg-gray-50 text-gray-700 border border-gray-200">
                        ${i + 1}</div>`;
    correct_answers.push(question.correct_answer);
  });

  questions_length = data.length;
  //   console.log(questions_length);
  student_answers = Array(questions_length).fill(null);
  //   console.log(student_answers);

  //register events and dom elements
  questionCount.textContent = `Questions: ${currentIndex + 1}/${questions_length}`;
  answeredCount.textContent = `Answered: ${student_answers.filter((a) => a !== null).length}/${questions_length}`; //to fix logic here
  progressBar.style.width = "0%";
  progressExit.style.width = "0%";

  pageNums = document.querySelectorAll(".pageNum");
  pageNums[0].classList.replace("bg-gray-50", "bg-blue-700");
  pageNums[0].classList.replace("text-gray-700", "text-white");

  questions = document.querySelectorAll(".questions");
  questions[0].classList.remove("hidden");

  let allChoice = document.querySelectorAll("input[type='radio']");
  allChoice.forEach((choice) => {
    choice.addEventListener("click", (e) => {
      const choiceContainer = e.target.closest(".choices-container");
      const id = choiceContainer.getAttribute("id");
      const value = choiceContainer.querySelector(
        "input[type='radio']:checked",
      ).value;
      const answer = { id: id, answer: value };
      getAnswer(answer);
    });
  });

  submitQuiz.disabled =
    student_answers.filter((a) => a !== null).length != correct_answers.length;
}
