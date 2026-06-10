<?php
require_once("../config/runQuery.php");
require_once("../actions/notifications.php");
date_default_timezone_set('Asia/Manila');

$userID = $_SESSION['user_id'] ?? null;
$semesterID = $_SESSION['semester_id'] ?? null;
$currentStreak = 0;
$currentXP = 0;
$profileImagePath = "";

//obtain current streak
$sql = "SELECT current_streak FROM users WHERE user_id = :user_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID]);
$streak = $result->fetch();
$currentStreak = $streak['current_streak'];

//obtain current xp
$sql = "SELECT SUM(xp_change) AS xp_change FROM xp_logs WHERE user_id = :user_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID]);
$currentXP = $result->fetch()['xp_change'] ?? 0;

//obtain profile picture
$sql = "SELECT profile_pic_path FROM users WHERE user_id = :user_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID]);
$profileImagePath = $result->fetch()['profile_pic_path'] ?? '../assets/images/profile-placeholder.png';

//obtain current learning system/semester
$sql = "SELECT semester_name, school_year FROM semesters WHERE semester_id = :semester_id AND is_active = 1";
$result = runQuery($pdo, $sql, ['semester_id' => $semesterID]);
$learningSystem = $result->fetch();
$semesterName = $learningSystem['semester_name'] ?? 'No Active Period';
$schoolYear = $learningSystem['school_year'] ?? '';
function checkStreak($pdo, $userID)
{
    $sql = "SELECT DATE(last_streak_updated) AS last_streak_updated FROM users WHERE user_id = :user_id";
    $result = runQuery($pdo, $sql, ['user_id' => $userID]);
    $last_updated_db = $result->fetch()['last_streak_updated'];
    $today = date('Y-m-d');

    if ($today == $last_updated_db) {
        return;
    }

    $sql = "SELECT DATE(created_at) AS created_at FROM xp_logs WHERE user_id = :user_id ORDER BY DATE(created_at) DESC LIMIT 1";
    $result = runQuery($pdo, $sql, ['user_id' => $userID]);
    $result = $result->fetch();

    if (empty($result)) {
        return;
    }

    $last_xp_date = $result['created_at'];
    $datediff = 0;

    if (!$last_xp_date) {
        return;
    }

    //computing date difference
    $last_xp_date = new DateTime($last_xp_date);
    $today = new DateTime();
    $datediff = $today->diff($last_xp_date)->days;
    global $currentStreak;

    if ($datediff == 0) {
        $currentStreak += 1;
    }

    if ($datediff > 1) {
        $currentStreak = 1;
    }

    if ($datediff == 0 || $datediff > 1) {
        $sql = "
            UPDATE users 
            SET 
                current_streak = :current_streak,
                last_streak_updated = CURDATE(),
                longest_streak = GREATEST(longest_streak, :longest_streak)
            WHERE user_id = :user_id
        "; //reusing placeholders causes error, so we use different names

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userID,
            ':current_streak' => $currentStreak,
            ':longest_streak' => $currentStreak
        ]);
    }
}

function logStreakXP($pdo, $userID, $semesterID)
{
    if (isset($_SESSION['isloggedProgressXP'])) {
        return;
    }

    $xp = 0;
    $reason = "";

    global $currentStreak;
    $currentStreak = (int) $currentStreak;

    if ($currentStreak == 3) {
        $xp = 20;
        $reason = "STREAK_3_DAYS";
    } else if ($currentStreak == 7) {
        $xp = 70;
        $reason = "STREAK_7_DAYS";
    } else if ($currentStreak == 14) {
        $xp = 100;
        $reason = "STREAK_14_DAYS";
    } else if ($currentStreak == 30) {
        $xp = 250;
        $reason = "STREAK_30_DAYS";
    }

    if ($xp == 0 && $reason == "") {
        return;
    }

    $sql = "INSERT INTO xp_logs (
            user_id,
            semester_id,
            xp_change,
            reason
        )
        VALUES (
            :user_id,
            :semester_id,
            :xp_change,
            :reason
        )";

    $params = [
        'user_id' => $userID,
        'semester_id' => $semesterID,
        'xp_change' => $xp,
        'reason' => $reason
    ];
    $result = runQuery($pdo, $sql, $params);

    triggerStreakMilestone($pdo, $userID, (int) $currentStreak);

    $_SESSION['isloggedProgressXP'] = true;
}

function logProgressXP($pdo, $userID, $semesterID)
{
    $sql = "SELECT
            d.total_minutes as current_progress,
            u.daily_goal_minutes as daily_goal_minutes,
            d.is_goal_reached as is_goal_reached
        FROM users u
        JOIN daily_progress d
        ON u.user_id = d.user_id 
        WHERE d.user_id = :user_id
        AND d.semester_id = :semester_id
        AND d.date = CURDATE()";
    $params = [
        'user_id' => $userID,
        'semester_id' => $semesterID,
    ];
    $result = runQuery($pdo, $sql, $params);
    $result = $result->fetch();

    if (empty($result)) {
        return;
    }

    $is_goal_reached = (int) $result['is_goal_reached'];
    if ($is_goal_reached == 1) {
        return;
    }

    $total_minutes = (int) $result['current_progress'];
    $daily_goal = (int) $result['daily_goal_minutes'];
    $is_goal_reached = (int) $total_minutes >= (int) $daily_goal ? true : false;

    if (!$is_goal_reached) {
        return;
    }

    $xp = 0;
    $reason = "";

    if ($total_minutes == $daily_goal) {
        $xp = 30;
        $reason = "DAILY_GOAL_COMPLETED";
    } else if ($total_minutes > $daily_goal) {
        $xp = 40;
        $reason = "DAILY_GOAL_EXCEEDED";
    }

    $sql = "INSERT INTO xp_logs (
            user_id,
            semester_id,
            xp_change,
            reason
        )
        VALUES (
            :user_id,
            :semester_id,
            :xp_change,
            :reason
        );";

    $params = [
        'user_id' => $userID,
        'semester_id' => $semesterID,
        'xp_change' => $xp,
        'reason' => $reason
    ];
    $result = runQuery($pdo, $sql, $params);

    $sql2 = "
        UPDATE daily_progress 
        SET is_goal_reached = 1
        WHERE user_id = :user_id
        AND date = CURDATE()";
    $result = runQuery($pdo, $sql2, ['user_id' => $userID]);

    triggerDailyGoal($pdo, $userID, $semesterID, $total_minutes);
}
function updateLastStudyDateFromXP($pdo, $userId, $semesterId)
{
    $sql = "
        UPDATE users
        SET last_study_date = (
            SELECT DATE(MAX(created_at))
            FROM xp_logs
            WHERE xp_logs.user_id = :user_id
            AND xp_logs.semester_id = :semester_id
        )
        WHERE user_id = :user_id3
        AND EXISTS (
            SELECT 1
            FROM xp_logs
            WHERE xp_logs.user_id = :user_id2
            AND xp_logs.semester_id = :semester_id2
        )
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':semester_id' => $semesterId,
        ':user_id2' => $userId,
        ':semester_id2' => $semesterId,
        ':user_id3' => $userId,
    ]);
}

//streaks and progress
checkStreak($pdo, $userID);
logStreakXP($pdo, $userID, $semesterID);
logProgressXP($pdo, $userID, $semesterID);
updateLastStudyDateFromXP($pdo, $userID, $semesterID);
//notifications
triggerOnboarding($pdo, $userID, $semesterID);
triggerInactivity($pdo);
triggerTasksDueTomorrow($pdo);
triggerOverdueTasks($pdo);
?>
<!-- settings overlay -->
<!-- opacity-0 pointer-events-none scale-95 -->
<div id="modalOverlay-settings"
    class="fixed inset-0 p-4 flex flex-wrap justify-center items-center w-full h-full z-1000 before:fixed before:inset-0 before:w-full before:h-full before:bg-[rgba(0,0,0,0.5)] opacity-0 pointer-events-none scale-95 transition-all">

    <div role="dialog" aria-modal="true" aria-labelledby="modal-title" tabindex="-1"
        class="settings-container w-full max-w-xl bg-white border border-slate-100 shadow-lg rounded-lg relative max-h-[95vh] overflow-y-auto outline-none p-4 md:p-6">
        <div class="flex items-center pb-3 border-b border-slate-300">
            <h3 id="modal-title" class="text-slate-900 text-lg font-semibold flex-1 flex items-center gap-2"><i
                    class='bx bx-cog text-2xl'></i><span>Settings</span>
            </h3>

            <button type="button" id="closeModal-settings" aria-label="Close modal"
                class="ml-auto flex items-center focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-3 cursor-pointer fill-slate-500 hover:fill-red-600"
                    aria-hidden="true" viewBox="0 0 329.269 329">
                    <path
                        d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.27 21.27 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" />
                </svg>
            </button>
        </div>
        <div class="my-6 space-y-6">

            <!-- profile section -->
            <div class="space-y-4">
                <h4 class="text-slate-900 font-semibold text-sm uppercase tracking-wide">
                    Profile
                </h4>

                <form id="profilePicForm" enctype="multipart/form-data">

                    <label class="mb-2 text-slate-900 font-medium text-base inline-block">
                        Profile Picture
                    </label>

                    <div class="flex flex-col md:flex-row md:items-center gap-4">

                        <img id="profilePreview" src="../<?= $profileImagePath ?>"
                            class="w-20 h-20 rounded-full object-cover border border-slate-300">

                        <div class="flex-1">
                            <input type="file" id="profilePic" name="profile_pic" accept="image/*" class="text-sm text-slate-600
                    file:mr-4
                    file:px-3
                    file:py-2
                    file:rounded-md
                    file:border-0
                    file:bg-slate-100
                    file:text-slate-700
                    hover:file:bg-slate-200">
                        </div>

                        <button type="submit" id="updateProfilePicBtn"
                            class="px-3.5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            Update Picture
                        </button>

                    </div>

                </form>
            </div>

            <!-- change name section -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-slate-900 font-semibold text-sm uppercase tracking-wide">Change Name</h4>
                <div class="space-y-3">
                    <div>
                        <label for="first_name" class="mb-2 text-slate-900 font-medium text-base inline-block">
                            First Name
                        </label>

                        <input type="text" id="first_name" value="<?= $_SESSION['first_name'] ?>"
                            class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none" />
                    </div>
                    <div>
                        <label for="last_name" class="mb-2 text-slate-900 font-medium text-base inline-block">
                            Last Name
                        </label>

                        <input type="text" id="last_name" value="<?= $_SESSION['last_name'] ?>"
                            class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none" />
                    </div>
                    <button type="button" id="updateNameBtn" onclick="changeName(<?= $_SESSION['user_id'] ?>)"
                        class="px-3.5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Update Name
                    </button>
                    <div class="name-error text-red-500 text-sm"></div>
                </div>
            </div>


            <!-- security section -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-slate-900 font-semibold text-sm uppercase tracking-wide">Security</h4>
                <div class="space-y-3">
                    <div>
                        <label for="current_password" class="mb-2 text-slate-900 font-medium text-base inline-block">
                            Current Password
                        </label>

                        <div class="relative">
                            <input type="password" id="current_password"
                                class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none pr-10" />

                            <button type="button"
                                class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
                                data-target="current_password">
                                👁️
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="new_password" class="mb-2 text-slate-900 font-medium text-base inline-block">
                            New Password
                        </label>

                        <div class="relative">
                            <input type="password" id="new_password"
                                class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none pr-10" />

                            <button type="button"
                                class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
                                data-target="new_password">
                                👁️
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="confirm_password" class="mb-2 text-slate-900 font-medium text-base inline-block">
                            Confirm New Password
                        </label>

                        <div class="relative">
                            <input type="password" id="confirm_password"
                                class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none pr-10" />

                            <button type="button"
                                class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700"
                                data-target="confirm_password">
                                👁️
                            </button>
                        </div>
                    </div>
                    <button type="button"
                        class="px-3.5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Change Password
                    </button>

                    <div class="password-error text-red-500 text-sm"></div>

                </div>
            </div>


            <!-- learning settings -->
            <div class="space-y-4 pt-4 border-t border-slate-200">
                <h4 class="text-slate-900 font-semibold text-sm uppercase tracking-wide">Learning Settings</h4>

                <!-- active semester -->
                <div>
                    <label for="semester" class="mb-2 text-slate-900 font-medium text-base inline-block">
                        Active Period
                    </label>

                    <select id="semester-select"
                        class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none"></select>
                    <div class="flex justify-between items-center">
                        <button type="button" id="update-period"
                            class="mt-3 px-3.5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                            Update Period
                        </button>
                        <span class="text-xs underline text-blue-400 cursor-pointer" id="new-period">Add New
                            Period</span>
                    </div>

                </div>
                <!-- add period form -->
                <form class="max-w-xl mx-auto space-y-6 hidden" id="add-period-form">

                    <!-- system type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Learning System Type
                        </label>
                        <select id="systemType" name="period_type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled selected>Select your system</option>
                            <option value="semester">Semester-based</option>
                            <option value="term">Term-based</option>
                            <option value="quarter">Quarter-based</option>
                            <option value="module">Module-based</option>
                        </select>
                    </div>

                    <!-- dynamic section wrapper -->
                    <div id="dynamicFields" class="space-y-6 hidden">

                        <!-- current period -->
                        <div id="periodField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Current Period
                            </label>
                            <input type="text" name="current_period" placeholder="e.g. 1st Semester, Q2, Module 3"
                                required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div id="startField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Start Date
                            </label>
                            <input name="start_date" id="start_date" type="date" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                min="<?= date('Y-m-d') ?>" />
                        </div>

                        <div id="endField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                End Date
                            </label>
                            <input name="end_date" id="end_date" type="date" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                        </div>


                        <!-- year level -->
                        <div id="yearField">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                School or Learning Year
                            </label>
                            <input name="year_level" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <button type="submit" id="submit-semester"
                        class="w-full bg-slate-800 text-white py-2 rounded-lg hover:bg-slate-700 transition">
                        Add New Period
                    </button>

                </form>

                <!-- daily goal -->
                <div>
                    <label for="daily_goal" class="mb-2 text-slate-900 font-medium text-base inline-block">
                        Daily Progress Goal (minutes)
                    </label>

                    <input type="number" id="daily_goal" min="1"
                        class="px-3.5 py-3 w-full rounded-md border border-slate-300 focus:border-blue-600 focus:outline-none"
                        placeholder="e.g. 60" />

                    <button type="button" id="updateGoalBtn"
                        class="mt-3 px-3.5 py-2 text-sm font-semibold rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Update Goal
                    </button>
                    <div class="daily-goal-error text-red-500 text-sm"></div>
                </div>
            </div>

        </div>


        <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
            <button type="button" id="cancelBtn-settings"
                class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                Cancel</button>
        </div>
    </div>
</div>
<!-- sidebar -->
<div id="sidebar"
    class="flex flex-col justify-between h-screen fixed md:relative md:row-start-1 md:row-span-2 bg-slate-900 z-20 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div>
        <div class="h-[60px] px-3 md:p-4 flex items-center">
            <span>
                <img src="../assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png" alt="Logo" width="50"
                    class="p-0">
            </span>
            <span>
                <h1 class="font-bold text-white">Learnify</h1>
                <p class="text-sm text-white">Learn Smarter</p>
            </span>
            <span id="chevron-right" class="md:hidden flex items-center">
                <i class='bx bx-chevron-left text-4xl text-white ml-5'></i>
            </span>
        </div>
        <hr class="border-gray-600" />
        <ul class="mt-4">
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/dashboard.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-home text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Dashboard
                    </span>
                </a>
            </li>
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/study-session.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'study-session.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-timer text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Study Sessions
                    </span>
                </a>
            </li>
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/tasks.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'tasks.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-check-square text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Tasks
                    </span>
                </a>
            </li>
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/courses.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'courses.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-book-open text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Courses
                    </span>
                </a>
            </li>
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/analytics.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'analytics.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-bar-chart-alt-2 text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Analytics
                    </span>
                </a>
            </li>
            <li class="w-[90%] mx-auto my-3">
                <a href="../pages/smart-coach.php"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors <?= basename($_SERVER['PHP_SELF']) === 'smart-coach.php' ? 'bg-white text-black!' : '' ?>">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-star text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Smart Coach
                    </span>
                </a>
            </li>
            <!-- <li class="w-[90%] mx-auto my-3">
                <a href="#"
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                    <span class="w-[50px] h-[50px] flex items-center justify-center">
                        <i class='bx bx-badge text-2xl'></i>
                    </span>
                    <span class="ml-2 font-bold">
                        Badges
                    </span>
                </a>
            </li> -->
        </ul>
    </div>
    <div>
        <hr class="border-gray-600" />
        <div class="h-[60px] px-3 md:p-4 flex items-center gap-2">
            <span>
                <img src="../assets/images/semester.png" alt="Logo" width="50" class="p-0">
            </span>
            <span>
                <h1 class="font-bold text-white"><?= $semesterName ?></h1>
                <p class="text-sm text-white"><?= $schoolYear ?></p>
            </span>
        </div>
    </div>
</div>
<!-- topbar -->
<div class="md:col-start-2 bg-slate-800 flex justify-between items-center py-2 px-3 md:py-4 md:px-6">
    <span class="text-xl md:text-2xl font-bold text-white">
        <?= $pageTitle ?>
    </span>
    <span class="flex items-center gap-1 md:gap-4">
        <!-- Streak -->
        <div
            class="hidden md:block flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/20 border border-amber-400/30 backdrop-blur-sm">
            <i class='bx bxs-hot text-amber-400 text-sm'></i>
            <span class="text-amber-400 text-sm"><?= $currentStreak ?></span>
        </div>
        <!-- Badge -->
        <div
            class="hidden md:block flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/20 border border-amber-400/30 backdrop-blur-sm">
            <i class="bx bxs-star text-amber-400 text-sm"></i>
            <span class=" text-amber-400 text-sm"><?= $currentXP ?>
        </div>
        <!-- Notification -->
        <div class="relative">
            <button id="notifBtn"
                class="relative p-2 rounded-lg hover:bg-slate-600 transition-colors text-slate-200 flex items-center">
                <i class='bx bx-bell text-2xl'></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-slate-700 hidden"
                    id="red-ball"></span>
            </button>
            <div id="notifMenu"
                class="hidden absolute -right-17 md:right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden z-200">

                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <span class="font-semibold text-slate-700">Notifications</span>
                    <button id="markAll" class="text-xs text-indigo-600 hover:underline">
                        Mark all as read
                    </button>
                </div>

                <div id="notifList" class="max-h-64 overflow-y-auto"></div>

            </div>
        </div>
        <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-600 transition-colors"
            id="dropdownBtn">
            <!-- <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-slate-500">
            </div> -->
            <img src="../<?= $profileImagePath ?>" alt="profile-pic"
                class="w-8 h-8 rounded-full flex items-center justify-center ring-2 ring-slate-500">
            <i class='bx bx-chevron-down text-2xl text-slate-300'></i>
            <div id="dropdownMenu"
                class="hidden absolute top-12 right-5 mt-2 w-48 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">
                <span class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50" id="openModal-settings">
                    Settings
                </span>

                <a href="../actions/logout-process.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    Logout
                </a>

            </div>
        </button>
        <span class="md:hidden cursor-pointer">
            <i id="hamburger" class="bx bx-menu text-white text-2xl"></i>
        </span>
    </span>
</div>
<script>
    console.log(<?= json_encode($_SESSION['user_id']) ?>);
    console.log("semester id", <?= json_encode($_SESSION['semester_id']) ?>);
    const chevron = document.getElementById("chevron-right");
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");
    chevron.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });
    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });

    //profile dropdown menu logic
    const btn = document.getElementById("dropdownBtn");
    const menu = document.getElementById("dropdownMenu");

    btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
    });

    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add("hidden");
        }
    });

    //code for modal functionalities (open, close, create) from readymadeui
    const openBtn_settings = document.getElementById("openModal-settings");
    const closeBtn_settings = document.getElementById("closeModal-settings");
    const cancelBtn_settings = document.getElementById("cancelBtn-settings");
    const overlay_settings = document.getElementById("modalOverlay-settings");

    // Open modal and lock body scroll
    openBtn_settings.onclick = () => {
        overlay_settings.classList.remove("opacity-0");
        overlay_settings.classList.remove("pointer-events-none");
        overlay_settings.classList.remove("scale-95");
        document.body.style.overflow = "hidden";
    };

    // Close modal and restore focus/scroll
    function closeModalSettings() {
        overlay_settings.classList.add("opacity-0");
        overlay_settings.classList.add("pointer-events-none");
        overlay_settings.classList.add("scale-95");
        document.body.style.overflow = "";
    }

    closeBtn_settings.onclick = cancelBtn_settings.onclick = closeModalSettings;

    // Close when clicking outside the dialog
    overlay_settings.onclick = (e) => {
        if (e.target === overlay_settings) closeModalSettings();
    };

    //notification dropdown logic
    const notif_btn = document.getElementById("notifBtn");
    const notif_menu = document.getElementById("notifMenu");
    const list = document.getElementById("notifList");
    const markAll = document.getElementById("markAll");

    async function fetchNotifications() {
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;

        const formData = new FormData();
        formData.append('userID', userID);
        formData.append('semesterID', semesterID);
        const response = await fetch(
            `/${BASE_URL}/actions/fetch_notifications.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();
        if (data.success) {
            renderNotifications(data.data);
        }
    }

    function renderNotifications(data) {
        let not_is_read = 0;
        list.innerHTML = "";
        data.forEach((notification) => {
            list.innerHTML +=
                `
            <div onclick="markAsRead(${notification.notif_id})"
                        class="px-4 py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition ${notification.is_read ? 'opacity-50' : ''}">
                <div class="flex items-center justify-between gap-2">
                    <div class="space-y-1">
                        <p class="text-sm text-slate-700">${notification.title}</p>
                        <p class="text-xs text-slate-500">${notification.message}</p>
                    </div>
                    ${!notification.is_read ? `<span class="w-2 h-2 mt-2 bg-indigo-500 rounded-full"></span>` : ``}
                </div>
            </div>
            `
            not_is_read += !notification.is_read ? 1 : 0;
        })

        if (not_is_read > 0) {
            document.getElementById('red-ball').classList.remove('hidden');
        } else {
            document.getElementById('red-ball').classList.add('hidden');
        }
    }

    async function markAsRead(notifID) {
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;

        const formData = new FormData();
        formData.append('userID', userID);
        formData.append('semesterID', semesterID);
        formData.append('notifID', notifID)
        const response = await fetch(
            `/${BASE_URL}/actions/mark_read_notifications.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();
        if (data.success) {
            fetchNotifications();
        }
    }

    markAll.addEventListener("click", async () => {
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        const semesterID = <?= json_encode($_SESSION['semester_id'] ?? null) ?>;

        const formData = new FormData();
        formData.append('userID', userID);
        formData.append('semesterID', semesterID);
        const response = await fetch(
            `/${BASE_URL}/actions/mark_read_notifications_all.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();
        if (data.success) {
            fetchNotifications();
        }
    });

    notif_btn.addEventListener("click", () => {
        notif_menu.classList.toggle("hidden");
        fetchNotifications();
    });

    document.addEventListener("click", (e) => {
        if (!notif_btn.contains(e.target) && !notif_menu.contains(e.target)) {
            notif_menu.classList.add("hidden");
        }
    });

    fetchNotifications();
</script>
<script src="../components/change-profile.js"></script>
<script src="../components/change-password.js"></script>
<script src="../components/change-daily-progress.js"></script>
<script src="../components/change-name.js"></script>
<script>
    //for dynamic addition of period
    const systemType = document.getElementById("systemType");
    const dynamicFields = document.getElementById("dynamicFields");
    const customNote = document.getElementById("customNote");
    const periodField = document.getElementById("periodField");
    const yearField = document.getElementById("yearField");

    systemType.addEventListener("change", function () {
        const value = this.value;

        // show main dynamic section unless "none"
        if (value === "none") {
            dynamicFields.classList.add("hidden");
            return;
        }

        dynamicFields.classList.remove("hidden");

        // reset visibility
        customNote.classList.add("hidden");
        periodField.classList.remove("hidden");
        yearField.classList.remove("hidden");

        // adjust based on selection
        if (value === "custom") {
            customNote.classList.remove("hidden");
        }

        if (value === "module" || value === "custom") {
            //still show period
            periodField.querySelector("input").placeholder =
                "e.g. Module 3, Week 5, Lesson 2";
        } else {
            periodField.querySelector("input").placeholder =
                "e.g. 1st Semester, Q2, Term 1";
        }

        //some systems may not need year level
        if (value === "module") {
            yearField.classList.add("hidden");
        }
    });

    const addPeriodForm = document.getElementById("add-period-form");

    document.getElementById("new-period").addEventListener("click", () => {
        addPeriodForm.classList.toggle("hidden");
    });

    //for submission of form
    addPeriodForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const start_date = document.getElementById("start_date").value;
        const end_date = document.getElementById("end_date").value;
        if (start_date >= end_date) {
            Swal.fire({
                icon: "error",
                title: "Failed!",
                text: "Invalid settings of start date and end date",
            });
            return;
        }

        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;

        const formData = new FormData(addPeriodForm);
        formData.append("userID", userID);

        const response = await fetch(
            `/${BASE_URL}/components/add-new-period.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();
        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "Created!",
                text: "The period was added.",
            });
            addPeriodForm.reset();
            addPeriodForm.classList.toggle('hidden');
            fetchPeriods();
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed!",
                text: "There was an error adding a new date",
            });
        }
    });

    //for fetching of active period list
    const semesterDown = document.getElementById('semester-select');
    async function fetchPeriods() {
        const formData = new FormData();
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        formData.append('userID', userID);

        const response = await fetch(
            `/${BASE_URL}/components/fetch-periods.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();

        if (data.success) {
            semesterDown.innerHTML = "";
            data.data.forEach((period) => {
                semesterDown.innerHTML +=
                    `
                    <option value="${period.semester_id}" ${period.is_active ? "selected" : ""}>${period.semester_name} (${period.school_year})</option>
                `
            });

        }
    }
    fetchPeriods();

    //for updating periods
    document.getElementById('update-period').onclick = updatePeriod;
    async function updatePeriod() {
        const formData = new FormData();
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id'] ?? null) ?>;
        formData.append('userID', userID);
        formData.append('semesterID', semesterDown.value);
        const response = await fetch(
            `/${BASE_URL}/components/update-periods.php`, {
            method: "POST",
            body: formData
        }
        );
        const data = await response.json();

        if (data.success) {
            Swal.fire({
                icon: "success",
                title: "Changed!",
                text: "The active period was changed.",
            });

            setTimeout(() => {
                location.reload();
            }, 2000)
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed!",
                text: "There was an error changing the active period!",
            });
        }
    }

</script>