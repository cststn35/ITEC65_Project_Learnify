<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
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

        $title = isset($_POST["title"])
            ? trim(filter_var($_POST["title"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $description = isset($_POST["descriptionTask"])
            ? trim(filter_var($_POST["descriptionTask"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $subject = isset($_POST["subject"])
            ? trim($_POST["subject"])
            : "";
        $deadline = isset($_POST["deadline"])
            ? trim(filter_var($_POST["deadline"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $priority = isset($_POST["priority"])
            ? trim(filter_var($_POST["priority"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $time = isset($_POST["time"])
            ? trim(filter_var($_POST["time"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";

        if ($time) {
            $time = ((int) $time) * 60; //time stored as seconds
        }

        $sql = "UPDATE tasks 
        SET title = :title, 
            description = :description, 
            subject_id = :subject, 
            deadline = :deadline, 
            priority = :priority, 
            estimated_seconds = :time
        WHERE user_id = :userID 
            AND semester_id = :semesterID
            AND tasks_id = :tasksID
            AND is_archived = 0";

        $params = [
            "title" => $title,
            "description" => $description,
            "subject" => $subject,
            "deadline" => $deadline,
            "priority" => $priority,
            "time" => $time,
            "userID" => $userID,
            "semesterID" => $semesterID,
            "tasksID" => $tasksID
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) { //to confirm if it is updated
            echo json_encode([
                "success" => true,
                "message" => "Task updated"
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