<?php
session_start();
require '../config/runQuery.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'] ?? null;
$goal = $data['daily_goal'] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized."
    ]);
    exit;
}

if (!$goal || $goal < 1) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid daily goal."
    ]);
    exit;
}

runQuery($pdo, "
    UPDATE users
    SET daily_goal_minutes = :goal
    WHERE user_id = :user_id
", [
    ":goal" => $goal,
    ":user_id" => $user_id
]);

echo json_encode([
    "success" => true,
    "message" => "Daily goal updated successfully."
]);