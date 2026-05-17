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
            <!-- add study session modal overlay -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="modalOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] transition-all opacity-0 pointer-events-none scale-95">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="task-form-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-play text-2xl'></i><span>Start Study Session</span>
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

                    <form id="session-form">
                        <div class="my-6 space-y-6">
                            <div class="titleInput">
                                <label for="title" class="mb-2 text-slate-900 font-medium text-base inline-block">Title
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="text" id="title" name="title" placeholder="e.g. Deep Dive Into React Hooks"
                                    required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="taskInput">
                                <label for="description"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Task
                                    <span class="text-gray-500 font-bold text-xs">(optional)</span></label>
                                <select name="task" id="tasks"
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select a task (optional)</option>
                                    <option value="N/A">N/A</option>
                                </select>
                            </div>
                            <div class="subjectInput">
                                <label for="subject"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Subject
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select name="subject" id="subject" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select a subject</option>
                                </select>
                            </div>
                            <div class="timeInput">
                                <label for="time" class="mb-2 text-slate-900 font-medium text-base inline-block">Planned
                                    Duration (in minutes)
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="number" id="time" name="time" placeholder="e.g. 10" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="questionInput">
                                <label for="time" class="mb-2 text-slate-900 font-medium text-base inline-block">Do you
                                    want to take a quiz after the session?
                                    <span class="text-red-500 font-bold">*</span></label>

                                <div class="space-y-2">
                                    <div class="answer no-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBorder('.no-answer')">
                                        <div
                                            class="border-blue-400 rounded-full w-4 h-4 flex items-center justify-center bg-black">
                                            <div class="rounded-full w-2 h-2 bg-blue-400 hidden"></div>
                                        </div>
                                        <span>No</span>
                                    </div>

                                    <div class="answer yes-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBorder('.yes-answer')">
                                        <div
                                            class="border-blue-400 rounded-full w-4 h-4 flex items-center justify-center bg-black">
                                            <div class="rounded-full w-2 h-2 bg-blue-400 hidden"></div>
                                        </div>
                                        <span>Yes</span>
                                    </div>

                                    <div class="answer later-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBorder('.later-answer')">
                                        <div
                                            class="border-blue-400 rounded-full w-4 h-4 flex items-center justify-center bg-black">
                                            <div class="rounded-full w-2 h-2 bg-blue-400 hidden"></div>
                                        </div>
                                        <span>Ask me after session</span>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="uploadInput w-full rounded-md bg-blue-100 border border-blue-200 p-5 space-y-3 hidden">
                                <div>
                                    <span class="font-semibold">Number of Questions</span>
                                    <div class="flex gap-3">
                                        <button
                                            class="number five px-4 py-2 border-2 border-slate-30g0 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                            type="button" onclick="chooseNumber('five')">5</button>
                                        <button
                                            class="number ten px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                            type="button" onclick="chooseNumber('ten')">10</button>
                                        <button
                                            class="number fifteen px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                            type="button" onclick="chooseNumber('fifteen')">15</button>
                                        <!-- <button
                                                class="number twenty px-4 py-2 border-2 border-slate-300 rounded-md bg-white flex-1 font-bold transition ease-in 0.25s hover:border-blue-400"
                                                type="button" onclick="chooseNumber('twenty')">20</button> -->
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                            <button type="button" id="cancelBtn"
                                class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Cancel</button>
                            <button type="submit" id="session-submit"
                                class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 disabled:opacity-50 disabled:bg-blue-400 disabled:cursor-not-allowed">
                                Create Session</button>
                        </div>
                    </form>
                </div>
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
            <div class="flex-1 min-h-0 mt-6 overflow-y-auto">
                <div class="w-full mx-auto md:border md:border-slate-300 rounded-md overflow-x-auto">
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
    <script src="../assets/js/sessions/add_session_modal.js"></script>
    <script src="../assets/js/tasks/fetch_subjects.js"></script>
    <script src="../assets/js/sessions/fetch_tasks_sessions.js"></script>
</body>

</html>