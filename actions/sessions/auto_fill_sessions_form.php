<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    $taskID = isset($_GET["taskID"])
        ? trim($_GET["taskID"])
        : "";

    $sql = "SELECT subject_id, title, estimated_seconds FROM tasks WHERE tasks_id = :task_id";
    $params = [
        'task_id' => $taskID
    ];
    $result = runQuery($pdo, $sql, $params, true);

    echo json_encode([
        'success' => true,
        'data' => $result
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}