<?php
header("Content-Type: application/json");
require '../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $sql = "SELECT * FROM semesters WHERE user_id = :user_id";

        $params = [
            'user_id' => $userID,
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