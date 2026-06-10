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
        $sessionID = isset($_POST["sessionID"])
            ? trim($_POST["sessionID"])
            : "";

        $start_time = isset($_POST["pause_start_time"])
            ? trim($_POST["pause_start_time"])
            : "";

        $totalPauseSeconds = isset($_POST["total_pause_seconds"])
            ? trim($_POST["total_pause_seconds"])
            : 0;

        $actualDurationSeconds = isset($_POST["actual_duration_seconds"])
            ? trim($_POST["actual_duration_seconds"])
            : 0;

        $status = isset($_POST['from_resume']) ? "active" : "paused";

        $session_notes = isset($_POST['session_notes']) ? trim($_POST['session_notes']) : "";

        $sql = "UPDATE sessions SET pause_start_time = :start_time, total_pause_seconds = :total_pause_seconds, actual_duration_seconds = :actual_duration_seconds, status = :status, session_notes = :session_notes WHERE user_id = :user_id AND session_id = :session_id AND semester_id = :semester_id AND (status = 'active' OR status = 'paused')";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'session_id' => $sessionID,
            'start_time' => $start_time,
            'total_pause_seconds' => $totalPauseSeconds,
            'actual_duration_seconds' => $actualDurationSeconds,
            'status' => $status,
            'session_notes' => $session_notes
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