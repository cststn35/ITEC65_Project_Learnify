<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_GET["userID"])
            ? trim($_GET["userID"])
            : "";

        $courseID = isset($_GET["course_id"])
            ? trim($_GET["course_id"])
            : "";

        $semesterID = isset($_GET["semester_id"])
            ? trim($_GET["semester_id"])
            : "";

        $title = isset($_POST["course"])
            ? trim(filter_var($_POST["course"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $description = isset($_POST["description"])
            ? trim(filter_var($_POST["description"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";
        $color = isset($_POST["color"])
            ? trim($_POST["color"])
            : "";

        $sql = "UPDATE subjects  
        SET name = :title, 
            description = :description, 
            color = :color
        WHERE user_id = :userID 
            AND semester_id = :semesterID
            AND subject_id = :subjectID
            AND is_archived = 0";

        $params = [
            "title" => $title,
            "description" => $description,
            "color" => $color,
            "userID" => $userID,
            "semesterID" => $semesterID,
            "subjectID" => $courseID
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() > 0) { //to confirm if it is updated
            echo json_encode([
                "success" => true,
                "message" => "Task updated"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No changes made (same data or task not found)"
            ]);
        }
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}