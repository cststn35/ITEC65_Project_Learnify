<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";
        $semesterID = isset($_GET["semesterID"])
            ? trim($_GET["semesterID"])
            : "";
        $sessionID = isset($_GET["session_id"])
            ? trim($_GET["session_id"])
            : "";

        $sql = "SELECT
                s.*,
                t.title AS task_title,
                sub.name AS subject_name
            FROM sessions s
            LEFT JOIN tasks t
                ON s.task_id = t.tasks_id
            LEFT JOIN subjects sub
                ON s.subject_id = sub.subject_id
            WHERE s.user_id = :user_id
            AND s.session_id = :session_id
            AND s.semester_id = :semester_id
            AND s.status IN ('active', 'paused')";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'session_id' => $sessionID
        ];

        $result = runQuery($pdo, $sql, $params, true);

        if (empty($result)) {
            echo json_encode([
                'success' => false,
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}