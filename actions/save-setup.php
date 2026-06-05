<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $userID = isset($_POST["userID"])
            ? trim($_POST["userID"])
            : "";

        $startDate = isset($_POST["start_date"])
            ? trim($_POST["start_date"])
            : "";

        $endDate = isset($_POST["end_date"])
            ? trim($_POST["end_date"])
            : "";

        $periodName = isset($_POST["period_name"])
            ? trim($_POST["period_name"])
            : "";

        $yearLevel = isset($_POST["year_level"])
            ? trim($_POST["year_level"])
            : "";

        $dailyGoal = isset($_POST["daily_goal"])
            ? trim($_POST["daily_goal"])
            : "";

        //upload semester

        $sql = "INSERT INTO semesters (
            user_id,
            semester_name,
            school_year,
            start_date,
            end_date,
            is_active
        )
        VALUES (
            :user_id,
            :semester_name,
            :school_year,
            :start_date,
            :end_date,
            1
        )";

        $params = [
            'user_id' => $userID,
            'semester_name' => $periodName,
            'school_year' => $yearLevel,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        $result = runQuery($pdo, $sql, $params);
        $semester_id = $pdo->lastInsertId();

        //update daily goal
        $sql = "UPDATE users SET daily_goal_minutes = :daily_goal_minutes WHERE user_id = :user_id";
        $params = [
            'user_id' => $userID,
            'daily_goal_minutes' => $dailyGoal,
        ];
        $result = runQuery($pdo, $sql, $params);

        //change into non-newly registered
        $pdo->prepare("
            UPDATE users 
            SET is_newly_registered = 0 
            WHERE user_id = ?
        ")->execute([$userID]);

        //upload profile pic
        if (
            !isset($_FILES['profile']) ||
            $_FILES['profile']['error'] !== 0
        ) {
            echo json_encode([
                'success' => true,
                'message' => 'Setup save.'
            ]);
            $_SESSION['semester_id'] = (int) $semester_id;
            $_SESSION['is_newly_registered'] = 0;
            exit;
        }

        $file = $_FILES['profile'];

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        $extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        if (!in_array($extension, $allowedExtensions)) {

            echo json_encode([
                'success' => false,
                'message' => 'Only JPG, PNG, and WEBP are allowed.'
            ]);
            exit;
        }

        $uploadDirectory = '../uploads/profile_pics/';

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $fileName =
            'profile_' .
            $userID .
            '_' .
            time() .
            '.' .
            $extension;

        $fullPath = $uploadDirectory . $fileName;

        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $fullPath
            )
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to upload image.'
            ]);
            exit;
        }

        //relative path for db
        $dbPath = 'uploads/profile_pics/' . $fileName;

        //update db
        $sql = "
            UPDATE users
            SET profile_pic_path = :profile_pic_path
            WHERE user_id = :user_id
        ";

        runQuery($pdo, $sql, [
            ':profile_pic_path' => $dbPath,
            ':user_id' => $userID
        ]);

        $_SESSION['semester_id'] = (int) $semester_id;
        $_SESSION['is_newly_registered'] = 0;

        echo json_encode([
            'success' => true,
            'message' => 'Profile picture updated successfully.',
            'path' => $dbPath
        ]);


    }

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}