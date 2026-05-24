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

    $queries = [
        'study_health' => "
        SELECT
            ROUND(
                (
                    COALESCE(consistency.consistency_score, 0) * 0.4 +
                    COALESCE(session.session_score, 0) * 0.3 +
                    COALESCE(quiz.quiz_score, 0) * 0.3
                ), 2
            ) AS study_health_score
        FROM dim_user u

        LEFT JOIN (
            SELECT
                user_sk,
                semester_sk,
                AVG(CASE WHEN total_minutes > 0 THEN 1 ELSE 0 END) * 100 AS consistency_score
            FROM fact_daily_progress
            GROUP BY user_sk, semester_sk
        ) consistency
        ON consistency.user_sk = u.user_id

        LEFT JOIN (
            SELECT
                user_sk,
                semester_sk,
                AVG(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) * 100 AS session_score
            FROM fact_study_session
            GROUP BY user_sk, semester_sk
        ) session
        ON session.user_sk = u.user_id

        LEFT JOIN (
            SELECT
                user_sk,
                semester_sk,
                AVG(score) AS quiz_score
            FROM fact_quiz
            GROUP BY user_sk, semester_sk
        ) quiz
        ON quiz.user_sk = u.user_id

        JOIN dim_semester s ON s.semester_id = :semester_id
        WHERE u.user_id = :user_id
        ",
        'consistency' => "
        SELECT
            ROUND(
                AVG(CASE WHEN total_minutes > 0 THEN 1 ELSE 0 END) * 100
            , 2) AS consistency_score
        FROM fact_daily_progress
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        ",
        'academic_risk' => "
        SELECT
            COUNT(*) as quiz_count,
            ROUND(AVG(score/total_questions) * 100, 2) AS avg_quiz_score
        FROM fact_quiz
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        ",
        'task_management' => "
        SELECT
            SUM(CASE WHEN priority = 'high' AND status = 'pending' THEN 1 ELSE 0 END)
            AS pending_high_priority
        FROM fact_task
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        ",
        'efficiency' => "
        SELECT
            ROUND(AVG(target_duration_minutes), 2) AS avg_target_minutes,
            ROUND(AVG(duration_seconds / 60), 2) AS avg_actual_minutes
        FROM fact_study_session
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        AND status = 'completed'
        ",
        'session_stability' => "
        SELECT
            ROUND(
                AVG(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) * 100
            , 2) AS completion_rate
        FROM fact_study_session
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        ",
        'focus_window' => "
        SELECT
            start_hour,
            COUNT(*) AS session_count
        FROM fact_study_session
        WHERE user_sk = :user_id
        AND semester_sk = :semester_id
        AND status = 'completed'
        GROUP BY start_hour
        ORDER BY session_count DESC
        LIMIT 1
        ",
        'subject_performance' => "
        SELECT
            ds.name AS subject_name,
            ROUND(AVG(score/total_questions) * 100, 2) AS avg_score
        FROM fact_quiz fq
        JOIN dim_subject ds ON ds.subject_sk = fq.subject_sk
        WHERE fq.user_sk = :user_id
        AND fq.semester_sk = :semester_id
        GROUP BY ds.name
        ORDER BY avg_score DESC
        ",
        'streak' => "
        SELECT
            current_streak,
            longest_streak
        FROM users
        WHERE user_id = :user_id
        ",
        'daily_progress' => "
        SELECT
            dp.total_minutes,
            u.daily_goal_minutes,
            ROUND((dp.total_minutes / u.daily_goal_minutes) * 100, 2) AS progress_percent
        FROM users u
        LEFT JOIN fact_daily_progress dp
            ON dp.user_sk = u.user_id
            AND dp.semester_sk = :semester_id
        WHERE u.user_id = :user_id
        ORDER BY dp.date_sk DESC
        LIMIT 1
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