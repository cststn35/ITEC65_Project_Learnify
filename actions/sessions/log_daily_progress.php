<?php
date_default_timezone_set('Asia/Manila');
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";
        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";

        $now = new DateTime();
        $date = $now->format("Y-m-d");

        $actualDurationSeconds = isset($_POST["actual_duration_seconds"])
            ? trim($_POST["actual_duration_seconds"])
            : 0;
        $actualDurationSeconds = floor($actualDurationSeconds / 60);

        $sql = "INSERT INTO daily_progress (
            user_id,
            semester_id,
            date,
            total_minutes
        )
        VALUES (
            :user_id,
            :semester_id,
            :date,
            :total_minutes
        )
        ON DUPLICATE KEY UPDATE
            total_minutes = total_minutes + VALUES(total_minutes);";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'date' => $date,
            'total_minutes' => $actualDurationSeconds
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) {
            echo json_encode([
                'success' => true
            ]);
            unset($_SESSION['session_id']);
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