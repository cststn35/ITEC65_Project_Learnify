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

//OLTP TABLES
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
    last_streak_updated DATE NULL,
    last_study_date DATE NULL,
    daily_goal_minutes INT DEFAULT 120,
    is_newly_registered TINYINT(1) DEFAULT 1,
    profile_pic_path VARCHAR(500) NULL,
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

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS sessions (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    task_id INT NULL,
    subject_id INT NOT NULL,
    semester_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    target_duration_minutes INT NOT NULL,
    start_time DATETIME NOT NULL,
    pause_start_time DATETIME NULL,
    end_time DATETIME NULL,
    total_pause_seconds INT DEFAULT 0,
    actual_duration_seconds INT NULL,
    status ENUM('active', 'paused', 'completed', 'abandoned')
        DEFAULT 'active',
    question_count INT DEFAULT 0,
    file_name VARCHAR(100) NULL,
    quiz_decision VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (task_id)
        REFERENCES tasks(tasks_id)
        ON DELETE SET NULL,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(subject_id)
        ON DELETE CASCADE,

    FOREIGN KEY (semester_id)
        REFERENCES semesters(semester_id)
        ON DELETE CASCADE
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS quizzes (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NULL,
    score INT DEFAULT 0,
    total_questions INT NOT NULL,
    duration_taken_seconds INT DEFAULT 0,
    status ENUM('in_progress', 'completed', 'abandoned')
        DEFAULT 'in_progress',
    xp_earned INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (session_id)
    REFERENCES sessions(session_id)
    ON DELETE SET NULL
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question TEXT NOT NULL,
    choice_a TEXT NOT NULL,
    choice_b TEXT NOT NULL,
    choice_c TEXT NOT NULL,
    choice_d TEXT NOT NULL,
    correct_answer TEXT NOT NULL,
    my_answer TEXT NULL,

    FOREIGN KEY (quiz_id)
    REFERENCES quizzes(quiz_id)
    ON DELETE CASCADE
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS xp_logs (
    xp_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester_id INT NOT NULL,
    xp_change INT NOT NULL,

    reason ENUM(
        'TASK_COMPLETE',
        'TASK_EARLY_BONUS',
        'TASK_OVERDUE_PENALTY',

        'STUDY_SESSION',

        'DAILY_GOAL_COMPLETE',
        'DAILY_GOAL_EXCEEDED',

        'STREAK_3_DAYS',
        'STREAK_7_DAYS',
        'STREAK_14_DAYS',
        'STREAK_30_DAYS',

        'TASK_CREATED',
        'TASK_DELETED',

        'QUIZ_CORRECT_ANSWER',
        'QUIZ_COMPLETION'
    ) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS daily_progress (
    daily_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester_id INT NOT NULL,
    date DATE NOT NULL,
    total_minutes INT NOT NULL,
    is_goal_reached TINYINT(1) DEFAULT 0,

    UNIQUE (user_id, semester_id, date),

    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS notifications (
    notif_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM(
        'REMINDER',
        'DEADLINE',
        'STREAK',
        'SYSTEM'
    ) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    semester_id INT NOT NULL,
    subject_id INT NOT NULL,
    day_of_week TINYINT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    teacher VARCHAR(100) NULL,
    room VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id),

    FOREIGN KEY (semester_id)
        REFERENCES semesters(semester_id),

    FOREIGN KEY (subject_id)
        REFERENCES subjects(subject_id)
);
"
);


//DIMENSION TABLES AND FACT TABLES FOR DATA ANALYTICS

//DIMENSION TABLES
runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS dim_user (
    user_sk INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    name VARCHAR(255),
    created_at DATE
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS dim_date (
    date_sk INT PRIMARY KEY,
    full_date DATE,
    day INT,
    month INT,
    year INT,
    week INT,
    weekday VARCHAR(20)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS dim_subject (
    subject_sk INT AUTO_INCREMENT PRIMARY KEY,
    subject_id INT,
    name VARCHAR(150),
    semester_id INT
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS dim_semester (
    semester_sk INT AUTO_INCREMENT PRIMARY KEY,
    semester_id INT,
    semester_name VARCHAR(100),
    school_year VARCHAR(100)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS dim_xp_reason (
    reason_sk INT AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(50),
    category VARCHAR(50)
);
"
);

//FACT TABLES
runQuery(
    $pdo,
    "
CREATE TABLE fact_study_session (
    session_sk INT AUTO_INCREMENT PRIMARY KEY,

    user_sk INT NOT NULL,
    subject_sk INT NOT NULL,
    semester_sk INT NOT NULL,
    date_sk INT NOT NULL,

    start_hour TINYINT NOT NULL,

    duration_seconds INT,
    target_duration_minutes INT,
    pause_seconds INT DEFAULT 0,

    status ENUM(
        'active',
        'paused',
        'completed',
        'abandoned'
    ),

    FOREIGN KEY (user_sk)
        REFERENCES dim_user(user_sk),

    FOREIGN KEY (subject_sk)
        REFERENCES dim_subject(subject_sk),

    FOREIGN KEY (semester_sk)
        REFERENCES dim_semester(semester_sk),

    FOREIGN KEY (date_sk)
        REFERENCES dim_date(date_sk)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS fact_quiz (
    quiz_sk INT AUTO_INCREMENT PRIMARY KEY,

    user_sk INT,
    subject_sk INT,
    semester_sk INT,
    date_sk INT,

    score INT,
    total_questions INT,
    duration_seconds INT,
    xp_earned INT,

    FOREIGN KEY (user_sk) REFERENCES dim_user(user_sk),
    FOREIGN KEY (subject_sk) REFERENCES dim_subject(subject_sk),
    FOREIGN KEY (semester_sk) REFERENCES dim_semester(semester_sk),
    FOREIGN KEY (date_sk) REFERENCES dim_date(date_sk)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS fact_task (
    task_sk INT AUTO_INCREMENT PRIMARY KEY,

    user_sk INT,
    subject_sk INT,
    semester_sk INT,
    date_created_sk INT,
    date_completed_sk INT NULL,

    priority VARCHAR(10),
    status VARCHAR(20),

    estimated_seconds INT,
    actual_seconds INT NULL,

    is_late BOOLEAN,

    FOREIGN KEY (user_sk) REFERENCES dim_user(user_sk),
    FOREIGN KEY (subject_sk) REFERENCES dim_subject(subject_sk),
    FOREIGN KEY (semester_sk) REFERENCES dim_semester(semester_sk),
    FOREIGN KEY (date_created_sk) REFERENCES dim_date(date_sk),
    FOREIGN KEY (date_completed_sk) REFERENCES dim_date(date_sk)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS fact_xp (
    xp_sk INT AUTO_INCREMENT PRIMARY KEY,

    user_sk INT,
    semester_sk INT,
    date_sk INT,
    reason_sk INT,

    xp_change INT,

    FOREIGN KEY (user_sk) REFERENCES dim_user(user_sk),
    FOREIGN KEY (semester_sk) REFERENCES dim_semester(semester_sk),
    FOREIGN KEY (date_sk) REFERENCES dim_date(date_sk),
    FOREIGN KEY (reason_sk) REFERENCES dim_xp_reason(reason_sk)
);
"
);

runQuery(
    $pdo,
    "
CREATE TABLE IF NOT EXISTS fact_daily_progress (
    daily_sk INT AUTO_INCREMENT PRIMARY KEY,

    user_sk INT,
    semester_sk INT,
    date_sk INT,

    total_minutes INT,

    FOREIGN KEY (user_sk) REFERENCES dim_user(user_sk),
    FOREIGN KEY (semester_sk) REFERENCES dim_semester(semester_sk),
    FOREIGN KEY (date_sk) REFERENCES dim_date(date_sk)
);
"
);

function loadDimDate($pdo, $start, $end)
{

    $current = strtotime($start);
    $end = strtotime($end);

    while ($current <= $end) {

        $date = date("Y-m-d", $current);

        $sql = "
            INSERT IGNORE INTO dim_date
            (date_sk, full_date, day, month, year, week, weekday)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $pdo->prepare($sql)->execute([
            (int) date("Ymd", $current),
            $date,
            date("d", $current),
            date("m", $current),
            date("Y", $current),
            date("W", $current),
            date("l", $current)
        ]);

        $current = strtotime("+1 day", $current);
    }
}

loadDimDate($pdo, '2026-01-01', '2027-01-01');

echo "Database initialized successfully.";

