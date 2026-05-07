<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

$userID = $_GET['userID'];

try {
    $sql = "SELECT * FROM subjects WHERE user_id = :userID";
    $params = [
        'userID' => $userID
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