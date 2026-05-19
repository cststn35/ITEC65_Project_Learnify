<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";

        $tasksID = isset($_GET["taskID"])
            ? trim($_GET["taskID"])
            : "";

        $semesterID = isset($_GET["semesterID"])
            ? trim($_GET["semesterID"])
            : "";

        $now = new DateTime();
        $end_time = $now->format("Y-m-d H:i:s");

        $sql = "UPDATE tasks 
        SET status = 'completed', 
        completed_at = :completed_at
        WHERE user_id = :userID 
            AND semester_id = :semesterID
            AND tasks_id = :tasksID
            AND is_archived = 0";

        $params = [
            "userID" => $userID,
            "semesterID" => $semesterID,
            "tasksID" => $tasksID,
            "completed_at" => $end_time
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) { //to confirm if it is updated
            echo json_encode([
                "success" => true,
                "message" => "Task mark as done"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No changes made (same data or task not found)"
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}