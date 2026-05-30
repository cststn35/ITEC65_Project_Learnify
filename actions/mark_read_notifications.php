<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";

        $notifID = isset($_POST["notifID"])
            ? trim($_POST["notifID"])
            : "";

        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND semester_id = :semester_id AND notif_id = :notif_id";

        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'notif_id' => $notifID
        ];

        $result = runQuery($pdo, $sql, $params);

        echo json_encode([
            'success' => true,
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}