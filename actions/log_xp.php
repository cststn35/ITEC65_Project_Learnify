<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";

        $reason = isset($_POST["reason"])
            ? trim($_POST["reason"])
            : "";

        $xp = isset($_POST["xp"])
            ? trim($_POST["xp"])
            : "";

        $sql = "INSERT INTO xp_logs (
            user_id,
            semester_id,
            xp_change,
            reason
        )
        VALUES (
            :user_id,
            :semester_id,
            :xp_change,
            :reason
        )";
        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'xp_change' => $xp,
            'reason' => $reason
        ];
        $result = runQuery($pdo, $sql, $params);

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