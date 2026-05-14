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
            <div class="w-full max-w-7xl mx-auto space-y-5">
                <!-- header -->
                <div class="bg-white w-full flex justify-center items-center p-7 rounded-lg shadow-md">
                    <span class="font-bold text-xl md:text-2xl">Demistifying React.js Hooks</span>
                </div>
                <!-- task card -->
                <div class="bg-white w-full flex justify-evenly items-center p-3 md:p-7 rounded-lg shadow-md">
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs md:text-sm text-slate-600">Subject</div>
                        <div>Physics</div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs  md:text-sm text-slate-600">Task</div>
                        <div>General Study</div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs  md:text-sm text-slate-600">Goal Duration</div>
                        <div>15 minutes</div>
                    </div>
                    <div class="flex flex-col items-center text-xs md:text-base">
                        <div class="text-xs md:text-sm text-slate-600">Status</div>
                        <div>Keep Going!</div>
                    </div>
                </div>
                <!-- timer itself -->
                <div
                    class="bg-gradient-to-r from-[#0A2A88] to-[#59CDE9] w-full h-140 rounded-lg flex justify-center items-center flex-col space-y-1">
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
                                15 minutes
                            </div>
                        </div>
                        <div>|</div>
                        <div class="flex justify-center gap-2">
                            <div>
                                <i class='bx bx-time'></i>
                                <span>Remaining:</span>
                            </div>
                            <div>
                                15 minutes
                            </div>
                        </div>
                        <div>|</div>
                        <div>14% Complete</div>
                    </div>
                    <div class="resume bg-white/10 backdrop-blur-md rounded-xl p-3 text-slate-200 hidden">
                        ▶️Take your break. Resume when ready
                    </div>
                </div>
                <div class="bg-white shadow-md rounded-md p-6 space-y-1">
                    <h1 class="font-bold text-lg">Session Progress</h1>
                    <div class="flex justify-evenly gap-4">
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-slate-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Planned Duration</div>
                            <div class="text-lg">15 mins</div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-green-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Studied Minutes</div>
                            <div class="text-lg">15 mins</div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-yellow-50 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Paused Minutes</div>
                            <div class="text-lg">15 mins</div>
                        </div>
                        <div
                            class="flex flex-col justify-center items-center rounded-md bg-gray-100 gap-2 px-8 py-4 flex-1">
                            <div class="text-sm text-slate-700">Progress</div>
                            <div class="text-lg">50%</div>
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
                        onclick="resetTimer()">
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
                                <div class="space-x-2"><i class='bx bxs-file-doc'></i><span>Lesson 10. Digital Self
                                        (highlighted).pdf</span>
                                </div>
                                <div class="space-x-2"><i class='bx bx-check-square'></i><span>Quiz Prepared ✔</span>
                                </div>
                                <div><span>10 questions generated</span>
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

        const progressCircle =
            document.querySelector(".progress-circle");

        const timeDisplay =
            document.getElementById("time");

        const radius = 200;
        const circumference =
            2 * Math.PI * radius;

        progressCircle.style.strokeDasharray =
            circumference;

        let elapsed = 0;
        let timer = null;
        let isRunning = false;

        const maxSeconds = 10;
        // full circle every 60 seconds

        function updateTimer() {
            elapsed++;

            const minutes =
                Math.floor(elapsed / 60);

            const seconds =
                elapsed % 60;

            timeDisplay.textContent =
                `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;

            const progress =
                (elapsed % maxSeconds) / maxSeconds;

            const offset =
                circumference -
                (progress * circumference);

            progressCircle.style.strokeDashoffset =
                offset;
        }

        function startTimer() {
            if (isRunning) return;

            isRunning = true;

            timer = setInterval(
                updateTimer,
                1000
            );
        }

        function pauseTimer() {
            clearInterval(timer);
            isRunning = false;
        }

        function resetTimer() {
            clearInterval(timer);
            isRunning = false;

            elapsed = 0;

            timeDisplay.textContent =
                "00:00";

            progressCircle.style.strokeDashoffset =
                circumference;
        }
    </script>
    <script src="../assets/js/sessions/add_session_modal.js"></script>
</body>

</html>