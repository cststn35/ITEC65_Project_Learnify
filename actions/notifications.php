<?php
function createNotification($pdo, $userId, $semesterId, $title, $message, $type)
{
    $sql = "
        INSERT INTO notifications (user_id, semester_id, title, message, type)
        VALUES (:user_id, :semester_id, :title, :message, :type)
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':semester_id' => $semesterId,
        ':title' => $title,
        ':message' => $message,
        ':type' => $type
    ]);
}
function triggerOnboarding($pdo, $userId, $semesterID)
{
    $stmt = $pdo->prepare("
        SELECT is_newly_registered 
        FROM users 
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user || $user['is_newly_registered'] == 0)
        return;

    // onboarding has no semester context
    createNotification(
        $pdo,
        $userId,
        $semesterID,
        "Welcome to Learnify 🎉",
        "Start by creating your first subject and task.",
        "ONBOARDING"
    );

    $pdo->prepare("
        UPDATE users 
        SET is_newly_registered = 0 
        WHERE user_id = ?
    ")->execute([$userId]);
}
function triggerStreakMilestone($pdo, $userId, $streak)
{
    $milestones = [3, 7, 14, 30];
    if (!in_array($streak, $milestones))
        return;

    $stmt = $pdo->prepare("
        SELECT 1 FROM notifications
        WHERE user_id = ?
        AND type = 'STREAK'
        AND message LIKE ?
    ");
    $stmt->execute([$userId, "%$streak%"]);

    if ($stmt->fetch())
        return;

    $messages = [
        3 => "3-day streak 🔥 Keep it going!",
        7 => "7-day streak 💪 Consistency is building!",
        14 => "14-day streak 🚀 Strong discipline!",
        30 => "30-day streak 🏆 Legendary focus!"
    ];

    createNotification(
        $pdo,
        $userId,
        null,
        "Streak Milestone",
        $messages[$streak],
        "STREAK"
    );
}
function triggerDailyGoal($pdo, $userId, $semesterId, $totalMinutes)
{
    $stmt = $pdo->prepare("
        SELECT daily_goal_minutes 
        FROM users 
        WHERE user_id = ?
    ");
    $stmt->execute([$userId]);
    $goal = $stmt->fetchColumn();

    if ($totalMinutes < $goal)
        return;

    // prevent duplicate per day per semester
    $stmt = $pdo->prepare("
        SELECT 1 FROM notifications
        WHERE user_id = ?
        AND semester_id = ?
        AND type = 'DAILY_GOAL'
        AND DATE(created_at) = CURDATE()
    ");
    $stmt->execute([$userId, $semesterId]);

    if ($stmt->fetch())
        return;

    createNotification(
        $pdo,
        $userId,
        $semesterId,
        "Daily Goal Reached 🎉",
        "You studied {$totalMinutes} minutes today!",
        "DAILY_GOAL"
    );
}

function triggerInactivity($pdo)
{
    $users = $pdo->query("
        SELECT user_id, last_study_date 
        FROM users
    ")->fetchAll();

    foreach ($users as $user) {

        if (!$user['last_study_date'])
            continue;

        $days = floor(
            (time() - strtotime($user['last_study_date'])) / 86400
        );

        if ($days < 2)
            continue;

        $stmt = $pdo->prepare("
            SELECT 1 FROM notifications
            WHERE user_id = ?
            AND type = 'INACTIVITY'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
        ");
        $stmt->execute([$user['user_id']]);

        if ($stmt->fetch())
            continue;

        createNotification(
            $pdo,
            $user['user_id'],
            null,
            "We miss you 😴",
            "You haven't studied for {$days} days. Come back when ready!",
            "INACTIVITY"
        );
    }
}
function triggerTasksDueTomorrow($pdo)
{
    $tasks = $pdo->query(" SELECT user_id, semester_id, title FROM tasks WHERE DATE(deadline) = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND status = 'pending' AND is_archived = 0 ")->fetchAll();
    foreach ($tasks as $task) {
        $stmt = $pdo->prepare(" SELECT 1 FROM notifications WHERE user_id = ? AND semester_id = ? AND type = 'TASK_DUE' AND message LIKE ? AND DATE(created_at) = CURDATE() ");
        $stmt->execute([$task['user_id'], $task['semester_id'], "%{$task['title']}%"]);
        if ($stmt->fetch())
            continue;
        createNotification($pdo, $task['user_id'], $task['semester_id'], "Task Due Tomorrow ⏰", "{$task['title']} is due tomorrow.", "TASK_DUE");
    }
}
function triggerOverdueTasks($pdo)
{
    $tasks = $pdo->query("
        SELECT user_id, semester_id, title
        FROM tasks
        WHERE deadline < NOW()
        AND status = 'pending'
        AND is_archived = 0
    ")->fetchAll();

    foreach ($tasks as $task) {

        $stmt = $pdo->prepare("
            SELECT 1 FROM notifications
            WHERE user_id = ?
            AND semester_id = ?
            AND type = 'TASK_OVERDUE'
            AND message LIKE ?
        ");
        $stmt->execute([
            $task['user_id'],
            $task['semester_id'],
            "%{$task['title']}%"
        ]);

        if ($stmt->fetch())
            continue;

        createNotification(
            $pdo,
            $task['user_id'],
            $task['semester_id'],
            "Task Overdue 🚨",
            "{$task['title']} is overdue.",
            "TASK_OVERDUE"
        );
    }
}
?>