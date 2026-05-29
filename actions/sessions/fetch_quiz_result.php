<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $quizID = isset($_GET["quizID"])
            ? trim($_GET["quizID"])
            : "";

        $sql = "SELECT * FROM questions WHERE quiz_id = :quiz_id";
        $params = [
            'quiz_id' => $quizID,
        ];

        $result = runQuery($pdo, $sql, $params, true);

        if (empty($result)) {
            echo json_encode([
                'success' => false,
                'data' => $result
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}