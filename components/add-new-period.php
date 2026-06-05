<?php
header("Content-Type: application/json");
require '../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $periodName = isset($_POST["current_period"])
            ? trim($_POST["current_period"])
            : "";

        $year = isset($_POST["year_level"])
            ? trim($_POST["year_level"])
            : "";

        $startDate = isset($_POST["start_date"])
            ? trim($_POST["start_date"])
            : "";

        $endDate = isset($_POST["end_date"])
            ? trim($_POST["end_date"])
            : "";


        $sql = "INSERT INTO semesters (
            user_id,
            semester_name,
            school_year,
            start_date,
            end_date
        )
        VALUES (
            :user_id,
            :semester_name,
            :school_year,
            :start_date,
            :end_date
        )";

        $params = [
            'user_id' => $userID,
            'semester_name' => $periodName,
            'school_year' => $year,
            'start_date' => $startDate,
            'end_date' => $endDate
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