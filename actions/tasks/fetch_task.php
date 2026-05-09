<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    $userID = isset($_GET["userID"])
        ? trim($_GET["userID"])
        : "";

    $semesterID = 1; // mockup only

    $tasksID = isset($_GET["tasks_id"])
        ? trim($_GET["tasks_id"])
        : "";

    if ($tasksID) { //fetching for editing
        $sql = "SELECT 
            t.tasks_id, 
            t.user_id, 
            t.subject_id, 
            t.semester_id, 
            t.title, 
            t.description, 
            t.deadline, 
            t.priority, 
            t.estimated_seconds
        FROM tasks t 
        INNER JOIN subjects s 
            ON t.subject_id = s.subject_id 
        WHERE t.user_id = :userID 
            AND t.semester_id = :semesterID
            AND t.tasks_id = :tasksID
            AND t.is_archived = 0";
        $params = [
            'userID' => $userID,
            'semesterID' => $semesterID,
            'tasksID' => $tasksID
        ];
    } else { //fetching for tasks rendering
        $sql = "SELECT 
            t.tasks_id, 
            t.user_id, 
            t.subject_id, 
            t.semester_id, 
            t.title, 
            t.description, 
            t.deadline, 
            t.priority, 
            t.estimated_seconds, 
            t.status, 
            s.color,
            s.name
        FROM tasks t 
        INNER JOIN subjects s 
            ON t.subject_id = s.subject_id 
        WHERE t.user_id = :userID 
            AND t.semester_id = :semesterID 
            AND t.is_archived = 0
        ORDER BY t.tasks_id DESC";
        $params = [
            'userID' => $userID,
            'semesterID' => $semesterID
        ];
    }

    $result = runQuery($pdo, $sql, $params, true);

    echo json_encode([
        'success' => true,
        'data' => $result
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}