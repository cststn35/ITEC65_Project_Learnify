<?php
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
        $description = isset($_POST["description"])
            ? trim(filter_var($_POST["description"], FILTER_SANITIZE_SPECIAL_CHARS))
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

        $sql = "INSERT INTO tasks (
            user_id,
            subject_id,
            semester_id,
            title,
            description,
            deadline,
            priority,
            estimated_seconds
        )
        VALUES (
            :user_id,
            :subject_id,
            :semester_id,
            :title,
            :description,
            :deadline,
            :priority,
            :estimated_seconds
        )";
        $params = [
            'user_id' => $userID,
            'subject_id' => $subject,
            'semester_id' => $semesterID,
            'title' => $title,
            'description' => $description,
            'deadline' => $deadline,
            'priority' => $priority,
            'estimated_seconds' => $time
        ];
        $result = runQuery($pdo, $sql, $params);

        echo json_encode([
            'success' => true,
            'data' => $result
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}