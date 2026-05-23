<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $scheduleID = isset($_GET["scheduleID"])
            ? trim($_GET["scheduleID"])
            : "";

        $sql = "SELECT s.*,ss.name FROM schedules s JOIN subjects ss ON s.subject_id = ss.subject_id  WHERE s.schedule_id = :schedule_id";

        $params = [
            'schedule_id' => $scheduleID,
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