<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    $userID = isset($_GET["userID"])
        ? trim($_GET["userID"])
        : "";

    $semesterID = isset($_GET["semesterID"])
        ? trim($_GET["semesterID"])
        : "";

    $courseID = isset($_GET["course_id"])
        ? trim($_GET["course_id"])
        : "";

    if ($courseID) {
        //fetching for editing courses
        $sql = "SELECT 
            color, 
            name, 
            description
        FROM subjects
        WHERE user_id = :userID 
            AND semester_id = :semesterID
            AND subject_id = :subjectID
            AND is_archived = 0";
        $params = [
            'userID' => $userID,
            'semesterID' => $semesterID,
            'subjectID' => $courseID
        ];

        $result = runQuery($pdo, $sql, $params, true);

        echo json_encode([
            'success' => true,
            'data' => $result,
        ]);
    } else {
        //fetching for courses rendering
        $sql = "SELECT 
            s.subject_id,
            s.name,
            s.description,
            s.color,
            s.is_archived,
            COUNT(t.tasks_id) AS task_count
        FROM subjects s
        LEFT JOIN tasks t
            ON s.subject_id = t.subject_id
            AND t.is_archived = 0
        WHERE s.user_id = :userID
            AND s.semester_id = :semesterID
            AND s.is_archived = 0
        GROUP BY s.subject_id, s.name, s.description, s.color
            ";

        $params = [
            'userID' => $userID,
            'semesterID' => $semesterID
        ];

        $sql2 = "SELECT
        COUNT(CASE WHEN is_archived = 0 THEN 1 END) AS total_subjects,
        COUNT(CASE WHEN is_archived = 1 THEN 1 END) AS archived_subjects
        FROM subjects
        WHERE user_id = :userID
        AND semester_id = :semesterID";

        $result = runQuery($pdo, $sql, $params, true);
        $result2 = runQuery($pdo, $sql2, $params, true);

        echo json_encode([
            'success' => true,
            'data' => $result,
            'data2' => $result2
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}