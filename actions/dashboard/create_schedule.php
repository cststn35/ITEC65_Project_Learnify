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

        $subject = isset($_POST["subject"])
            ? trim($_POST["subject"])
            : "";

        $day = isset($_POST["day"])
            ? trim($_POST["day"])
            : "";
        $startTime = isset($_POST["startTime"])
            ? trim($_POST["startTime"])
            : "";
        $endTime = isset($_POST["endTime"])
            ? trim($_POST["endTime"])
            : "";
        $room = isset($_POST["room"])
            ? trim(filter_var($_POST["room"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";

        $teacher = isset($_POST["teacher"])
            ? trim(filter_var($_POST["teacher"], FILTER_SANITIZE_SPECIAL_CHARS))
            : "";

        //check if there will be conflicting schedule
        $sql1 = "
            SELECT schedule_id
            FROM schedules
            WHERE user_id = :user_id
            AND semester_id = :semester_id
            AND day_of_week = :day_of_week
            AND start_time < :new_end_time
            AND end_time > :new_start_time
            LIMIT 1
        ";

        $params1 = [
            ':user_id' => $userID,
            ':semester_id' => $semesterID,
            ':day_of_week' => $day,
            ':new_end_time' => $endTime,
            ':new_start_time' => $startTime
        ];

        $result = runQuery($pdo, $sql1, $params1);

        if ($result->rowCount() > 0) {
            echo json_encode([
                'success' => false,
            ]);
            exit;
        }

        //if not, proceed to creation of schedule

        $sql2 = "INSERT INTO schedules (
            user_id,
            subject_id,
            semester_id,
            day_of_week,
            start_time,
            end_time,
            room,
            teacher
        )
        VALUES (
            :user_id,
            :subject_id,
            :semester_id,
            :day_of_week,
            :start_time,
            :end_time,
            :room,
            :teacher
        )";

        $params2 = [
            'user_id' => $userID,
            'subject_id' => $subject,
            'semester_id' => $semesterID,
            'day_of_week' => $day,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room' => $room,
            'teacher' => $teacher
        ];

        $result = runQuery($pdo, $sql2, $params2);

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