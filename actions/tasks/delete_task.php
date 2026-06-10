<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $pdo->beginTransaction();
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";

        $tasksID = isset($_GET["tasks_id"])
            ? trim($_GET["tasks_id"])
            : "";

        $semesterID = isset($_GET["semesterID"])
            ? trim($_GET["semesterID"])
            : "";

        $sql = "UPDATE tasks
        SET is_archived = 1
        WHERE user_id = :userID 
            AND semester_id = :semesterID
            AND tasks_id = :tasksID
            AND is_archived = 0";

        $params = [
            "userID" => $userID,
            "semesterID" => $semesterID,
            "tasksID" => $tasksID
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) { //to confirm if it is deleted
            echo json_encode([
                "success" => true,
                "message" => "Task deleted"
            ]);
            $pdo->commit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No changes made (same data or task not found)"
            ]);
            $pdo->rollBack();
        }
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}