<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "GET") {
        $pdo->beginTransaction();
        $sessionID = $_GET['sessionID'];

        $sql = "UPDATE sessions SET status = 'invalidated' WHERE session_id = :session_id";
        $params = [
            'session_id' => $sessionID
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() < 0) {
            echo json_encode([
                'success' => false
            ]);
            $pdo->rollBack();
            exit;
        }

        unset($_SESSION['session_id']);
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