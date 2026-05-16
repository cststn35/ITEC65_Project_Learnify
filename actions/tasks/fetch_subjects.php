<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

$userID = $_GET['userID'];
$semesterID = $_GET['semester_id'];

try {
    $sql = "SELECT * FROM subjects WHERE user_id = :userID AND semester_id = :semesterID AND is_archived = 0";
    $params = [
        'userID' => $userID,
        'semesterID' => $semesterID
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