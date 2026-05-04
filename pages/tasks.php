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
                    class="px-3.5 py-2 text-white text-sm font-semibold cursor-pointer bg-[#333] hover:bg-[#222] border border-[#333] rounded-md transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#444] flex items-center gap-2">
                    <i class='bx bx-plus text-sm md:text-base'></i>
                    <span class="text-sm md:text-base">Add Task</span>
                </button>
            </div>

            <!-- cards -->
            <div class="cards-container flex-1 flex flex-col min-h-0">
                <div class="flex flex-col flex-1 min-h-0">
                    <div
                        class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto mt-6 p-4 sm:p-6 flex flex-col gap-3">
                        <div class="flex flex-wrap gap-3">
                            <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">All Subjects</option>
                            </select>
                            <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">All Semesters</option>
                            </select>
                            <select
                                class="px-3.5 py-2 text-slate-900 text-sm rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                <option value="">Sort by Subject</option>
                            </select>
                        </div>

                        <div class="flex flex-wrap gap-3">
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
                                Completed
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

                                <div>
                                    <span
                                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                                        Completed
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
                                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-yellow-100 text-yellow-700 border border-yellow-200">
                                        Due soon

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
                                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-red-100 text-red-700 border border-red-200">
                                        Overdue
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
                                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-gray-100 text-gray-700 border border-gray-200">
                                        Pending
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
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>