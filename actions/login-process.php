<?php
session_start();

include("../config/db.php");

// Get form data
$email = isset($_POST["email"])
    ? trim(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    : "";
$password = isset($_POST["password"])
    ? trim(filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS))
    : "";

if ($email && $password) {
    $sql = "SELECT user_id, first_name, last_name, password_hash, is_newly_registered FROM users WHERE email = :email AND is_deleted = 0";
    // Prepare and execute query
    $stmt = $pdo->prepare($sql); //Fetch the password of the user
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    $sql2 = "SELECT semester_id FROM semesters WHERE user_id = :user_id AND is_active = 1";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute(['user_id' => (int) $user['user_id']]);
    $semesterID = $stmt2->fetch()['semester_id'];

    if (!$semesterID) {
        $semesterID = 0;
    }


    if ($user && password_verify($password, $user['password_hash'])) { //Compare the hashed password of the user to the entered password
        $_SESSION['user_id'] = $user['user_id'];
        $firstname = $user["first_name"];
        $lastname = $user["last_name"];
        $_SESSION['first_name'] = $firstname;
        $_SESSION['last_name'] = $lastname;
        $_SESSION['semester_id'] = (int) $semesterID;
        $_SESSION['is_newly_registered'] = (int) $user['is_newly_registered'];
        header("Location: ../pages/dashboard.php");
        exit;
    }

    $_SESSION["login"]["error"] = "Invalid username or password.";
    header("Location: ../login-register.php");
    exit;
} else {
    $_SESSION["login"]["error"] = "Email and password must be both filled";
    header("Location: ../login-register.php");
    exit;
}

