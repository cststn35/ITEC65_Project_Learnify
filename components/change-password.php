<?php
session_start();
require '../config/runQuery.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized.'
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$current = $data['current_password'] ?? '';
$new = $data['new_password'] ?? '';
$confirm = $data['confirm_password'] ?? '';

if (!$current || !$new || !$confirm) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required.'
    ]);
    exit;
}

if ($new !== $confirm) {
    echo json_encode([
        'success' => false,
        'message' => 'Passwords do not match.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// get user password
$sql = "SELECT password_hash FROM users WHERE user_id = :user_id";
$stmt = runQuery($pdo, $sql, [
    ':user_id' => $user_id
]);

$user = $stmt->fetch();

if (!$user) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
    exit;
}

// verify current password
if (!password_verify($current, $user['password_hash'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Current password is incorrect.'
    ]);
    exit;
}

// hash new password
$newHash = password_hash($new, PASSWORD_DEFAULT);

// update password
$sql = "
    UPDATE users
    SET password_hash = :password_hash
    WHERE user_id = :user_id
";

runQuery($pdo, $sql, [
    ':password_hash' => $newHash,
    ':user_id' => $user_id
]);

echo json_encode([
    'success' => true,
    'message' => 'Password updated successfully.'
]);