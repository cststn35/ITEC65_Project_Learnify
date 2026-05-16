<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";
        $semesterID = isset($_GET["semesterID"])
            ? trim($_GET["semesterID"])
            : "";
        $title = isset($_POST["title"])
            ? trim(filter_var($_POST["title"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $taskID = isset($_POST["task"])
            ? trim($_POST["task"])
            : null;
        if ($taskID == "N/A") {
            $taskID = null;
        }
        $subjectID = isset($_POST["subject"])
            ? trim($_POST["subject"])
            : "";
        $time = isset($_POST["time"])
            ? trim(filter_var($_POST["time"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $questionCount = isset($_POST["question_count"])
            ? trim($_POST["question_count"])
            : null;
        $fileName = isset($_POST["file_name"])
            ? trim(filter_var($_POST["file_name"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";

        $sql = "INSERT INTO sessions (
            title,
            user_id,
            task_id,
            subject_id,
            semester_id,
            target_duration_minutes,
            question_count,
            file_name
        )
        VALUES (
            :title,
            :user_id,
            :task_id,
            :subject_id,
            :semester_id,
            :target_time,
            :question_count,
            :file_name
        )";

        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'task_id' => $taskID,
            'subject_id' => $subjectID,
            'target_time' => $time,
            'title' => $title,
            'question_count' => $questionCount,
            'file_name' => $fileName
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) {
            $_SESSION["session_id"] = $pdo->lastInsertId();
            if (isset($_SESSION["session_id"])) {
                echo json_encode([
                    'success' => true,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}