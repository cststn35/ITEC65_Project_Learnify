<?php
$dsn = "mysql:host=localhost;dbname=studyapp";
$username = "root";
$pass = "";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO($dsn, $username, $pass, $options);
} catch (PDOException $e) {
    echo $e->getMessage();
}
function runQuery($pdo, $sql, $params = [], $fetch = false)
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($fetch === true) {
            return $stmt->fetchAll(); // for SELECT multiple rows
        }

        return $stmt; // return statement for flexibility
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    xp INT DEFAULT 0,
    current_streak INT DEFAULT 0,
    longest_streak INT DEFAULT 0,
    last_study_date DATE NULL,
    daily_goal_minutes INT DEFAULT 120,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
"
);

echo "Database initialized successfully.";

