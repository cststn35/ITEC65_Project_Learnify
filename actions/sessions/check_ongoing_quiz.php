<?php
session_start();
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

        $sql = "SELECT q.quiz_id, q.status FROM quizzes q INNER JOIN sessions s ON q.session_id = s.session_id WHERE s.user_id = :user_id AND s.semester_id = :semester_id AND q.status = 'in_progress'";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
        ];

        $result = runQuery($pdo, $sql, $params, true);

        if (empty($result)) {
            echo json_encode([
                'success' => true,
                'isOngoing' => false
            ]);
        } else {
            unset($_SESSION['quiz_id']);
            if (!isset($_SESSION['quiz_id'])) {
                $_SESSION['quiz_id'] = $result[0]['quiz_id'];
            }
            echo json_encode([
                'success' => true,
                'isOngoing' => true
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}