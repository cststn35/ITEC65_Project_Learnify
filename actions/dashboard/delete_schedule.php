<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $pdo->beginTransaction();
        $scheduleID = isset($_GET["scheduleID"])
            ? trim($_GET["scheduleID"])
            : "";

        $sql = "DELETE FROM schedules
        WHERE schedule_id = :schedule_id
        ";

        $params = [
            "schedule_id" => $scheduleID,
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) { //to confirm if it is deleted
            echo json_encode([
                "success" => true,
                "message" => "Task deleted"
            ]);
            $pdo->commit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No changes made (same data or task not found)"
            ]);
            $pdo->rollBack();
        }
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}