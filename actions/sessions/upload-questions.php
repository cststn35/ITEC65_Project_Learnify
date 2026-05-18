<?php
session_start();
require_once __DIR__ . '/../../config/runQuery.php';

if (isset($_SESSION['quizzes'])) {
    try {
        //upload questions into database
        $sessionID = isset($_GET["sessionID"])
            ? trim($_GET["sessionID"])
            : "";

        if (isset($_SESSION["quizzes"])) {
            $result = runQuery($pdo, "
    INSERT INTO quizzes (session_id)
    VALUES (:session_id);
        ", [
                "session_id" => $sessionID,
            ]);

            $quizID = $pdo->lastInsertId();

            $sql = "INSERT INTO questions (quiz_id, question, choice_a, choice_b, choice_c, choice_d, correct_answer)
VALUES (:quiz_id, :question, :choice_a, :choice_b, :choice_c, :choice_d, :correct_answer)";

            foreach ($_SESSION["quizzes"] as $q) {
                if (
                    !isset($q["question"]) ||
                    !isset($q["choice_a"]) ||
                    !isset($q["choice_b"]) ||
                    !isset($q["choice_c"]) ||
                    !isset($q["choice_d"]) ||
                    !isset($q["correct_answer"])
                ) {
                    continue;
                }

                $params = [
                    "quiz_id" => $quizID,
                    "question" => $q["question"],
                    "choice_a" => $q["choice_a"],
                    "choice_b" => $q["choice_b"],
                    "choice_c" => $q["choice_c"],
                    "choice_d" => $q["choice_d"],
                    "correct_answer" => $q["correct_answer"]
                ];

                $result = runQuery($pdo, $sql, $params);

                if ($result->rowCount() <= 0) {
                    echo json_encode([
                        "success" => false,
                    ]);
                    exit;
                }
            }

            unset($_SESSION["quizzes"]);
            unset($_SESSION["quiz_id"]);
            if (!isset($_SESSION["quiz_id"])) {
                $_SESSION['quiz_id'] = $quizID;
            }
            echo json_encode([
                'success' => true,
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
        ]);
    }

}

