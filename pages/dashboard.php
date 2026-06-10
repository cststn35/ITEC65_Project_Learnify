<?php
include_once("../actions/auth.php");
date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Dashboard";
        include_once("../components/topsidebar.php")
            ?>
        <main
            class="bg-slate-100 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-y-auto space-y-6">
            <!-- add/edit class modal -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="modalOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] opacity-0 pointer-events-none scale-95 transition-all">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="schedule-form-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bxs-plus-square text-2xl'></i><span>Add Class Schedule</span>
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

                    <form id="schedule-form">
                        <div class="my-6 space-y-6">
                            <div class="subjectInput">
                                <label for="subject"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Subject
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select name="subject" id="subject" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select a subject</option>
                                </select>
                                <span
                                    class="create-subject text-sm flex justify-end underline text-blue-600 cursor-pointer">Create
                                    Subject</span>
                            </div>
                            <!-- inline subject addition form -->
                            <div id="course-form-container" class="hidden">
                                <div class="my-6 space-y-6">
                                    <div class="iconColor">
                                        <label for="title"
                                            class="mb-2 text-slate-900 font-medium text-base inline-block">Icon
                                            Color
                                            <span class="text-red-500 font-bold">*</span></label>
                                        <div class="flex flex-wrap justify-center items-center gap-3">
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="blue-700" onclick="changeBorder('blue-700')" type="button"><i
                                                    class='bx bxs-book text-2xl text-blue-700'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="red-700" onclick="changeBorder('red-700')" type="button"><i
                                                    class='bx bxs-book text-2xl text-red-700'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="orange-500" onclick="changeBorder('orange-500')" type="button"><i
                                                    class='bx bxs-book text-2xl text-orange-500'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="yellow-400" onclick="changeBorder('yellow-400')" type="button"><i
                                                    class='bx bxs-book text-2xl text-yellow-400'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="green-700" onclick="changeBorder('green-700')" type="button"><i
                                                    class='bx bxs-book text-2xl text-green-700'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="purple-500" onclick="changeBorder('purple-500')" type="button"><i
                                                    class='bx bxs-book text-2xl text-purple-500'></i></button>
                                            <button
                                                class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                                id="violet-700" onclick="changeBorder('violet-700')" type="button"><i
                                                    class='bx bxs-book text-2xl text-violet-700'></i></button>
                                        </div>
                                    </div>
                                    <div class="courseInput">
                                        <label for="course"
                                            class="mb-2 text-slate-900 font-medium text-base inline-block">Subject/Course
                                            Title
                                            <span class="text-red-500 font-bold">*</span>
                                        </label>
                                        <input type="text" id="course" name="course"
                                            placeholder="e.g. Web Systems and Technologies"
                                            class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                                    </div>
                                    <div class="descriptionInput">
                                        <label for="description"
                                            class="mb-2 text-slate-900 font-medium text-base inline-block">Short
                                            Description
                                            <span class="text-gray-500 font-bold text-xs">(optional)</span></label>
                                        <input type="text" id="description-sub" name="description"
                                            placeholder="Brief description of the course..."
                                            class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                                    </div>
                                </div>

                                <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                                    <button type="button" id="cancelBtn2"
                                        class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                        Cancel</button>
                                    <button type="button" id="course-submit"
                                        class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                        Create Subject</button>
                                </div>
                            </div>
                            <div class="dayInput">
                                <label for="day" class="mb-2 text-slate-900 font-medium text-base inline-block">Day
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select name="day" id="day" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select a day</option>
                                    <option value="1">Sunday</option>
                                    <option value="2">Monday</option>
                                    <option value="3">Tue</option>
                                    <option value="4">Wed</option>
                                    <option value="5">Thu</option>
                                    <option value="6">Fry</option>
                                    <option value="7">Sa</option>
                                </select>
                            </div>
                            <div class="startTimeInput">
                                <label for="startTime"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Start Time
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="time" id="startTime" name="startTime" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="endTimeInput">
                                <label for="endTime" class="mb-2 text-slate-900 font-medium text-base inline-block">End
                                    Time
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="time" id="endTime" name="endTime" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="teacherInput">
                                <label for="room" class="mb-2 text-slate-900 font-medium text-base inline-block">Teacher
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="text" id="teacher" name="teacher" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="roomInput">
                                <label for="room" class="mb-2 text-slate-900 font-medium text-base inline-block">Room
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="text" id="room" name="room" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                        </div>

                        <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                            <button type="button" id="cancelBtn"
                                class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Cancel</button>
                            <button id="delSched" type="button"
                                class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 hidden">
                                Delete Schedule</button>
                            <button type="submit" id="schedule-submit"
                                class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Save Schedule</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- greetings -->
            <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 w-full space-y-3">
                <h1 class="font-semibold text-2xl">👋 GOOD
                    <?= ($h = date('G')) < 12 ? 'MORNING' : ($h < 18 ? 'AFTERNOON' : 'EVENING') ?>, <span
                        id="user-name">Juan</span>
                </h1>
                <p class="text-slate-700 text-md" id="rem-minutes">You are {x} minutes away from today's study goal</p>
                <p class="text-slate-500 text-sm" id="semester-status">Semester: {1st Semester} {S.Y 2025-2026}</p>
            </div>
            <!-- pendings and streak -->
            <div class="flex gap-6 flex-col md:flex-row">
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 w-full space-y-2">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="p-2 rounded-lg bg-blue-200 flex shadow-sm"><i
                                class="fa-regular fa-clock text-2xl text-blue-800"></i></span>
                        <span class="font-semibold text-lg">TODAY STUDY</span>
                    </div>
                    <div class="text-4xl font-bold" id="today-study-text">
                        98 / 120 mins
                    </div>
                    <div class="text-slate-500" id="today-study-progress">82% complete</div>
                    <div class="font-semibold" id="today-study-message-greet">Good Progress</div>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 w-full space-y-2">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="p-2 rounded-lg bg-orange-200 flex shadow-sm"><i
                                class="fa-solid fa-fire text-2xl text-orange-800"></i></span>
                        <span class="font-semibold text-lg">CURRENT STREAK</span>
                    </div>
                    <div class="text-4xl font-bold" id="current-streak-days">
                        6 Days
                    </div>
                    <div class="text-slate-500">Longest: <span id="longest-streak">14 Days</span></div>
                    <div class="font-semibold" id="streak-message">Keep It Going</div>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm rounded-2xl p-4 sm:p-6 w-full space-y-2">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="p-2 rounded-lg bg-violet-200 flex shadow-sm"><i
                                class="bx bx-task text-2xl text-violet-800"></i></span>
                        <span class="font-semibold text-lg">PENDING TASKS</span>
                    </div>
                    <div class="text-4xl font-bold" id="remaining-tasks">
                        9 remaining
                    </div>
                    <div class="text-slate-800" id="high-priority-count">3 High Priority</div>
                    <div class="font-semibold" id="priority-message">Needs Attention</div>
                </div>
            </div>
            <!-- schedule at a glance -->
            <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-4 sm:p-6 w-full flex flex-col">
                <div class="flex justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <span class="p-2.5 rounded-xl bg-amber-100 flex shadow-sm">
                            <i class="bx bx-calendar text-2xl text-amber-600"></i>
                        </span>

                        <div>
                            <h2 class="font-semibold text-lg text-slate-800 tracking-tight">
                                CLASS SCHEDULE AT A GLANCE
                            </h2>
                            <p class="text-sm text-slate-500">
                                Your weekly classes overview
                            </p>
                        </div>
                    </div>
                    <button class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl
                    shadow-sm hover:bg-blue-700 hover:shadow-md
                    active:scale-[0.98] transition-all duration-200
                    flex items-center gap-2 h-fit" id="openModal">
                        <i class="bx bx-book"></i>
                        Add A Class
                    </button>

                </div>

                <div
                    class="rounded-2xl bg-slate-50 border border-slate-200 flex flex-1 flex-col md:flex-row p-4 gap-4 text-center overflow-x-auto min-h-100 schedule-cont relative">
                    <!-- SUNDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Sunday
                        </div>
                        <div class="flex flex-col gap-3 sunday araw">
                        </div>
                    </div>
                    <!-- MONDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Monday
                        </div>
                        <div class="flex flex-col gap-3 monday araw">
                        </div>
                    </div>
                    <!-- TUESDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Tuesday
                        </div>
                        <div class="flex flex-col gap-3 tuesday araw">
                        </div>
                    </div>
                    <!-- WEDNESDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Wednesday
                        </div>
                        <div class="flex flex-col gap-3 wednesday araw">
                        </div>
                    </div>
                    <!-- THURSDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Thursday
                        </div>
                        <div class="flex flex-col gap-3 thursday araw">
                        </div>
                    </div>
                    <!-- FRIDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Friday
                        </div>
                        <div class="flex flex-col gap-3 friday araw">
                        </div>
                    </div>
                    <!-- SATURDAY -->
                    <div class="flex flex-col gap-3 min-w-[180px]">
                        <div
                            class="p-3 bg-amber-50 border border-amber-100 rounded-xl text-center font-semibold text-slate-700 shadow-sm">
                            Saturday
                        </div>
                        <div class="flex flex-col gap-3 saturday araw">
                        </div>
                    </div>
                    <div class="add-class-message flex justify-center md:absolute md:top-1/2 md:left-1/2 md:-translate-x-1/2 md:-translate-y-1/2 text-2xl cursor-pointer text-slate-600"
                        onclick="openModal()">Add your class schedule here</div>
                </div>
            </div>
            <!-- start study session -->
            <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-7 w-full space-y-4 flex flex-col">
                <div class="flex items-center gap-3 mb-5">
                    <span class="p-2.5 rounded-xl bg-red-100 flex shadow-sm">
                        <i class="fa-solid fa-bullseye text-2xl text-red-600"></i>
                    </span>

                    <div>
                        <h2 class="font-semibold text-lg text-slate-800 tracking-tight">
                            TODAY'S STUDY GOAL
                        </h2>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="text-center font-bold text-5xl" id="start-study-text">98 / 120 mins</div>
                    <div class="rounded-lg w-full h-5 bg-slate-300">
                        <div class="rounded-lg h-5 bg-blue-800" id="start-study-progress-bar"></div>
                    </div>
                    <div class="text-center text-2xl font-semibold text-slate-600" id="start-study-progress">82%</div>
                </div>
                <div class="flex flex-col items-center space-y-2 text-slate-500">
                    <div id="remaining-minutes">You are only 22 minutes away from reaching today's goal</div>
                    <div>Keep going to maintain your streak and earn bonus XP</div>
                </div>
                <div class="flex justify-center">
                    <a href="study-session.php">
                        <button class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-xl
                    shadow-sm hover:bg-blue-700 hover:shadow-md
                    active:scale-[0.98] transition-all duration-200
                    flex items-center gap-2 cursor-pointer">
                            <i class="bx bx-play"></i>
                            Start Study Session
                        </button>
                    </a>
                </div>
            </div>
            <!-- upcoming tasks -->
            <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-7 w-full space-y-4 flex flex-col">
                <div class="flex items-center gap-3 mb-5">
                    <span class="p-2.5 rounded-xl bg-violet-100 flex shadow-sm">
                        <i class="bx bx-task text-2xl text-violet-600"></i>
                    </span>

                    <div>
                        <h2 class="font-semibold text-lg text-slate-800 tracking-tight">
                            UPCOMING TASKS
                        </h2>
                        <p class="text-sm text-slate-500">
                            Tasks that require immediate attention
                        </p>
                    </div>
                </div>
                <div class="space-y-3" id="tasks-container">
                    <!-- tasks here -->
                </div>
                <a href="tasks.php">
                    <div class="flex justify-end cursor-pointer text-blue-700 font-semibold">
                        <h1>View All Tasks -></h1>
                    </div>
                </a>

            </div>
            <!-- weekly study trend -->
            <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-7 w-full space-y-4 flex flex-col ">
                <div class="flex items-center gap-3 mb-5">
                    <span class="p-2.5 rounded-xl bg-violet-100 flex shadow-sm">
                        <i class="bx bx-task text-2xl text-violet-600"></i>
                    </span>
                    <div>
                        <h2 class="font-semibold text-lg text-slate-800 tracking-tight">
                            WEEKLY STUDY TREND
                        </h2>
                        <p class="text-sm text-slate-500">
                            Your recent study activity
                        </p>
                    </div>
                </div>
                <div>
                    <!-- chart wrapper -->
                    <div class="relative w-full h-64 p-4">
                        <canvas id="studyTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- smart coach preview -->
            <div class="bg-white border border-slate-200/80 shadow-sm rounded-2xl p-7 w-full space-y-4 flex flex-col ">
                <div class="flex items-center gap-3 mb-5">
                    <span class="p-2.5 rounded-xl bg-pink-100 flex shadow-sm">
                        <i class="bx bx-brain text-2xl text-pink-600"></i>
                    </span>
                    <div>
                        <h2 class="font-semibold text-lg text-slate-800 tracking-tight">
                            SMART COACH PREVIEW
                        </h2>
                        <p class="text-sm text-slate-500">
                            Personalized insights based on your recent behavior
                        </p>
                    </div>
                </div>
                <!-- priority insights cards -->
                <div class="space-y-4 priority-cards rounded-2xl bg-slate-50 border border-slate-200 p-4">
                    <!-- study consistency -->
                    <!-- academic stability -->
                    <!-- task management stability -->
                </div>
                <div class="flex justify-end cursor-pointer text-blue-700 font-semibold">
                    <a href="smart-coach.php">
                        <h1>Open Smart Coach -></h1>
                    </a>
                </div>
            </div>
        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
    </script>
    <script src="../assets/js/dashboard/fetch-dashboard.js"></script>
    <script src="../assets/js/tasks/fetch_subjects_3.js"></script>
    <script src="../assets/js/dashboard/render_schedule.js"></script>
    <script src="../assets/js/dashboard/add_schedule_modal.js"></script>
    <script src="../assets/js/dashboard/edit_schedule.js"></script>
</body>

</html>
