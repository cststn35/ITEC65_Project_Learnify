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

$userID = $_SESSION['user_id'];

if (
    !isset($_FILES['profile_pic']) ||
    $_FILES['profile_pic']['error'] !== 0
) {
    echo json_encode([
        'success' => false,
        'message' => 'Please select an image.'
    ]);
    exit;
}

$file = $_FILES['profile_pic'];

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

//get old image
$sql = "
    SELECT profile_pic_path
    FROM users
    WHERE user_id = :user_id
";

$stmt = runQuery($pdo, $sql, [
    ':user_id' => $userID
]);

$user = $stmt->fetch();

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

//delete old pic
if (
    !empty($user['profile_pic_path']) &&
    file_exists('../../' . $user['profile_pic_path'])
) {
    unlink('../../' . $user['profile_pic_path']);
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

echo json_encode([
    'success' => true,
    'message' => 'Profile picture updated successfully.',
    'path' => $dbPath
]);