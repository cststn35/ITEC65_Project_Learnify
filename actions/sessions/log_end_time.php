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

        $now = new DateTime();
        $end_time = $now->format("Y-m-d H:i:s");

        $totalPauseSeconds = isset($_POST["total_pause_seconds"])
            ? trim($_POST["total_pause_seconds"])
            : 0;

        $actualDurationSeconds = isset($_POST["actual_duration_seconds"])
            ? trim($_POST["actual_duration_seconds"])
            : 0;

        $session_notes = isset($_POST['session_notes']) ? trim($_POST['session_notes']) : "";

        $sql = "UPDATE sessions SET end_time = :end_time, total_pause_seconds = :total_pause_seconds, actual_duration_seconds = :actual_duration_seconds, status = 'completed', session_notes = :session_notes WHERE user_id = :user_id AND session_id = :session_id AND semester_id = :semester_id AND (status = 'active' OR status = 'paused')";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'session_id' => $sessionID,
            'end_time' => $end_time,
            'total_pause_seconds' => $totalPauseSeconds,
            'actual_duration_seconds' => $actualDurationSeconds,
            'session_notes' => $session_notes
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