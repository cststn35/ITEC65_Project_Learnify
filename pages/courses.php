<?php include_once("../actions/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Courses";
        include_once("../components/topsidebar.php")
            ?>
        <main class="bg-gray-200 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-hidden">
            <div class="flex justify-between">
                <h1 class="font-bold text-2xl">Subjects/Courses</h1>
            </div>

            <!-- add subject overlay -->
            <div id="modalOverlay"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] hidden">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bxs-book text-2xl'></i>Add New Subject
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
                            <div class="iconColor">
                                <label for="title" class="mb-2 text-slate-900 font-medium text-base inline-block">Icon
                                    Color
                                    <span class="text-red-500 font-bold">*</span></label>
                                <div class="flex flex-wrap justify-center items-center gap-3">
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="blue" onclick="changeBorder('blue')" type="button"><i
                                            class='bx bxs-book text-2xl text-blue-700'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="red" onclick="changeBorder('red')" type="button"><i
                                            class='bx bxs-book text-2xl text-red-700'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="orange" onclick="changeBorder('orange')" type="button"><i
                                            class='bx bxs-book text-2xl text-orange-500'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="yellow" onclick="changeBorder('yellow')" type="button"><i
                                            class='bx bxs-book text-2xl text-yellow-400'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="green" onclick="changeBorder('green')" type="button"><i
                                            class='bx bxs-book text-2xl text-green-700'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="purple" onclick="changeBorder('purple')" type="button"><i
                                            class='bx bxs-book text-2xl text-purple-500'></i></button>
                                    <button
                                        class="icon p-3 text-2xl rounded-lg border-2 border-slate-200 transition-all hover:scale-110"
                                        id="violet" onclick="changeBorder('violet')" type="button"><i
                                            class='bx bxs-book text-2xl text-violet-700'></i></button>
                                </div>
                            </div>
                            <div class="courseInput">
                                <label for="course"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Subject/Course Title <span
                                        class="text-red-500 font-bold">*</span>
                                </label>
                                <input type="text" id="course" name="course"
                                    placeholder="e.g. Web Systems and Technologies" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="descriptionInput">
                                <label for="description"
                                    class="mb-2 text-slate-900 font-medium text-base inline-block">Short
                                    Description
                                    <span class="text-gray-500 font-bold text-xs">(optional)</span></label>
                                <input type="text" id="description" placeholder="Brief description of the course..."
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                        </div>

                        <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                            <button type="button" id="cancelBtn"
                                class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Cancel</button>
                            <button type="submit"
                                class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                Create Subject</button>
                        </div>
                    </form>
                </div>
            </div>

            <form class="mt-4 mx-auto flex w-full gap-4" role="search">
                <div
                    class="flex flex-1 items-center gap-2.5 px-3 py-2.5 rounded-md bg-white outline-1 -outline-offset-1 outline-slate-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192.904 192.904" class="size-4 fill-slate-400"
                        aria-hidden="true">
                        <path
                            d="m190.707 180.101-47.078-47.077c11.702-14.072 18.752-32.142 18.752-51.831C162.381 36.423 125.959 0 81.191 0 36.422 0 0 36.423 0 81.193c0 44.767 36.422 81.187 81.191 81.187 19.688 0 37.759-7.049 51.831-18.751l47.079 47.078a7.474 7.474 0 0 0 5.303 2.197 7.498 7.498 0 0 0 5.303-12.803zM15 81.193C15 44.694 44.693 15 81.191 15c36.497 0 66.189 29.694 66.189 66.193 0 36.496-29.692 66.187-66.189 66.187C44.693 147.38 15 117.689 15 81.193z">
                        </path>
                    </svg>
                    <label for="search" class="sr-only">Search</label>
                    <input type="search" id="search" placeholder="Search subjects..." required
                        class="text-base text-slate-900 w-full outline-none" />
                </div>
                <button type="button"
                    class="px-3.5 py-2 text-white text-sm font-semibold cursor-pointer bg-[#333] hover:bg-[#222] border border-[#333] rounded-md transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-[#444] flex items-center gap-2"
                    id="openModal">
                    <i class='bx bx-plus text-sm md:text-base'></i>
                    <span class="text-sm md:text-base">Add Subject</span>
                </button>
            </form>
            <div
                class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto mt-6 p-4 sm:p-6 justify-center md:justify-start flex gap-3">
                <div class="border-r border-gray-300 pr-4 flex items-center justify-center gap-1"><i
                        class='bx bx-book-open text-lg text-blue-600'></i><span>Total:
                        12</span></div>
                <div class="border-r border-gray-300 pr-4 flex items-center justify-center gap-1"><i
                        class='bx bxs-circle text-sm text-green-600'></i><span>Active:
                        10</span></div>
                <div class=" pr-4 flex items-center justify-center gap-1"><i
                        class='bx bx-archive-in text-lg text-red-600'></i><span>Archived:
                        2</span></div>
            </div>
            <!-- grids of subjects -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 w-full mx-auto mt-6 flex-1 min-h-0 overflow-y-auto">
                <!-- one grid item -->
                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>

                <div class="bg-white border border-slate-200 shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4">
                    <!-- heading -->
                    <div class="flex gap-2">
                        <div class="text-center text-4xl"><i class='bx bxs-book text-red-700'></i></div>
                        <div class="flex flex-col">
                            <div>
                                <h1 class="font-bold text-xl">Data Structures and Algorithm</h1>
                            </div>
                            <div class="flex gap-3 text-gray-600 text-sm">
                                <p>Intro to arrays, trees, graphs</p>
                            </div>
                        </div>
                    </div>
                    <!-- active tasks -->
                    <div class="text-sm text-gray-600 flex gap-2">
                        <span><i class='bx bx-task'></i></span>
                        <span>Pending Tasks: 12</span>
                    </div>
                    <!-- status -->
                    <span
                        class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">
                        Active
                    </span>
                    <!-- buttons -->
                    <div class="flex gap-2">
                        <button
                            class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                            <i class='fa-regular fa-eye'></i>
                            <span>View</span>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-edit text-lg text-gray-600'></i>
                        </button>
                        <button
                            class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                            <i class='bx bx-trash text-lg text-gray-600'></i>
                        </button>
                    </div>

                </div>
            </div>




        </main>
    </div>
    <script src="../assets/js/courses.js"></script>
</body>

</html>