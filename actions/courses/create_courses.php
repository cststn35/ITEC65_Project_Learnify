<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";

        $semesterID = isset($_GET["semesterID"])
        ? trim($_GET["semesterID"])
        : "";

        $color = isset($_POST["color"])
            ? trim(filter_var($_POST["color"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $name = isset($_POST["course"])
            ? trim(filter_var($_POST["course"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $description = isset($_POST["description"])
            ? trim($_POST["description"])
            : "";

        $sql = "INSERT INTO subjects (
            user_id,
            semester_id,
            name,
            description,
            color
        )
        VALUES (
            :user_id,
            :semester_id,
            :name,
            :description,
            :color
        )";

        $params = [
            'user_id' => $userID,
            'semester_id' => $semesterID,
            'name' => $name,
            'description' => $description,
            'color' => $color
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