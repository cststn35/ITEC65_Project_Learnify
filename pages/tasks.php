<?php include_once("../actions/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Tasks";
        include_once("../components/topsidebar.php")
            ?>
        <main class="bg-gray-200 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-hidden">
            <div class="flex justify-between">
                <h1 class="font-bold text-2xl">TASKS</h1>
                <button type="button"
                    class="px-3.5 py-2 text-white text-sm font-semibold cursor-pointer bg-[#333] hover:bg-[#222] border border-[#333] rounded-md transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#444] flex items-center gap-2"
                    id="openModal">
                    <i class='bx bx-plus text-sm md:text-base'></i>
                    <span class="text-sm md:text-base">Add Task</span>
                </button>
            </div>

            <!-- add task overlay -->
            <div id="modalOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] hidden">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bxs-plus-square text-2xl'></i>Create Task
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

                    <form action="">
                        <div class="my-6 space-y-6">
                            <div class="titleInput">
                                <label for="title" class="mb-2 text-slate-900 font-medium text-base inline-block">Title
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="text" id="title" placeholder="e.g. Study Math Chapter 3" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="descriptionInput">
                                <label for="description"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Short
                                    Description
                                    <span class="text-gray-500 font-bold text-xs">(optional)</span></label>
                                <input type="text" id="description" placeholder="Add notes about this task"
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="subjectInput">
                                <label for="subject"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Subject
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select name="subject" id="subject" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select a subject</option>
                                    <option value="">Math</option>
                                    <option value="">Science</option>
                                </select>
                            </div>
                            <div class="deadlineInput">
                                <label for="deadline"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Deadline
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="date" id="deadline" required placeholder="Add notes about this task"
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="priorityInput">
                                <label for="priority"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Priority Level
                                    <span class="text-red-500 font-bold">*</span></label>
                                <select name="priority" id="priority" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600">
                                    <option value="" disabled hidden selected>Select priority level</option>
                                    <option value="">Low</option>
                                    <option value="">Medium</option>
                                    <option value="">High</option>
                                </select>
                            </div>
                            <div class="timeInput">
                                <label for="time"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Estimated Time To
                                    Accomplish (in minutes)
                                    <span class="text-gray-500 font-bold text-xs">(optional)</span></label>
                                <input type="number" id="time" placeholder="e.g. 10"
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                        </div>

                        <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                            <button type="button" id="cancelBtn"
                                class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Cancel</button>
                            <button type="submit"
                                class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Create Task</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- cards -->
            <div class="cards-container flex-1 flex flex-col min-h-0">
                <div class="flex flex-col flex-1 min-h-0">
                    <div
                        class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto mt-6 p-4 sm:p-6 flex flex-col gap-3">
                        <div class="flex flex-wrap gap-3 border-b border-slate-200 pb-4">
                            <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">All Subjects</option>
                            </select>
                            <!-- <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">All Semesters</option>
                            </select> -->
                            <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">Sort by Due Date</option>
                                <option value="">Sort by Priority</option>
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button class="px-4 py-2 text-sm rounded-lg transition-colors
                        bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold
                        data-[active=true]:bg-indigo-600
                        data-[active=true]:text-white
                        data-[active=true]:shadow-sm">
                                All
                            </button>
                            <button class="px-4 py-2 text-sm rounded-lg transition-colors
                        bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold
                        data-[active=true]:bg-indigo-600
                        data-[active=true]:text-white
                        data-[active=true]:shadow-sm">
                                Pending
                            </button>
                            <button class="px-4 py-2 text-sm rounded-lg transition-colors
                        bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold
                        data-[active=true]:bg-indigo-600
                        data-[active=true]:text-white
                        data-[active=true]:shadow-sm">
                                Due soon
                            </button>

                            <button class="px-4 py-2 text-sm rounded-lg transition-colors
                        bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold
                        data-[active=true]:bg-indigo-600
                        data-[active=true]:text-white
                        data-[active=true]:shadow-sm">
                                Completed
                            </button>
                            <button class="px-4 py-2 text-sm rounded-lg transition-colors
                        bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold
                        data-[active=true]:bg-indigo-600
                        data-[active=true]:text-white
                        data-[active=true]:shadow-sm">
                                Overdue
                            </button>
                        </div>
                    </div>

                    <!-- tasks -->
                    <div class="tasks-container mt-3 flex-1 overflow-y-auto flex flex-col gap-4 min-h-0   [&::-webkit-scrollbar]:w-2
                    [&::-webkit-scrollbar-track]:bg-gray-100
                    [&::-webkit-scrollbar-thumb]:bg-gray-300
                    dark:[&::-webkit-scrollbar-track]:bg-neutral-700
                    dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                        <!-- task 1 -->
                        <div
                            class="one-card bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 flex flex-col gap-3">
                            <!-- heading -->
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-2">
                                    <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <h1 class="font-bold text-xl">Study Math</h1>
                                        </div>
                                        <div class="flex gap-3 text-gray-600">
                                            <div class="flex items-center gap-1"><i
                                                    class='bx bx-book-open'></i><span>Math</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-calendar'></i><span>Due
                                                    May 10</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-time'></i><span>45
                                                    min</span></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- pills -->
                                <div>
                                    <span
                                        class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                                        Completed
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 text-xs font-medium">
                                        🔴 High
                                    </span>
                                </div>
                                <!-- buttons -->
                                <div class="flex gap-2">
                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-play text-xl'></i>
                                        <span>Start Study</span>
                                    </button>

                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-green-600 hover:bg-green-700 border border-green-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-check text-xl'></i>
                                        <span>Mark as Done</span>
                                    </button>
                                    <!-- kebab button -->
                                    <div class="relative task-card">

                                        <button type="button" data-action="dropdown-toggle" aria-haspopup="true"
                                            class="h-full px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md flex items-center gap-2 cursor-pointer bg-slate-200 border border-slate-100 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">

                                            <i class="fa-solid fa-ellipsis-vertical text-base"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <ul data-dropdown
                                            class="hidden absolute right-0 mt-2 p-2 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden">

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-edit text-xl'></i>
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-trash text-xl'></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- task 2 -->
                        <div
                            class="one-card bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 flex flex-col gap-3">
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-2">
                                    <div class="text-center text-4xl"><i class='bx bxs-book text-blue-700'></i></div>
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <h1 class="font-bold text-xl">Study Physics</h1>
                                        </div>
                                        <div class="flex gap-3 text-gray-600">
                                            <div class="flex items-center gap-1"><i
                                                    class='bx bx-book-open'></i><span>Physics</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-calendar'></i><span>Due
                                                    May 12</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-time'></i><span>46
                                                    min</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <span
                                        class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        Due soon
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-xs font-medium">
                                        🟡 Medium
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-play text-xl'></i>
                                        <span>Start Study</span>
                                    </button>

                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-green-600 hover:bg-green-700 border border-green-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-check text-xl'></i>
                                        <span>Mark as Done</span>
                                    </button>
                                    <!-- kebab button -->
                                    <div class="relative task-card">

                                        <button type="button" data-action="dropdown-toggle" aria-haspopup="true"
                                            class="h-full px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md flex items-center gap-2 cursor-pointer bg-slate-200 border border-slate-100 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">

                                            <i class="fa-solid fa-ellipsis-vertical text-base"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <ul data-dropdown
                                            class="hidden absolute right-0 mt-2 p-2 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden">

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-edit text-xl'></i>
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-trash text-xl'></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- task 3 -->
                        <div
                            class="one-card bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 flex flex-col gap-3">
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-2">
                                    <div class="text-center text-4xl"><i class='bx bxs-book text-violet-700'></i></div>
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <h1 class="font-bold text-xl">Study Arts</h1>
                                        </div>
                                        <div class="flex gap-3 text-gray-600">
                                            <div class="flex items-center gap-1"><i
                                                    class='bx bx-book-open'></i><span>Art Appreciation</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-calendar'></i><span>Due
                                                    May 14</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-time'></i><span>48
                                                    min</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <span
                                        class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-red-100 text-red-700 border border-red-200">
                                        Overdue
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium">
                                        🟢 Low
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-play text-xl'></i>
                                        <span>Start Study</span>
                                    </button>

                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-green-600 hover:bg-green-700 border border-green-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-check text-xl'></i>
                                        <span>Mark as Done</span>
                                    </button>
                                    <!-- kebab button -->
                                    <div class="relative task-card">

                                        <button type="button" data-action="dropdown-toggle" aria-haspopup="true"
                                            class="h-full px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md flex items-center gap-2 cursor-pointer bg-slate-200 border border-slate-100 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">

                                            <i class="fa-solid fa-ellipsis-vertical text-base"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <ul data-dropdown
                                            class="hidden absolute right-0 mt-2 p-2 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden">

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-edit text-xl'></i>
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-trash text-xl'></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- task 4 -->
                        <div
                            class="one-card bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 flex flex-col gap-3">
                            <div class="flex flex-col gap-3">
                                <div class="flex gap-2">
                                    <div class="text-center text-4xl"><i class='bx bxs-book text-pink-700'></i></div>
                                    <div class="flex flex-col gap-2">
                                        <div>
                                            <h1 class="font-bold text-xl">Study Database</h1>
                                        </div>
                                        <div class="flex gap-3 text-gray-600">
                                            <div class="flex items-center gap-1"><i
                                                    class='bx bx-book-open'></i><span>Database System</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-calendar'></i><span>Due
                                                    May 20</span></div>
                                            <div class="flex items-center gap-1"><i class='bx bx-time'></i><span>55
                                                    min</span></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <span
                                        class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-gray-100 text-gray-700 border border-gray-200">
                                        Pending
                                    </span>
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-medium">
                                        🟢 Low
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-play text-xl'></i>
                                        <span>Start Study</span>
                                    </button>

                                    <button
                                        class="px-3.5 py-2 text-white text-sm font-semibold bg-green-600 hover:bg-green-700 border border-green-600 rounded-md transition-colors flex gap-2">
                                        <i class='bx bx-check text-xl'></i>
                                        <span>Mark as Done</span>
                                    </button>
                                    <!-- kebab button -->
                                    <div class="relative task-card">

                                        <button type="button" data-action="dropdown-toggle" aria-haspopup="true"
                                            class="h-full px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md flex items-center gap-2 cursor-pointer bg-slate-200 border border-slate-100 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">

                                            <i class="fa-solid fa-ellipsis-vertical text-base"></i>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <ul data-dropdown
                                            class="hidden absolute right-0 mt-2 p-2 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden">

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-edit text-xl'></i>
                                                    Edit
                                                </a>
                                            </li>

                                            <li>
                                                <a href="#"
                                                    class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100">
                                                    <i class='bx bx-trash text-xl'></i>
                                                    Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="action-modals"></div>

    <script src="../assets/js/tasks.js"></script>
</body>

</html>