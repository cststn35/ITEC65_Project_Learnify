<?php
session_start();

if (isset($_SESSION['quizzes'])) {
    unset($_SESSION['quizzes']);
    echo json_encode([
        'status' => "Quizzes unset as there are quizzes"
    ]);
    exit;
}
echo json_encode([
    'status' => "No quizzes yet"
]);

