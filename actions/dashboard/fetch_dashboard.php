<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/db.php';

try {
    $userID = isset($_GET["userID"])
        ? trim($_GET["userID"])
        : "";

    $semesterID = isset($_GET["semesterID"])
        ? trim($_GET["semesterID"])
        : "";

    // $userID = 1;
    // $semesterID = 1;

    $queries = [
        "semester" => "
        SELECT
            semester_name, school_year
        FROM semesters
        WHERE user_id = :user_id
    ",
        "user_info" => "
        SELECT
            name
        FROM users
        WHERE user_id = :user_id
    ",
        "daily_progress_goal" => "
        SELECT
            daily_goal_minutes
        FROM users
        WHERE user_id = :user_id
    ",
        // 1. TOTAL STUDY TIME
        "today_study" => "
        SELECT
            SUM(d.total_minutes) as current_progress,
            u.daily_goal_minutes
        FROM users u
        JOIN daily_progress d
        ON u.user_id = d.user_id 
        WHERE d.user_id = :user_id
        AND d.semester_id = :semester_id
        AND d.date = CURDATE()
        GROUP BY d.date
    ",

        // 2. STREAK COUNT
        "streak" => "
        SELECT
            current_streak, longest_streak
        FROM users
        WHERE user_id = :user_id
    ",

        // 3. TASK COUNT
        "task_count" => "
        SELECT
            COUNT(*) as all_tasks,
            SUM(CASE WHEN t.status = 'pending' THEN 1 ELSE 0 END) as pending_tasks,
            SUM(CASE WHEN t.priority = 'high' AND t.status = 'pending' THEN 1 ELSE 0 END) as highprio_tasks
        FROM tasks t
        JOIN subjects s
        ON t.subject_id = s.subject_id
        WHERE t.user_id = :user_id
        AND t.semester_id = :semester_id
        AND t.is_archived = 0
        AND s.is_archived = 0
    ",

        // 4. ALL TASKS (LIMIT TO 5)
        "task_data" => "
        SELECT 
            t.title, 
            t.deadline, 
            t.priority
        FROM tasks t 
        INNER JOIN subjects s 
            ON t.subject_id = s.subject_id 
        WHERE t.user_id = :user_id 
            AND t.semester_id = :semester_id
            AND t.is_archived = 0
            AND t.status = 'pending'
            AND s.is_archived = 0
        ORDER BY t.deadline DESC
        LIMIT 5
    ",

        // 5. STUDY TREND
        "study_trend" => "
        SELECT
            dd.full_date,
            dd.weekday,
            fdp.total_minutes
        FROM fact_daily_progress fdp
        JOIN dim_date dd ON fdp.date_sk = dd.date_sk
        JOIN dim_user du ON fdp.user_sk = du.user_sk
        JOIN dim_semester ds ON fdp.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND dd.full_date >= CURDATE() - INTERVAL 7 DAY
        ORDER BY dd.full_date
    ",
        //6. HIGH PRIORITY TASK ALERT
        "task_management" => "
        SELECT
            SUM(CASE WHEN priority = 'high' AND status = 'pending' THEN 1 ELSE 0 END)
            AS pending_high_priority
        FROM fact_task
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id",
        //7. ACADEMIC MASTERY
        'academic_risk' => "
        SELECT
            ROUND(AVG(score/total_questions) * 100, 2) AS avg_quiz_score
        FROM fact_quiz
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        "
    ];

    $results = [];

    foreach ($queries as $key => $sql) {
        $params = [
            ':user_id' => $userID,
            ':semester_id' => $semesterID
        ];
        if ($key == "streak" || $key == "user_info" || $key == "semester" || $key == "daily_progress_goal") {
            $params = [':user_id' => $userID];
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        $results[$key] = $data;
    }

    echo json_encode([
        'success' => true,
        'result' => $results
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}