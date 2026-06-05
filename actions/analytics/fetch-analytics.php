<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/db.php';

function getDateFilter($view, $startDate = null, $endDate = null, $column = "dd.full_date")
{
    switch ($view) {
        case "weekly":
            return "AND $column >= CURDATE() - INTERVAL 6 DAY";

        case "monthly":
            return "AND $column >= CURDATE() - INTERVAL 29 DAY";

        case "periodical":
            return "AND $column BETWEEN '$startDate' AND '$endDate'";

        default:
            return "1=1"; // no filtering
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";

        $filters = json_decode($_POST['filters'], true);

        $semesterRangeStmt = $pdo->prepare("
    SELECT start_date, end_date
    FROM semesters
    WHERE semester_id = :semester_id
");
        $semesterRangeStmt->execute([
            ":semester_id" => $semesterID
        ]);
        $semesterRange = $semesterRangeStmt->fetch();

        $startDate = $semesterRange["start_date"];
        $endDate = $semesterRange["end_date"];
        //study trend view
        $studyTrendView['date_filter'] = getDateFilter($filters['studyTrendView']['time'], $startDate, $endDate);
        $studyTrendView['subject_id'] =
            ($filters['studyTrendView']['subject_id'] == "all")
            ? ""
            : "AND dss.subject_id = " . $filters['studyTrendView']['subject_id'];
        $studyTrendView['total_minutes'] = "SUM(fss.duration_seconds) as total_seconds";
        if ($filters['studyTrendView']['time'] == "weekly" || $filters['studyTrendView']['time'] == "monthly") {
            $studyTrendView['group_by'] = "GROUP BY dd.date_sk";
        } else if ($filters['studyTrendView']['time'] == "periodical") {
            $studyTrendView['group_by'] = "GROUP BY dd.month";
        }

        //study subject view
        $studySubjectView['date_filter'] = getDateFilter($filters['studySubjectView']['time'], $startDate, $endDate);

        //plan study view
        $planStudyView['subject_id'] = ($filters['planStudyView']['subject_id'] == "all")
            ? ""
            : "AND dss.subject_id = " . $filters['planStudyView']['subject_id'];

        //task trend view
        $taskTrendView['subject_id'] = ($filters['taskTrendView']['subject_id'] == "all")
            ? ""
            : "AND dss.subject_id = " . $filters['taskTrendView']['subject_id'];

        //quiz trend view
        $quizView['date_filter'] = getDateFilter($filters['quizView']['time'], $startDate, $endDate);
        $quizView['subject_id'] =
            ($filters['quizView']['subject_id'] == "all")
            ? ""
            : "AND dss.subject_id = " . $filters['quizView']['subject_id'];
        if ($filters['quizView']['time'] == "weekly" || $filters['quizView']['time'] == "monthly") {
            $quizView['group_by'] = "GROUP BY dd.full_date";
        } else if ($filters['quizView']['time'] == "periodical") {
            $quizView['group_by'] = "GROUP BY dd.month";
        }

        //xp trend view
        $xpView['date_filter'] = getDateFilter($filters['xpView']['time'], $startDate, $endDate);
        if ($filters['xpView']['time'] == "weekly" || $filters['xpView']['time'] == "monthly") {
            $xpView['group_by'] = "GROUP BY dd.full_date";
        } else if ($filters['xpView']['time'] == "periodical") {
            $xpView['group_by'] = "GROUP BY dd.month";
        }

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
            dd.month,
            dd.weekday,"
                . $studyTrendView['total_minutes'] .
                " FROM fact_study_session fss
        JOIN dim_date dd ON fss.date_sk = dd.date_sk
        JOIN dim_user du ON fss.user_sk = du.user_sk
        JOIN dim_semester ds ON fss.semester_sk = ds.semester_sk
        JOIN dim_subject dss ON fss.subject_sk = dss.subject_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id "
                . $studyTrendView['subject_id']
                . " "
                . $studyTrendView['date_filter']
                . " "
                . $studyTrendView['group_by']
                . " ORDER BY dd.full_date"
            ,

            // 6. STUDY BY SUBJECT
            "study_by_subject" => "
        SELECT
            dsub.name,
            ROUND(SUM(duration_seconds) / 60, 2) AS total_hours
        FROM fact_study_session fs
        JOIN dim_subject dsub ON fs.subject_sk = dsub.subject_sk
        JOIN dim_user du ON fs.user_sk = du.user_sk
        JOIN dim_semester ds ON fs.semester_sk = ds.semester_sk
        JOIN dim_date dd ON fs.date_sk = dd.date_sk
        WHERE du.user_id = :user_id" . " " .
                $studySubjectView['date_filter'] . " " .
                "AND ds.semester_id = :semester_id
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
        JOIN dim_subject dss on fs.subject_sk = dss.subject_sk
        WHERE du.user_id = :user_id
        AND ds.semester_id = :semester_id" . " " .
                $planStudyView['subject_id'] . " " .
                "AND fs.status = 'completed'
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
        JOIN dim_subject dss ON ft.subject_sk = dss.subject_sk
        WHERE du.user_id = :user_id" . " " .
                $taskTrendView['subject_id'] . " " .
                "AND ds.semester_id = :semester_id
        AND ft.status = 'completed'
    ",

            // 13. QUIZ TREND
            "quiz_trend" =>
                "SELECT
                dd.month,
                dd.full_date,
                dd.weekday,
                ROUND(AVG(score * 100.0 / total_questions), 2) AS avg_score
            FROM fact_quiz fq
            JOIN dim_date dd ON fq.date_sk = dd.date_sk
            JOIN dim_user du ON fq.user_sk = du.user_sk
            JOIN dim_semester ds ON fq.semester_sk = ds.semester_sk
            JOIN dim_subject dss ON fq.subject_sk = dss.subject_sk
            WHERE du.user_id = :user_id
            AND ds.semester_id = :semester_id "
                . $quizView['subject_id'] . " "
                . $quizView['date_filter'] . " "
                . $quizView['group_by'] . "
            ORDER BY dd.full_date",
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
            ROUND(s.actual_duration_seconds / 60, 2) AS study_minutes,
            ROUND(q.score * 100.0 / q.score, 2) AS quiz_percent
        FROM quizzes q
        JOIN sessions s ON q.session_id = s.session_id
        WHERE s.user_id = :user_id
        AND s.semester_id = :semester_id
        AND q.status = 'completed'
    ",

            // 16. XP GROWTH
            "xp_growth" =>
                "SELECT
                dd.month,
                dd.full_date,
                dd.weekday,
                SUM(fx.xp_change) AS total_xp
            FROM fact_xp fx
            JOIN dim_date dd ON fx.date_sk = dd.date_sk
            JOIN dim_user du ON fx.user_sk = du.user_sk
            JOIN dim_semester ds ON fx.semester_sk = ds.semester_sk
            WHERE du.user_id = :user_id
            AND ds.semester_id = :semester_id "
                . $xpView['date_filter'] . " "
                . $xpView['group_by'] . "
            ORDER BY dd.full_date",
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
    ",
            "subject_filter" => "
        SELECT subject_id, name
        FROM subjects
        WHERE user_id = :user_id
        AND semester_id = :semester_id
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
}

