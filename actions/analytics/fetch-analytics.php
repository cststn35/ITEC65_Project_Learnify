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

        // 1. TOTAL STUDY TIME
        "total_study_time" => "
        SELECT
            ROUND(SUM(duration_seconds) / 3600, 2) AS total_hours
        FROM fact_study_session fs
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND fs.status = 'completed'
    ",

        // 2. QUIZ AVERAGE SCORE
        "quiz_average_score" => "
        SELECT
            ROUND(AVG((score * 100.0) / total_questions), 2) AS quiz_average
        FROM fact_quiz fq
        JOIN dim_user du ON fq.user_sk = du.user_sk
        JOIN dim_semester ds ON fq.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
    ",

        // 3. TASK COMPLETION
        "task_completion" => "
        SELECT
            COUNT(*) AS total_tasks,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) AS completed_tasks
        FROM fact_task ft
        JOIN dim_user du ON ft.user_sk = du.user_sk
        JOIN dim_semester ds ON ft.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
    ",

        // 4. STREAK
        "streak" => "
        SELECT current_streak, longest_streak
        FROM users
        WHERE user_id = :user_id
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

        // 6. STUDY BY SUBJECT
        "study_by_subject" => "
        SELECT
            dsub.name,
            ROUND(SUM(duration_seconds) / 3600, 2) AS total_hours
        FROM fact_study_session fs
        JOIN dim_subject dsub ON fs.subject_sk = dsub.subject_sk
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND fs.status = 'completed'
        GROUP BY dsub.subject_sk
        ORDER BY total_hours DESC
    ",

        // 7. STUDY CONSISTENCY
        "study_consistency" => "
        SELECT
            ROUND(
                SUM(CASE WHEN fdp.total_minutes >= u.daily_goal_minutes THEN 1 ELSE 0 END)
                * 100.0 / COUNT(*),
                2
            ) AS consistency_score
        FROM fact_daily_progress fdp
        JOIN dim_user du ON fdp.user_sk = du.user_sk
        JOIN users u ON du.user_id = u.user_id
        JOIN dim_semester ds ON fdp.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
    ",

        // 8. PEAK STUDY HOURS
        "peak_study_hours" => "
        SELECT
            start_hour,
            COUNT(*) AS total_sessions
        FROM fact_study_session fs
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND fs.status = 'completed'
        GROUP BY start_hour
        ORDER BY start_hour
    ",

        // 9. GOAL ACHIEVEMENT RATE
        "goal_achievement_rate" => "
        SELECT
            ROUND(
                SUM(CASE WHEN total_minutes >= u.daily_goal_minutes THEN 1 ELSE 0 END)
                * 100.0 / COUNT(*),
                2
            ) AS goal_achievement_rate
        FROM fact_daily_progress fdp
        JOIN dim_user du ON fdp.user_sk = du.user_sk
        JOIN users u ON du.user_id = u.user_id
        JOIN dim_semester ds ON fdp.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
    ",

        // 10. PLANNED VS ACTUAL
        "planned_vs_actual" => "
        SELECT
            ROUND(AVG(target_duration_minutes), 2) AS avg_target_minutes,
            ROUND(AVG(duration_seconds) / 60, 2) AS avg_actual_minutes
        FROM fact_study_session fs
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND fs.status = 'completed'
    ",

        // 11. SESSION COMPLETION RATE
        "session_completion_rate" => "
        SELECT status, COUNT(*) AS total
        FROM fact_study_session fs
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        GROUP BY status
    ",

        // 12. TASK ON TIME
        "task_on_time" => "
        SELECT
            COUNT(*) AS completed_tasks,
            SUM(CASE WHEN is_late = 0 THEN 1 ELSE 0 END) AS on_time_tasks
        FROM fact_task ft
        JOIN dim_user du ON ft.user_sk = du.user_sk
        JOIN dim_semester ds ON ft.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND ft.status = 'completed'
    ",

        // 13. QUIZ TREND
        "quiz_trend" => "
        SELECT
            dd.full_date,
            ROUND(AVG(score * 100.0 / total_questions), 2) AS avg_score
        FROM fact_quiz fq
        JOIN dim_date dd ON fq.date_sk = dd.date_sk
        JOIN dim_user du ON fq.user_sk = du.user_sk
        JOIN dim_semester ds ON fq.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        GROUP BY dd.full_date
        ORDER BY dd.full_date
    ",

        // 14. SUBJECT MASTERY
        "subject_mastery" => "
        SELECT
            dsub.name,
            ROUND(AVG(score * 100.0 / total_questions), 2) AS mastery_score
        FROM fact_quiz fq
        JOIN dim_subject dsub ON fq.subject_sk = dsub.subject_sk
        JOIN dim_user du ON fq.user_sk = du.user_sk
        JOIN dim_semester ds ON fq.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        GROUP BY dsub.subject_sk
        ORDER BY mastery_score DESC
    ",

        // 15. STUDY VS QUIZ
        "study_vs_quiz" => "
        SELECT
            ROUND(fs.duration_seconds / 60, 2) AS study_minutes,
            ROUND(fq.score * 100.0 / fq.total_questions, 2) AS quiz_percent
        FROM fact_study_session fs
        JOIN fact_quiz fq ON fs.subject_sk = fq.subject_sk
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        AND fs.status = 'completed'
    ",

        // 16. XP GROWTH
        "xp_growth" => "
        SELECT
            dd.full_date,
            SUM(fx.xp_change) AS total_xp
        FROM fact_xp fx
        JOIN dim_date dd ON fx.date_sk = dd.date_sk
        JOIN dim_user du ON fx.user_sk = du.user_sk
        JOIN dim_semester ds ON fx.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        GROUP BY dd.full_date
        ORDER BY dd.full_date
    ",

        // 17. XP SOURCE BREAKDOWN
        "xp_source_breakdown" => "
        SELECT
            dxr.category,
            SUM(fx.xp_change) AS total_xp
        FROM fact_xp fx
        JOIN dim_xp_reason dxr ON fx.reason_sk = dxr.reason_sk
        JOIN dim_user du ON fx.user_sk = du.user_sk
        JOIN dim_semester ds ON fx.semester_sk = ds.semester_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id
        GROUP BY dxr.category
        ORDER BY total_xp DESC
    "
    ];

    $results = [];

    foreach ($queries as $key => $sql) {
        $params = [
            ':user_id' => $userID,
            ':semester_id' => $semesterID
        ];
        if ($key == "streak") {
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