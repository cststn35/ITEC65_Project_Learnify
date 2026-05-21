<?php
require_once "db.php";
require_once "populate_dim.php";
require_once "populate_fact.php";
function runETL($pdo)
{

    loadDimUser($pdo);
    loadDimSubject($pdo);
    loadDimSemester($pdo);
    loadDimXPReason($pdo);
    loadFactStudySession($pdo);
    loadFactDailyProgress($pdo);
    loadFactQuiz($pdo);
    loadFactTask($pdo);
    loadFactXP($pdo);

    // echo "ETL completed successfully.";
}

runETL($pdo);