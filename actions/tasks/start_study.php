<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $tasksID = isset($_GET["taskID"])
            ? trim($_GET["taskID"])
            : "";

        $sql = "SELECT 
        tasks_id,
        subject_id,
        title,
        estimated_seconds
        FROM tasks
        WHERE tasks_id = :tasksID";

        $params = [
            "tasksID" => $tasksID,
        ];

        $result = runQuery($pdo, $sql, $params);
        $result = $result->fetch();

        if (!empty($result)) { //to confirm if it is fetched
            unset($_SESSION['start_study']);
            if (!isset($_SESSION['start_study'])) {
                $_SESSION['start_study'] = [
                    'tasks_id' => $result['tasks_id'],
                    'subject_id' => $result['subject_id'],
                    'title' => $result['title'],
                    'estimated_seconds' => $result['estimated_seconds']
                ];
            }
            echo json_encode([
                "success" => true
            ]);
        } else {
            echo json_encode([
                "success" => false
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}