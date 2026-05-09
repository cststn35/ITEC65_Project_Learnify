<?php
session_start();

include("../config/db.php");

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

if ($email && $password) {
    $sql = "SELECT user_id, name, password_hash FROM users WHERE email = :email";
    // Prepare and execute query
    $stmt = $pdo->prepare($sql); //Fetch the password of the user
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) { //Compare the hashed password of the user to the entered password
        $_SESSION['user_id'] = $user['user_id'];
        $name = $user["name"];
        $_SESSION['name'] = $name;
        $_SESSION['semester_id'] = 1;
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

