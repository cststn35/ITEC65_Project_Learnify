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
        <main class="bg-gray-200 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-hidden">
            <!-- headings and buttons -->
            <div class="flex justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Study Sessions</h1>
                    <div class="text-slate-500">Track and manage your study sessions</div>
                </div>
                <button type="button"
                    class="max-h-fit md:max-h-full px-3.5 py-2 text-white text-sm font-semibold cursor-pointer bg-[#333] hover:bg-[#222] border border-[#333] rounded-md transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#444] flex items-center gap-2"
                    id="openModal">
                    <i class='bx bx-play text-xl'></i>
                    <span class="text-sm md:text-base">Start New Session</span>
                </button>
            </div>
            <!-- statistics overview -->
            <div class="flex flex-col md:flex-row mt-6 gap-4">
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Total Sessions</span>
                        <span><i class='bx bx-book text-blue-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        5
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Total Time</span>
                        <span><i class='bx bx-time text-green-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        4h 33m
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Average Quiz Score</span>
                        <span><i class='bx bx-trophy text-yellow-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        83%
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Total XP</span>
                        <span><i class='bx bx-book text-blue-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        166
                    </span>
                </div>
            </div>
            <!-- table -->
            <div class="flex-1 min-h-0 md:px-8 mt-6 overflow-y-auto">
                <div class="max-w-7xl mx-auto md:border md:border-slate-300 rounded-md overflow-x-auto">
                    <table class="w-full">
                        <thead
                            class="hidden md:table-header-group text-slate-900 text-left text-sm font-semibold border-b border-slate-300 whitespace-nowrap">
                            <tr class="bg-slate-50">
                                <th scope="col" class="px-4 py-3.5">Date</th>
                                <th scope="col" class="px-4 py-3.5">Title</th>
                                <th scope="col" class="px-4 py-3.5">Task</th>
                                <th scope="col" class="px-4 py-3.5">Subject</th>
                                <th scope="col" class="px-4 py-3.5">Progress</th>
                                <th scope="col" class="px-4 py-3.5">Duration</th>
                                <th scope="col" class="px-4 py-3.5">Pause</th>
                                <th scope="col" class="px-4 py-3.5">Quiz</th>
                                <th scope="col" class="px-4 py-3.5">XP</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y divide-slate-200">
                            <tr
                                class="hover:bg-slate-50 border md:border-0 md:border-b border-slate-300 even:bg-slate-100 block rounded-2xl mb-4 md:mb-0 md:table-row md:rounded-none">
                                <td
                                    class="px-4 py-4 font-medium text-slate-900 whitespace-nowrap flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Date</span>
                                    <span>
                                        <span><i class='bx bxs-calendar'></i></span>
                                        <span>May 10</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Title</span>
                                    <span>React Hooks</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Task</span>
                                    <span>Study Math Chapter 3</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Subject</span>
                                    <span>Web Development</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex gap-20 md:table-cell">
                                    <span class="md:hidden text-black font-bold">Progress</span>
                                    <span class="w-full">
                                        <div class="text-right md:text-left">91%</div>
                                        <div class="bg-slate-300 rounded-md h-2 w-full">
                                            <div class="bg-purple-700 h-2 rounded-md w-[90%]"></div>
                                        </div>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Duration</span>
                                    <span>
                                        <span><i class='bx bx-time'></i></span>
                                        <span>3m</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Pause</span>
                                    <span>60s</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Quiz</span>
                                    <span>60%</span>
                                </td>
                                <td class="px-4 py-4 text-yellow-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">XP</span>
                                    <span>+31</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Status</span>
                                    <span
                                        class="px-2 py-1 rounded-xl font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">Completed</span>
                                </td>
                            </tr>
                            <tr
                                class="hover:bg-slate-50 border md:border-0 md:border-b border-slate-300 even:bg-slate-100 block rounded-2xl mb-4 md:mb-0 md:table-row md:rounded-none">
                                <td
                                    class="px-4 py-4 font-medium text-slate-900 whitespace-nowrap flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Date</span>
                                    <span>
                                        <span><i class='bx bxs-calendar'></i></span>
                                        <span>May 10</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Title</span>
                                    <span>React Hooks</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Task</span>
                                    <span>Study Math Chapter 3</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Subject</span>
                                    <span>Web Development</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex gap-20 md:table-cell">
                                    <span class="md:hidden text-black font-bold">Progress</span>
                                    <span class="w-full">
                                        <div class="text-right md:text-left">91%</div>
                                        <div class="bg-slate-300 rounded-md h-2 w-full">
                                            <div class="bg-purple-700 h-2 rounded-md w-[90%]"></div>
                                        </div>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Duration</span>
                                    <span>
                                        <span><i class='bx bx-time'></i></span>
                                        <span>3m</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Pause</span>
                                    <span>60s</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Quiz</span>
                                    <span>60%</span>
                                </td>
                                <td class="px-4 py-4 text-yellow-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">XP</span>
                                    <span>+31</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Status</span>
                                    <span
                                        class="px-2 py-1 rounded-xl font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">Completed</span>
                                </td>
                            </tr>
                            <tr
                                class="hover:bg-slate-50 border md:border-0 md:border-b border-slate-300 even:bg-slate-100 block rounded-2xl mb-4 md:mb-0 md:table-row md:rounded-none">
                                <td
                                    class="px-4 py-4 font-medium text-slate-900 whitespace-nowrap flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Date</span>
                                    <span>
                                        <span><i class='bx bxs-calendar'></i></span>
                                        <span>May 10</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Title</span>
                                    <span>React Hooks</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Task</span>
                                    <span>Study Math Chapter 3</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Subject</span>
                                    <span>Web Development</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex gap-20 md:table-cell">
                                    <span class="md:hidden text-black font-bold">Progress</span>
                                    <span class="w-full">
                                        <div class="text-right md:text-left">91%</div>
                                        <div class="bg-slate-300 rounded-md h-2 w-full">
                                            <div class="bg-purple-700 h-2 rounded-md w-[90%]"></div>
                                        </div>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Duration</span>
                                    <span>
                                        <span><i class='bx bx-time'></i></span>
                                        <span>3m</span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Pause</span>
                                    <span>60s</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Quiz</span>
                                    <span>60%</span>
                                </td>
                                <td class="px-4 py-4 text-yellow-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">XP</span>
                                    <span>+31</span>
                                </td>
                                <td class="px-4 py-4 text-slate-500 flex justify-between md:table-cell">
                                    <span class="md:hidden text-black font-bold">Status</span>
                                    <span
                                        class="px-2 py-1 rounded-xl font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">Completed</span>
                                </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>



        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
    </script>
    <script src="../assets/js/courses/add_course_modal.js"></script>
    <script src="../assets/js/courses/fetch_courses.js"></script>
    <script src="../assets/js/courses/edit_course.js"></script>
    <script src="../assets/js/courses/delete_course.js"></script>
</body>

</html>