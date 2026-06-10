<?php
session_start();
header("Content-Type: application/json");
require '../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $pdo->beginTransaction();
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $semesterID = isset($_POST["semesterID"])
            ? trim($_POST["semesterID"])
            : "";

        $sql1 = "UPDATE semesters SET is_active = 0 WHERE user_id = :user_id";

        $sql2 = "UPDATE semesters SET is_active = 1 WHERE user_id = :user_id AND semester_id = :semester_id";

        $params1 = [
            'user_id' => $userID,
        ];

        $params2 = [
            'user_id' => $userID,
            'semester_id' => $semesterID
        ];

        $result = runQuery($pdo, $sql1, $params1);
        $result = runQuery($pdo, $sql2, $params2);

        $_SESSION['semester_id'] = (int) $semesterID;

        echo json_encode([
            'success' => true,
            'data' => $result
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