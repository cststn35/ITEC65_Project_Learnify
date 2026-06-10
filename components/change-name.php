<?php
session_start();
require '../config/runQuery.php';

header('Content-Type: application/json');

$_first_name = trim($_POST['first_name'] ?? '');
$_last_name = trim($_POST['last_name'] ?? '');


$user_id = $_SESSION['user_id'];

if (empty($_first_name) || empty($_last_name)) {
    echo json_encode([
        'success' => false,
        'message' => 'First name and last name cannot be empty.'
    ]);
    exit;
}

$pdo->beginTransaction();

$sql = "UPDATE users SET first_name = :first_name, last_name = :last_name WHERE user_id = :user_id";
runQuery($pdo, $sql, [
    'first_name' => $_first_name,
    'last_name' => $_last_name,
    'user_id' => $user_id
]);
$pdo->commit();

echo json_encode([
    'success' => true,
    'message' => 'Name updated successfully.'
]);