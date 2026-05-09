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

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS semesters (
    semester_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester_name VARCHAR(100) NOT NULL,
    school_year VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS subjects (
    subject_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    color ENUM(
        'red-700',
        'orange-500',
        'yellow-400',
        'green-700',
        'blue-700',
        'purple-500',
        'violet-700'
    ) NULL,
    is_archived BOOLEAN NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS tasks (
    tasks_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject_id INT NOT NULL,
    semester_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    deadline DATETIME NULL,
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    estimated_seconds INT NULL,
    status ENUM('pending', 'completed') NOT NULL DEFAULT 'pending',
    completed_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_archived BOOLEAN NOT NULL DEFAULT 0,

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (subject_id) REFERENCES subjects(subject_id),
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id)
);
"
);

echo "Database initialized successfully.";

