<?php
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

        $sql = "SELECT session_id, status FROM sessions WHERE user_id = :user_id AND semester_id = :semester_id AND (status = 'active' OR status = 'paused')";
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