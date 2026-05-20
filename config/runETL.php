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
    loadDimDate($pdo, '2026-01-01', '2027-01-01');

    loadFactStudySession($pdo);
    loadFactDailyProgress($pdo);
    loadFactQuiz($pdo);
    loadFactTask($pdo);
    loadFactXP($pdo);

    echo "ETL completed successfully.";
}

runETL($pdo);