<?php
header("Content-Type: application/json");
date_default_timezone_set('Asia/Manila');
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";
        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";
        $sessionID = isset($_POST["sessionID"])
            ? trim($_POST["sessionID"])
            : "";
        $now = new DateTime();
        $start_time = $now->format("Y-m-d H:i:s");

        $sql = "UPDATE sessions SET start_time = :start_time WHERE user_id = :user_id AND session_id = :session_id AND semester_id = :semester_id AND (status = 'active' OR status = 'paused')";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'session_id' => $sessionID,
            'start_time' => $start_time
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}