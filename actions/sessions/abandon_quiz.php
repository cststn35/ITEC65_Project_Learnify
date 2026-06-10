<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $pdo->beginTransaction();
        $quizID = isset($_POST["quizID"])
            ? trim($_POST["quizID"])
            : "";

        $sql = "UPDATE quizzes SET status = 'abandoned' WHERE quiz_id = :quiz_id";
        $params = [
            'quiz_id' => $quizID
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() < 0) {
            echo json_encode([
                'success' => false
            ]);
            $pdo->rollBack();
            exit;
        }

        unset($_SESSION['quiz_id']);
        echo json_encode([
            'success' => true
        ]);
        $pdo->commit();
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}