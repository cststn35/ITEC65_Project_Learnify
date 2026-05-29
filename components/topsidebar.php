<?php
require_once("../config/runQuery.php");
date_default_timezone_set('Asia/Manila');

$userID = $_SESSION['user_id'] ?? null;
$semesterID = $_SESSION['semester_id'] ?? null;
$currentStreak = 0;
$currentXP = 0;

//obtain current streak
$sql = "SELECT current_streak FROM users WHERE user_id = :user_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID]);
$streak = $result->fetch();
$currentStreak = $streak['current_streak'];

//obtain current xp
$sql = "SELECT SUM(xp_change) AS xp_change FROM xp_logs WHERE user_id = :user_id";
$result = runQuery($pdo, $sql, ['user_id' => $userID]);
$currentXP = $result->fetch()['xp_change'] ?? 0;
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
}

checkStreak($pdo, $userID);
logStreakXP($pdo, $userID, $semesterID);
logProgressXP($pdo, $userID, $semesterID);
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

        <form id="settings-form-container">
            <div class="my-6 space-y-6">

            </div>

            <div class="border-t border-slate-300 pt-4 flex justify-end gap-4 md:pt-6">
                <button type="button" id="cancelBtn-settings"
                    class="px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md cursor-pointer bg-white border border-slate-300 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    Cancel</button>
                <button type="submit" id="settings-save"
                    class="px-3.5 py-2 text-white text-sm font-semibold rounded-md cursor-pointer bg-blue-600 border border-blue-600 transition-colors hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                    Save Settings</button>
            </div>
        </form>
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                    class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
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
                <h1 class="font-bold text-white">2nd Semester</h1>
                <p class="text-sm text-white">S.Y. 2025-2026</p>
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
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-slate-700"></span>
            </button>
            <div id="notifMenu"
                class="hidden absolute -right-17 md:right-0 mt-2 w-80 bg-white border border-slate-200 rounded-xl shadow-lg overflow-hidden">

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
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-slate-500">
            </div>
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
    const chevron = document.getElementById("chevron-right");
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");
    chevron.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });
    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });

    //dropdown menu logic
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
    let notifications = [
        { id: 1, text: "New quiz available", read: false },
        { id: 2, text: "Your study session is completed", read: false },
        { id: 3, text: "Reminder: Math task due tomorrow", read: false }
    ];

    const notif_btn = document.getElementById("notifBtn");
    const notif_menu = document.getElementById("notifMenu");
    const list = document.getElementById("notifList");
    const markAll = document.getElementById("markAll");

    function renderNotifications() {
        const unread = notifications.filter(n => !n.read).length;

        list.innerHTML = notifications.map(n => `
        <div onclick="markAsRead(${n.id})"
            class="px-4 py-3 border-b border-slate-100 cursor-pointer hover:bg-slate-50 transition ${n.read ? 'opacity-50' : ''}">

            <div class="flex items-start justify-between gap-2">
                <p class="text-sm text-slate-700">${n.text}</p>

                ${!n.read ? `<span class="w-2 h-2 mt-2 bg-indigo-500 rounded-full"></span>` : ""}
            </div>

        </div>
    `).join("");
    }

    function markAsRead(id) {
        notifications = notifications.map(n =>
            n.id === id ? { ...n, read: true } : n
        );

        renderNotifications();
    }

    markAll.addEventListener("click", () => {
        notifications = notifications.map(n => ({ ...n, read: true }));
        renderNotifications();
    });

    notif_btn.addEventListener("click", () => {
        notif_menu.classList.toggle("hidden");
        renderNotifications();
    });

    document.addEventListener("click", (e) => {
        if (!notif_btn.contains(e.target) && !notif_menu.contains(e.target)) {
            notif_menu.classList.add("hidden");
        }
    });

    renderNotifications();
</script>