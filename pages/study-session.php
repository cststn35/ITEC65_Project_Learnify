<?php include_once("../actions/auth.php"); ?>
<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/meta-head.php") ?>

<?php

require_once("../config/runQuery.php");
$userID = $_SESSION['user_id'] ?? null;
$semesterID = $_SESSION['semester_id'] ?? null;

$sql = "SELECT COUNT(*) AS total_sessions FROM sessions WHERE user_id = :user_id AND semester_id = :semester_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID, 'semester_id' => $semesterID]);
$total_sessions = $result->fetch()['total_sessions'];

$sql = "SELECT COALESCE(SUM(actual_duration_seconds),0) AS total_time FROM sessions WHERE user_id = :user_id AND semester_id = :semester_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID, 'semester_id' => $semesterID]);
$total_time = (int) $result->fetch()['total_time'] ?? 0;
$total_time = floor($total_time / 3600) . " hr " . floor(($total_time % 3600) / 60) . " min";

$sql = "SELECT COALESCE((SUM(score) / NULLIF(SUM(total_questions), 0)) * 100, 0) AS total_score FROM fact_quiz WHERE user_sk = :user_id AND semester_sk = :semester_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID, 'semester_id' => $semesterID]);
$total_score = (int) $result->fetch()['total_score'] ?? 0;

$sql = "SELECT COALESCE(SUM(xp_change), 0) AS total_xp FROM xp_logs WHERE user_id = :user_id AND semester_id = :semester_id AND (reason = 'STUDY_SESSION' OR reason = 'QUIZ_COMPLETION')";
$result = runQuery($pdo, $sql, ['user_id' => $userID, 'semester_id' => $semesterID]);
$total_xp = (int) $result->fetch()['total_xp'] ?? 0;

$sql =
    "
    SELECT 
        DATE_FORMAT(s.created_at, '%M %d, %Y') AS created_at,
        s.session_id,
        s.title AS session_title,
        t.title AS task_title,
        sub.name AS subject_title,

        FLOOR(s.actual_duration_seconds/60) as actual_duration_seconds,
        s.total_pause_seconds,
        s.status,
        s.session_notes,

        LEAST(
            ROUND(
                (s.actual_duration_seconds / NULLIF(s.target_duration_minutes * 60, 0)) * 100,
            2),
        100) AS progress,

        q.score,
        q.total_questions,
        q.quiz_id

    FROM sessions s
    LEFT JOIN tasks t 
        ON s.task_id = t.tasks_id
    LEFT JOIN subjects sub 
        ON s.subject_id = sub.subject_id
    LEFT JOIN quizzes q
        ON s.session_id = q.session_id

    WHERE s.user_id = :user_id
    AND s.semester_id = :semester_id
    AND s.status != 'invalidated'

    ORDER BY s.created_at DESC;
";
$result = runQuery($pdo, $sql, ['user_id' => $userID, 'semester_id' => $semesterID]);
$tableData = $result->fetchAll();
?>

<body class="font-[Inter]">
    <div class="md:grid md:grid-cols-[250px_1fr] md:grid-rows-[60px_1fr] h-screen">
        <?php
        $pageTitle = "Study Sessions";
        include_once("../components/topsidebar.php");
        ?>
        <main
            class="bg-slate-100 col-start-2 p-4 md:p-6 lg:p-8 max-h-[calc(100dvh-60px)] flex flex-col overflow-hidden">
            <!-- quiz result overlay -->
            <!-- opacity-0 pointer-events-none scale-95 -->
            <div id="modalOverlay2"
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] opacity-0 pointer-events-none scale-95 transition-all">

                <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
                    class="end-session-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
                    <div class="flex items-center pb-3 border-b border-slate-300">
                        <h3 id="modal-title"
                            class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                                class='bx bx-pencil text-2xl'></i><span>Quiz Answer</span>
                        </h3>
                    </div>

                    <div class="answer-cont"></div>


                    <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                        <button type="submit" id="exit-ngayon"
                            class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-red-600 border border-red-600 transition-colors hover:bg-red-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500">
                            Close</button>
                    </div>
                </div>
            </div>
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
                class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] opacity-0 pointer-events-none scale-95 transition-all">

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
                            <div class="timeInput">
                                <label for="time" class="mb-2 text-slate-900 font-medium text-base inline-block">Planned
                                    Duration (in minutes)
                                    <span class="text-red-500 font-bold">*</span></label>
                                <input type="number" min="1" id="time" name="time" placeholder="e.g. 10" required
                                    class="px-3.5 py-3 text-base text-slate-900 rounded-md bg-white w-full outline-1 -outline-offset-1 outline-slate-300 focus:outline-2 focus:-outline-offset-2 focus:outline-blue-600" />
                            </div>
                            <div class="questionInput">
                                <label for="time" class="mb-2 text-slate-900 font-medium text-base inline-block">Do you
                                    want to take a quiz after the session?
                                    <span class="text-red-500 font-bold">*</span></label>

                                <div class="space-y-2">
                                    <div class="answer no-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBordera('.no-answer')">
                                        <div
                                            class="border-blue-400 rounded-full w-4 h-4 flex items-center justify-center bg-black">
                                            <div class="rounded-full w-2 h-2 bg-blue-400 hidden"></div>
                                        </div>
                                        <span>No</span>
                                    </div>

                                    <div class="answer yes-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBordera('.yes-answer')">
                                        <div
                                            class="border-blue-400 rounded-full w-4 h-4 flex items-center justify-center bg-black">
                                            <div class="rounded-full w-2 h-2 bg-blue-400 hidden"></div>
                                        </div>
                                        <span>Yes</span>
                                    </div>

                                    <div class="answer later-answer flex items-center gap-3 w-full border-3 border-slate-300 rounded-md px-3.5 py-3 hover:border-blue-300 transition ease-in duration-.2"
                                        onclick="changeBordera('.later-answer')">
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
                        <?= $total_sessions ?>
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Total Time</span>
                        <span><i class='bx bx-time text-green-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        <?= $total_time ?>
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Average Quiz Score</span>
                        <span><i class='bx bx-trophy text-yellow-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        <?= $total_score ?>%
                    </span>
                </div>
                <div class="bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 text-sm">Total XP</span>
                        <span><i class='bx bx-book text-blue-500 text-xl'></i></span>
                    </div>
                    <span class="font-semibold text-2xl">
                        <?= $total_xp ?>
                    </span>
                </div>
            </div>
            <div class="flex-1 min-h-0 mt-6 overflow-y-auto overflow-x-auto">
                <div class="w-full mx-auto bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

                    <table class="w-full">

                        <thead
                            class="hidden md:table-header-group text-left text-sm font-semibold text-slate-600 border-b border-slate-200 bg-slate-50">
                            <tr>
                                <th class="px-4 py-3.5">Date</th>
                                <th class="px-4 py-3.5">Title</th>
                                <th class="px-4 py-3.5">Task</th>
                                <th class="px-4 py-3.5">Subject</th>
                                <th class="px-4 py-3.5">Progress</th>
                                <th class="px-4 py-3.5">Actual Duration</th>
                                <th class="px-4 py-3.5">Pause</th>
                                <th class="px-4 py-3.5">Quiz</th>
                                <th class="px-4 py-3.5">Status</th>
                                <th class="px-4 py-3.5">Session Notes</th>
                                <th class="px-4 py-3.5">Action</th>
                            </tr>
                        </thead>

                        <tbody class="text-sm divide-y divide-slate-100">

                            <?php foreach ($tableData as $row): ?>

                                <tr
                                    class="hover:bg-slate-50 transition border-b border-slate-100 md:border-0 md:table-row block mb-4 md:mb-0">

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Date</span>
                                        <span class="text-slate-800 font-medium">
                                            <i class='bx bxs-calendar mr-1'></i>
                                            <?= $row['created_at'] ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Title</span>
                                        <span class="text-slate-700">
                                            <?= $row['session_title'] ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Task</span>
                                        <span class="text-slate-600">
                                            <?= $row['task_title'] == null ? "General Study" : $row['task_title'] ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Subject</span>
                                        <span class="text-slate-600">
                                            <?= $row['subject_title'] ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Progress</span>

                                        <div class="w-full">
                                            <div class="text-right md:text-left text-slate-700 mb-1">
                                                <?= $row['progress'] ?>%
                                            </div>

                                            <div class="bg-slate-200 rounded-full h-2 w-full overflow-hidden">
                                                <div class="bg-indigo-500 h-2 rounded-full transition-all duration-300"
                                                    style="width: <?= $row['progress'] ?>%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Duration</span>
                                        <span class="text-slate-600">
                                            <i class='bx bx-time mr-1'></i>
                                            <?= $row['actual_duration_seconds'] ?>m
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Pause</span>
                                        <span class="text-slate-600">
                                            <?= $row['total_pause_seconds'] ?>s
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Quiz</span>
                                        <span class="text-slate-700 font-medium">
                                            <?= $row['score'] ?>/<?= $row['total_questions'] ?>
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Status</span>

                                        <?php if ($row['status'] == "paused"): ?>
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Completed
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Session Notes</span>

                                        <button onclick="viewSessionNotes('<?= $row['session_notes'] ?>')"
                                            class="px-3 py-2 text-sm bg-slate-800 text-white rounded-lg hover:bg-slate-700 active:scale-[0.98] transition">
                                            View Session Notes
                                        </button>

                                    </td>

                                    <td class="px-4 py-4 flex justify-between md:table-cell">
                                        <span class="md:hidden text-slate-500 font-semibold">Action</span>

                                        <?php if ($row['total_questions']): ?>
                                            <button onclick="showQuizResult(<?= $row['quiz_id'] ?>)"
                                                class="px-3 py-2 text-sm bg-slate-800 text-white rounded-lg hover:bg-slate-700 active:scale-[0.98] transition">
                                                View Result
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($row['status'] == "paused"): ?>
                                            <button onclick="invalidateSession(<?= $row['session_id'] ?>)"
                                                class="px-3 py-2 text-sm bg-red-800 text-white rounded-lg hover:bg-red-700 active:scale-[0.98] transition">
                                                Invalidate Session
                                            </button>
                                        <?php endif; ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>
                    </table>

                </div>
            </div>



        </main>
    </div>
    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        console.log(userID);
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;
        const startStudy = <?= json_encode($_SESSION['start_study'] ?? null) ?>;

        function viewSessionNotes(notes) {
            swal.fire({
                title: 'Session Notes',
                text: notes || "No notes available for this session.",
                icon: 'info',
                confirmButtonText: 'Close'
            });
        }
    </script>
    <script src="../assets/js/sessions/add_session_modal.js"></script>
    <script src="../assets/js/tasks/fetch_subjects_2.js"></script>
    <script src="../assets/js/sessions/fetch_tasks_sessions.js"></script>
    <script src="../assets/js/sessions/show_quiz_result.js"></script>
    <script src="../assets/js/sessions/invalidate-session.js"></script>
</body>

</html>