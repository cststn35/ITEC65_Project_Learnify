<?php include_once("../actions/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Study Sessions";
        include_once("../components/topsidebar.php")
            ?>
        <main class="bg-gray-200 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-y-auto">
            <!-- warning modal -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="modalOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] transition-all opacity-0 pointer-events-none scale-95">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="end-session-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-calendar-exclamation text-2xl'></i><span>Leave Quiz?</span>
                        </h3>

                        <button type="button" id="closeModal" aria-label="Close modal"
                            class="ml-auto flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600" aria-hidden="true"
                                viewBox="0 0 329.269 329">
                                <path
                                    d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
                            </svg>
                        </button>
                    </div>

                    <div class="content py-5 space-y-5">
                        <div class="text-center text-sm text-slate-400">Your progress will be lost if you exit now</div>
                        <div class="flex-none">
                            <div class="flex justify-between">
                                <span>Progress</span>
                                <span class="progress-exit-percentage">0%</span>
                            </div>
                            <div class="rounded-md w-full bg-slate-200 h-3">
                                <div class="progress-exit rounded-md bg-blue-600 h-3"></div>
                            </div>
                        </div>
                        <div class="rounded-md bg-red-100 border border-red-300 p-5 text-center text-red-800">
                            ⚠️ You will lose all progress and the quiz will be considered abandoned if not submitted!
                        </div>
                    </div>

                    <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                        <button type="button" id="cancelBtn"
                            class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                            Continue Quiz</button>
                        <button type="submit" id="task-submit"
                            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                            Exit Anyway</button>
                    </div>
                </div>
            </div>
            <!-- result show -->
            <div id="resultOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] transition-all opacity-0 pointer-events-none scale-95">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="end-session-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-pencil text-2xl'></i><span>Score Result</span>
                        </h3>
                    </div>

                    <div class="answer-cont content py-5">
                        <div class="score-cont text-center text-5xl text-black font-bold py-5">100/100</div>
                        <h1>Review your answers (TAKE A SCREENSHOT):</h1>
                    </div>

                    <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                        <button type="submit" id="exit-ngayon"
                            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                            Exit</button>
                    </div>
                </div>
            </div>
            <header class="bg-white w-full shadow-md p-5 rounded-md">
                <div class="w-full max-w-4xl mx-auto flex justify-center">
                    <!-- <div class="flex-1">
                        <i class='bx bx-left-arrow-alt'></i>
                        Exit Quiz
                    </div> -->
                    <div class="flex flex-col justify-center items-center flex-1 text-center">
                        <span class="font-bold text-3xl session-title">Taking Charge of One's Health</span>
                        <!-- <span>Based on: Activity2.docx</span> -->
                    </div>
                </div>
            </header>
            <!-- progress bar -->
            <div class="mt-6 progress-bar w-full max-w-4xl bg-white rounded-md mx-auto shadow-md p-5">
                <div class="flex justify-between">
                    <div class="question-count">Question 1/10</div>
                    <div class="answered-count">Answered: 0/10</div>
                </div>
                <div class="rounded-md w-full bg-slate-300 h-3">
                    <div class="progress-bar-current rounded-md bg-blue-600 h-3"></div>
                </div>
            </div>
            <!-- quiz container -->
            <div class="mt-5 quiz-container bg-white shadow-md w-full max-w-4xl mx-auto p-5 rounded-md">
                <!-- one item -->
            </div>
            <!-- navigation container -->
            <div class="mt-6 w-full max-w-4xl mx-auto flex justify-between items-center gap-3 md:gap-0">
                <button type="button"
                    class="previous rounded-md bg-white px-3.5 py-2 border border-slate-400 cursor-pointer text-xs md:text-base">Previous</button>
                <div class="flex gap-1 flex-wrap pageBtn-container">
                </div>
                <button type="button"
                    class="next rounded-md bg-white px-3.5 py-2 border border-slate-400 cursor-pointer text-xs md:text-base">Next</button>
            </div>
            <!-- exit/submit container -->
            <div
                class="mt-6 w-full max-w-4xl bg-white shadow-md p-5 mx-auto rounded-md flex justify-between items-center">
                <button type="button"
                    class="exit-quiz rounded-md bg-white px-3.5 py-2 border border-red-400 cursor-pointer text-xs md:text-base text-red-500 font-semibold">Exit
                    Quiz</button>
                <span class="text-center all-answered hidden">All questions answered</span>
                <button type="button"
                    class="submit-quiz rounded-md px-3.5 py-2 border border-green-500 cursor-pointer text-xs md:text-base text-white font-semibold bg-green-500 disabled:cursor-not-allowed">Submit
                    Quiz</button>
            </div>
        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        console.log(BASE_URL);
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
        const quizID = <?= json_encode($_SESSION['quiz_id'] ?? null) ?>;
        console.log(quizID);
    </script>
    <script src="../assets/js/sessions/render_quiz.js"></script>
    <script src="../assets/js/sessions/fetch_quiz.js"></script>

</body>

</html>