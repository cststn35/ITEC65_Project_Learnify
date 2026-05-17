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
            <!-- end study session modal overlay -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="end-modal"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] transition-all opacity-0 pointer-events-none scale-95">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="end-session-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-alarm-exclamation text-2xl'></i><span>End Study Session?</span>
                        </h3>

                        <button type="button" id="closeModalEnd" aria-label="Close modal"
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
                        <div class="flex gap-4">
                            <div
                                class="rounded-md bg-slate-200 border border-slate-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700">Studied</div>
                                <div class="text-3xl font-bold studied-minuto">0</div>
                                <div class="text-sm text-slate-700">minutes</div>
                            </div>
                            <div
                                class="rounded-md bg-slate-200 border border-slate-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700">Goal</div>
                                <div class="text-3xl font-bold goal-minuto">20</div>
                                <div class="text-sm text-slate-700">minutes</div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between">
                                <span>Progress</span>
                                <span class="progress-percentage">0%</span>
                            </div>
                            <div class="rounded-md w-full bg-slate-200 h-3">
                                <div class="rounded-md bg-blue-600 h-3 progress-width"></div>
                            </div>
                        </div>
                        <div class="rounded-md bg-amber-100 border border-amber-300 p-5 text-center text-yellow-800">
                            <span class="hidden may-continue">You're only <span class="minutes-remaining">x</span>
                                minute(s) away from reaching your goal. You may end the session now or keep
                                going.</span>
                            <span class="hidden finished">You've reached your goal! You may end the session or continue
                                studying.</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                        <button type="button" id="cancelBtnEnd"
                            class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                            Cancel</button>
                        <button type="submit" id="end-session-btn"
                            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                            End Session</button>
                    </div>
                </div>
            </div>
            <!-- session completed overlay -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="sesCompleteOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] transition-all opacity-0 pointer-events-none scale-95">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="complete-session-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-check-circle text-2xl text-green-600'></i><span>Session Completed!
                                🎇🎊</span>
                        </h3>

                        <!-- <button type="button" id="closeModal" aria-label="Close modal"
                            class="ml-auto flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600" aria-hidden="true"
                                viewBox="0 0 329.269 329">
                                <path
                                    d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
                            </svg>
                        </button> -->
                    </div>

                    <div class="content pt-5 space-y-5">
                        <div class="flex gap-4">
                            <div
                                class="rounded-md bg-slate-200 border border-slate-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700 flex items-center gap-2"><i
                                        class='bx bx-time text-xl text-blue-600'></i>Studied</div>
                                <div class="text-3xl font-bold end-studied-minutes">0</div>
                                <div class="text-sm text-slate-700">minutes</div>
                            </div>
                            <div
                                class="rounded-md bg-slate-200 border border-slate-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700 flex items-center gap-2"><i
                                        class='bx bx-time text-xl text-blue-600'></i>Goal</div>
                                <div class="text-3xl font-bold end-goal-minutes">20</div>
                                <div class="text-sm text-slate-700">minutes</div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="rounded-md bg-yellow-100 border border-yellow-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700 flex items-center gap-2"><i
                                        class='bx bx-pause-circle text-2xl text-orange-400'></i>Paused</div>
                                <div class="text-3xl font-bold end-paused-minutes">0</div>
                                <div class="text-sm text-slate-700">minutes</div>
                            </div>
                            <div
                                class="rounded-md bg-yellow-100 border border-yellow-300 flex flex-col justify-center items-center gap-2 p-5 flex-1">
                                <div class="text-slate-700 flex items-center gap-2"><i
                                        class="fa-solid fa-bolt text-yellow-500 text-xl"></i>XP Earned</div>
                                <div class="text-3xl font-bold">+25</div>
                                <div class="text-sm text-slate-700">experience</div>
                            </div>
                        </div>
                        <!-- <div>
                            <div class="flex justify-between">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="rounded-md w-full bg-slate-200 h-3">
                                <div class="rounded-md w-[50%] bg-blue-600 h-3"></div>
                            </div>
                        </div> -->

                        <!-- yes quiz -->
                        <div
                            class="yes-quiz rounded-md bg-green-100 border border-green-300 p-5 text-center text-yellow-800 flex flex-col gap-2 hidden">
                            <div>Your quiz is ready! Test your knowledge now!</div>
                            <div class="flex justify-center gap-2">
                                <button type="submit" id="ses-take"
                                    class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-green-600 border border-green-600 transition-colors hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
                                    Take Quiz</button>
                                <button type="button" id="ses-later"
                                    class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    Later</button>
                            </div>
                        </div>
                        <!-- upload quiz -->
                        <div
                            class="later-quiz uploadInput w-full rounded-md bg-blue-100 border border-blue-200 p-5 space-y-3 hidden">
                            <div>
                                <span class="font-semibold">Number of Questions</span>
                                <div class="flex gap-3">
                                    <button
                                        class="number five px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                        type="button" onclick="chooseNumber('five')">5</button>
                                    <button
                                        class="number ten px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                        type="button" onclick="chooseNumber('ten')">10</button>
                                    <button
                                        class="number fifteen px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                        type="button" onclick="chooseNumber('fifteen')">15</button>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <span class="font-semibold">Upload Learning Materials (PDF, DOCX, TXT)</span>
                                <div class="bg-white border-2 border-dashed border-slate-300 rounded-md p-4 flex justify-center items-center gap-2 cursor-pointer"
                                    onclick="triggerUpload()">
                                    <span class="flex items-center"><i
                                            class='bx bx-upload text-slate-500 text-2xl'></i></span>
                                    <span class="upload-text">Click to Upload</span>
                                    <input type="file" hidden class="file-input" accept=".pdf, .docx, .txt">
                                </div>
                                <div class="upload-status rounded-md border border-slate-300 bg-white p-4 hidden">
                                    <div>
                                        <span>Selected: </span>
                                        <span class="selected-file font-semibold"></span>
                                    </div>
                                    <div>
                                        <span>Quiz Generation Status: </span>
                                        <span class="font-semibold generation-status">Loading...</span>
                                    </div>
                                    <div class="quiz-buttons flex justify-center gap-3 mt-3 hidden">
                                        <button type="submit" id="take-quiz"
                                            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-green-600 border border-green-600 transition-colors hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500">
                                            Take Quiz</button>
                                        <button type="button" id="later-nalang"
                                            class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                            Later</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="complete hidden">
                            <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                                <button type="button" id="cancelBtnEnd"
                                    class="close-btn px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="w-full max-w-7xl mx-auto space-y-5">
                <!-- header -->
                <div class="bg-white w-full flex justify-center items-center p-7 rounded-lg shadow-md">
                    <span class="font-bold text-xl md:text-2xl title-tab">Demistifying React.js Hooks</span>
                </div>
                <!-- task card -->
                <div class="bg-white w-full flex justify-evenly items-center p-3 md:p-7 rounded-lg shadow-md">
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs md:text-sm text-slate-600">Subject</div>
                        <div class="subjectName">Physics</div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs  md:text-sm text-slate-600">Task</div>
                        <div class="taskName">General Study</div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs  md:text-sm text-slate-600">Goal Duration</div>
                        <div>
                            <span class="goalDuration"></span><span> minutes</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs md:text-sm text-slate-600">Status</div>
                        <div class="timer-status">Keep Going!</div>
                    </div>
                </div>
                <!-- timer itself -->
                <div
                    class="timer-container relative bg-gradient-to-r from-[#0A2A88] to-[#59CDE9] w-full h-140 rounded-lg flex justify-center items-center flex-col space-y-1">
                    <div class="circle-wrapper">
                        <svg width="440" height="440" class="svg">
                            <circle class="bg-circle" cx="220" cy="220" r="200"></circle>
                            <circle class="progress-circle" cx="220" cy="220" r="200"></circle>
                        </svg>
                        <div class="time" id="time">
                            00:00
                        </div>
                    </div>
                    <div class="flex justify-center gap-6 text-slate-200">
                        <div class="flex justify-center gap-2">
                            <div>
                                <i class='bx bx-time'></i>
                                <span>Goal:</span>
                            </div>
                            <div>
                                <span class="goal-planned-duration"></span>
                                <span> minutes</span>
                            </div>
                        </div>
                        <div>|</div>
                        <div class="flex justify-center gap-2">
                            <div>
                                <i class='bx bx-time'></i>
                                <span>Remaining:</span>
                            </div>
                            <div>
                                <span class="remaining-min"></span>
                                <span> minutes</span>
                            </div>
                        </div>
                        <div>|</div>
                        <div>
                            <span>Percentage: </span>
                            <span class="progreso-2"></span>
                        </div>
                    </div>
                    <div class="resume-status bg-white/10 backdrop-blur-md rounded-xl p-3 text-slate-200 hidden">
                        ▶️Take your break. Resume when ready
                    </div>
                    <div class="absolute right-5 bottom-5 cursor-pointer" onclick="triggerFullscreen()">
                        <i class='bx bx-fullscreen text-3xl text-slate-100 font-bold'></i>
                    </div>
                </div>
                <div class="bg-white shadow-md rounded-md p-6 space-y-1">
                    <h1 class="font-bold text-lg">Session Progress</h1>
                    <div class="flex justify-evenly gap-4">
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-slate-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Planned Duration</div>
                            <div class="text-lg"><span class="planned-duration"></span><span> minutes</span></div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-green-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Studied Minutes</div>
                            <div class="text-lg"><span class="elapsed-minutes"></span><span> minutes</span></div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-yellow-50 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Paused Minutes</div>
                            <div class="text-lg"><span class="paused-minutes"></span><span> minutes</span></div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-gray-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Progress</div>
                            <div class="text-lg progreso">0%</div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="rounded-md bg-green-600 text-xl px-4 py-4 font-bold shadow-md flex-1 flex justify-center items-center text-white cursor-pointer"
                        onclick="startTimer()">
                        <i class='bx bx-play text-3xl'></i><span>Start</span>
                    </div>
                    <div class="rounded-md bg-yellow-400 text-xl px-4 py-4 font-bold shadow-md flex-1 flex justify-center items-center text-white cursor-pointer"
                        onclick="pauseTimer()">
                        <i class='bx bx-pause text-3xl'></i><span>Pause</span>
                    </div>
                    <div class="rounded-md bg-red-500 text-xl px-4 py-4 font-bold shadow-md flex-1 flex justify-center items-center text-white cursor-pointer"
                        onclick="endSession()">
                        <i class='bx bx-stop text-3xl'></i><span>End Session</span>
                    </div>
                </div>
                <div class="flex gap-3">
                    <div class="bg-white rounded-md p-5 flex-1 shadow-md">
                        <h1 class="font-bold">Session Notes</h1>
                        <textarea
                            class="w-full h-50 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden border border-slate-400 rounded-md p-4"
                            placeholder="Write your reflections, key learnings, or questions..."></textarea>
                    </div>
                    <div class="flex flex-col gap-3 flex-1">
                        <div class="bg-white p-5 rounded-md shadow-md flex-1">
                            <h1 class="font-bold">Quiz Status</h1>
                            <div class="space-y-1">
                                <div class="space-x-2"><i class='bx bxs-file-doc'></i><span class="fileName">Lesson 10.
                                        Digital Self
                                        (highlighted).pdf</span>
                                </div>
                                <div class="space-x-2"><i class='bx bx-check-square'></i><span class="quizStatus">Quiz
                                        Prepared ✔</span>
                                </div>
                                <div><span class="questionsCount">10 questions generated</span>
                                </div>
                            </div>
                        </div>
                        <div class="border border-amber-300 bg-yellow-100 rounded-md p-10 shadow-md">
                            <span class="italic">“The secret to getting ahead is getting started” - Mark Twain</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
        const sessionID = <?= json_encode($_SESSION['session_id'] ?? null) ?>;
        const questions = <?= json_encode($_SESSION["quizzes"] ?? null) ?>;
        const toUploadTODB = questions != null ? true : false;
    </script>
    <script src="../assets/js/sessions/study-session-timer.js"></script>
    <script src="../assets/js/sessions/fetch_session_info.js"></script>
</body>

</html>