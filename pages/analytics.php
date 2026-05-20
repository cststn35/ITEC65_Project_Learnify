<?php include_once("../actions/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Analytics";
        include_once("../components/topsidebar.php")
            ?>
        <main
            class="bg-gray-200 col-start-2 p-4 md:p-6 lg:px-30 max-h-[calc(100dvh-60px)] flex flex-col overflow-scroll">
            <h1 class="text-3xl font-semibold">Analytics</h1>
            <!-- today/semester overview -->
            <div class="space-y-10">
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Today/Semester Overview</h1>
                    <div class="flex flex-col md:flex-row w-full justify-between gap-4">
                        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full">
                            <div class="flex items-center gap-3">
                                <span class="bg-blue-500 p-2 flex items-center rounded-lg"><i
                                        class='bx bx-time text-2xl text-white'></i></span>
                                <span class="text-slate-600 font-semibold text-sm">Study Time</span>
                            </div>
                            <div class="text-2xl study-time-hours">52 Hours</div>
                        </div>
                        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full">
                            <div class="flex items-center gap-3">
                                <span class="bg-green-500 p-2 flex items-center rounded-lg"><i
                                        class='bx bx-trophy text-2xl text-white'></i></span>
                                <span class="text-slate-600 font-semibold text-sm">Quiz Average</span>
                            </div>
                            <div class="text-2xl quiz-average">82%</div>
                        </div>
                        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full">
                            <div class="flex items-center gap-3">
                                <span class="bg-violet-500 p-2 flex items-center rounded-lg"><i
                                        class='bx bx-check-circle text-2xl text-white'></i></span>
                                <span class="text-slate-600 font-semibold text-sm">Tasks Done</span>
                            </div>
                            <div class="text-2xl tasks-done">36/42</div>
                        </div>
                        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full">
                            <div class="flex items-center gap-3">
                                <span class="bg-orange-500 p-2 flex items-center rounded-lg"><i
                                        class='bx bxs-hot text-2xl text-white'></i></span>
                                <span class="text-slate-600 font-semibold text-sm">Streak</span>
                            </div>
                            <div class="text-2xl streak-count">8 Days</div>
                        </div>
                    </div>
                </div>
                <!-- study analytics -->
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Study Analytics</h1>
                    <div class="w-full grid md:grid-cols-2 gap-4">
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">Study Trend</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-full h-64 p-4">
                                <canvas id="studyTrendChart"></canvas>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80 ">
                            <h1 class="font-semibold text-sm">Study By Subject</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-full h-64 p-4">
                                <canvas id="studySubjectChart"></canvas>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">Peak Study Hours</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-full h-64 p-4">
                                <canvas id="peakStudyChart"></canvas>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">Study Consistency Score/Goal Achievement Rate</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-60 aspect-square mx-auto">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">

                                    <!-- background -->
                                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-200"
                                        stroke-width="3" />

                                    <!-- progress -->
                                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-blue-500" stroke-width="3"
                                        stroke-linecap="round" stroke-dasharray="100.53" stroke-dashoffset="50.27"
                                        id="consistencyProgress" />
                                </svg>

                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold" id="percentage">50%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- productivity -->
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Productivity</h1>
                    <div class="w-full flex flex-col md:flex-row gap-4">
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">Planned vs. Actual Study Time</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-full h-64 p-4">
                                <canvas id="plannedActualChart"></canvas>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80 ">
                            <h1 class="font-semibold text-sm">Session Completion Breakdown</h1>
                            <!-- chart wrapper -->
                            <div class="relative w-full h-64 p-4">
                                <canvas id="sessionCompletionChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 w-full h-80">
                            <h1 class="font-semibold text-sm mb-10">Task Completion + On-Time Rate</h1>
                            <div class="flex flex-col items-center flex-1 gap-10">
                                <div class="w-full">
                                    <h1 class="w-full font-semibold text-xs underline">Tasks Done On Time</h1>
                                    <div
                                        class="bg-gray-200 rounded-full w-full h-2 max-w-4xl mx-auto mt-12 dark:bg-neutral-700">
                                        <div class="on-time-percentage h-full flex items-center justify-center rounded-full bg-blue-600 relative dark:bg-blue-500"
                                            style="width: 50%;">
                                            <div
                                                class="on-time absolute text-xs -right-4 bg-blue-600 text-white font-semibold px-1.5 min-w-[40px] min-h-[24px] -top-9 rounded flex items-center justify-center before:w-4 before:h-4 before:rotate-45 before:bg-blue-600 before:z-[-1] before:absolute before:-bottom-0.5 dark:bg-blue-500 dark:before:bg-blue-500">
                                                50%</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <h1 class="w-full font-semibold text-xs underline">Tasks Done Late</h1>
                                    <div
                                        class="bg-gray-200 rounded-full w-full h-2 max-w-4xl mx-auto mt-12 dark:bg-neutral-700">
                                        <div class="late-percentage h-full flex items-center justify-center rounded-full bg-blue-600 relative dark:bg-blue-500"
                                            style="width: 50%;">
                                            <div
                                                class="late absolute text-xs -right-6 bg-blue-600 text-white font-semibold px-1.5 min-w-[40px] min-h-[24px] -top-9 rounded flex items-center justify-center before:w-4 before:h-4 before:rotate-45 before:bg-blue-600 before:z-[-1] before:absolute before:-bottom-0.5 dark:bg-blue-500 dark:before:bg-blue-500">
                                                50%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- academic performance -->
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Academic Performance</h1>
                    <div class="w-full flex flex-col md:flex-row gap-4">
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">Quiz Trend</h1>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80 ">
                            <h1 class="font-semibold text-sm">Subject Mastery</h1>
                        </div>
                    </div>
                    <div
                        class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80 mt-4">
                        <h1 class="font-semibold text-sm">Study Time vs. Quiz Score</h1>
                    </div>
                </div>
                <!-- gamification -->
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Gamification</h1>
                    <div class="w-full flex flex-col md:flex-row gap-4">
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80">
                            <h1 class="font-semibold text-sm">XP Growth</h1>
                        </div>
                        <div
                            class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 space-y-3 w-full h-80 ">
                            <h1 class="font-semibold text-sm">XP Source Breakdown</h1>
                        </div>
                    </div>
                </div>
                <!-- smart insights -->
                <div class="space-y-2">
                    <h1 class="text-xl font-semibold">Smart Insights</h1>
                    <div class="w-full bg-blue-100 p-5 rounded-md border border-blue-300 flex gap-2">
                        <span class="flex items-center"><i class='bx bx-trending-up text-2xl text-blue-500'></i></span>
                        You study most consistently on weekdays
                    </div>
                </div>
            </div>



        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
    </script>
    <script src="../assets/js/analytics/analytics-chart.js"></script>
</body>

</html>