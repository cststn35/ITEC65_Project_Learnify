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

        $sql = "SELECT s.*,ss.name FROM schedules s JOIN subjects ss ON s.subject_id = ss.subject_id  WHERE s.user_id = :user_id AND s.semester_id = :semester_id ORDER BY s.day_of_week, s.start_time";

        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
        ];

        $result = runQuery($pdo, $sql, $params, true);

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