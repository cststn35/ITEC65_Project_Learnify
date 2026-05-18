<?php
session_start();
header("Content-Type: application/json");
require_once __DIR__ . '/../../config/runQuery.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $quizID = isset($_POST["quizID"])
            ? trim($_POST["quizID"])
            : "";
        $studAnswer = json_decode($_POST['studAnswer'], true);

        $score = isset($_POST["score"])
            ? trim($_POST["score"])
            : "";

        $total_questions = isset($_POST["total_questions"])
            ? trim($_POST["total_questions"])
            : "";

        $sql = "UPDATE quizzes SET status = 'completed', score = :score, total_questions = :total_questions WHERE quiz_id = :quiz_id";
        $params = [
            'quiz_id' => $quizID,
            'score' => $score,
            'total_questions' => $total_questions
        ];

        $result = runQuery($pdo, $sql, $params);

        if ($result->rowCount() < 0) {
            echo json_encode([
                'success' => false
            ]);
        }

        $sql2 = "UPDATE questions SET my_answer = :answer WHERE id = :question_id";

        foreach ($studAnswer as $answer) {
            $params = [
                'answer' => $answer['answer'],
                'question_id' => $answer['id']
            ];

            $result = runQuery($pdo, $sql2, $params);

            if ($result->rowCount() < 0) {
                echo json_encode([
                    'success' => false
                ]);
                exit;
            }
        }

        unset($_SESSION['quiz_id']);
        echo json_encode([
            'success' => true
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}