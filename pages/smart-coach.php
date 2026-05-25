<?php include_once("../actions/auth.php"); ?>
<?php require_once("../config/runETL.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Smart Coach";
        include_once("../components/topsidebar.php")
            ?>
        <main
            class="bg-slate-100 col-start-2 p-4 md:p-6 lg:px-30 max-h-[calc(100dvh-60px)] flex flex-col overflow-y-scroll space-y-6">
            <div class="flex w-full gap-5">
                <div><span class="bg-purple-800 rounded-2xl p-4 flex items-center justify-center"><i
                            class='bx bx-brain text-3xl text-white'></i></span></div>
                <div class="flex justify-center flex-col flex-1">
                    <div class="font-bold text-xl">Smart Coach</div>
                    <div>Personalized recommendations based on your study behavior</div>
                </div>
                <div class="flex items-center">
                    <span class="bg-green-200 rounded-3xl p-2 border border-green-300">2nd Semester S.Y 2025-2026</span>
                </div>
            </div>
            <!-- KPI -->
            <div class="flex gap-4">
                <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 w-full space-y-3">
                    <div class="flex items-center gap-2">
                        <span><i class='bx bxs-bar-chart-alt-2'></i></span>
                        <span class="text-xs font-bold">STUDY HEALTH</span>
                    </div>
                    <div>
                        <div class="relative size-40 mx-auto">
                            <svg class="-rotate-90 size-full" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <!-- Background Circle (Gauge) -->
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-200" stroke-width="1.5"
                                    stroke-linecap="round"></circle>
                                <!-- Gauge Progress -->
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-blue-500 health-progress"
                                    stroke-width="1.5" stroke-dasharray="100.53" stroke-dashoffset="50.27"
                                    stroke-linecap="round"></circle>
                            </svg>
                            <!-- Value Text -->
                            <div
                                class="absolute top-1/2 inset-s-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span class="text-4xl font-bold text-primary health-progress-text">50</span>
                                <span class="text-primary block">/100</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm rounded-lg p-4 sm:p-6 w-full space-y-3">
                    <div class="flex items-center gap-2">
                        <span><i class='bx bxs-calendar'></i></span>
                        <span class="text-xs font-bold">CONSISTENCY SCORE</span>
                    </div>
                    <div>
                        <div class="relative size-40 mx-auto">
                            <svg class="-rotate-90 size-full" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                <!-- Background Circle (Gauge) -->
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-slate-200" stroke-width="1.5"
                                    stroke-linecap="round"></circle>
                                <!-- Gauge Progress -->
                                <circle cx="18" cy="18" r="16" fill="none" class="stroke-red-500 consistency-progress"
                                    stroke-width="1.5" stroke-dasharray="100.53" stroke-dashoffset="50.27"
                                    stroke-linecap="round"></circle>
                            </svg>
                            <!-- Value Text -->
                            <div
                                class="absolute top-1/2 inset-s-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                <span class="text-4xl font-bold text-primary consistency-progress-text">50</span>
                                <span class="text-primary block">/100</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- priority insights -->
            <div class="flex w-full gap-5">
                <div><span class="bg-purple-800 rounded-2xl p-4 flex items-center justify-center"><i
                            class='fa-solid fa-bullseye text-3xl text-white'></i></span></div>
                <div class="flex justify-center flex-col flex-1">
                    <div class="font-bold text-xl">Priority Insights</div>
                    <div>Top recommendations based on your recent activity</div>
                </div>
            </div>
            <!-- priority insights cards -->
            <div class="space-y-4 priority-cards">
                <!-- study consistency -->
                <!-- academic stability -->
                <!-- task management stability -->
            </div>
            <!-- productivity coach -->
            <div class="flex w-full gap-5">
                <div><span class="bg-purple-800 rounded-2xl p-4 flex items-center justify-center"><i
                            class='bx bx-alarm text-3xl text-white'></i></span></div>
                <div class="flex justify-center flex-col flex-1">
                    <div class="font-bold text-xl">Productivity Coach</div>
                    <div>Gauges your productivity metrics based on study session behavior</div>
                </div>
            </div>
            <!-- productivity coach cards -->
            <div class="space-y-4 focusCont">
                <div class="flex gap-4 proCoachCont">
                    <!-- study pattern -->
                    <!-- session stability -->
                </div>
                <!-- focus window -->
            </div>
            <!-- academic coach-->
            <div class="flex w-full gap-5">
                <div><span class="bg-purple-800 rounded-2xl p-4 flex items-center justify-center"><i
                            class='bx bx-book-open text-3xl text-white'></i></span></div>
                <div class="flex justify-center flex-col flex-1">
                    <div class="font-bold text-xl">Academic Coach</div>
                    <div>Gauges your academic metrics based on quiz performance</div>
                </div>
            </div>
            <!-- academic coach cards -->
            <div class="flex gap-4 acadCoachCont">
                <!-- subject to improve -->
                <!-- strongest subject -->
            </div>

            <!-- task coach-->
            <div class="flex w-full gap-5">
                <div><span class="bg-purple-800 rounded-2xl p-4 flex items-center justify-center"><i
                            class='bx bx-check-square text-3xl text-white'></i></span></div>
                <div class="flex justify-center flex-col flex-1">
                    <div class="font-bold text-xl">Streak and Daily Progress Coach</div>
                    <div>Gauges your streak and daily progress</div>
                </div>
            </div>
            <!-- streak and daily progress -->
            <div class="streak-cont flex gap-4">

            </div>
        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
    </script>
    <script src="../assets/js/smart_coach/smart-coach.js"></script>
</body>

</html>