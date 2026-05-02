<!-- sanitize inputs

check if all are filled

check if password and confirm password matches, retain email and full 

then if all are ok, then proceed -->

<?php
session_start();

include("../config/db.php");

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullName = isset($_POST["fullname"]) ? trim(filter_var($_POST["fullname"]), FILTER_SANITIZE_SPECIAL_CHARS) : "";
        $email = isset($_POST["email"]) ? trim(filter_var($_POST["email"]), FILTER_VALIDATE_EMAIL) : "";
        $password = isset($_POST["password"]) ? trim(filter_var($_POST["password"]), FILTER_SANITIZE_SPECIAL_CHARS) : "";
        $cpassword = isset($_POST["cpassword"]) ? trim(filter_var($_POST["cpassword"]), FILTER_SANITIZE_SPECIAL_CHARS) : "";

        if (empty($fullName) && empty($email) && empty($fullName) && empty($cpassword)) {
            $_SESSION["registration"]["error"] = "All fields must be filled.";
            $_SESSION["reg"] = true;
            header("Location: ../login-register.php");
            exit;
        }

        if ($password != $cpassword) {
            $_SESSION["registration"]["error"] = "Passwords do not match.";
            $_SESSION["registration"]["fullname"] = $fullName;
            $_SESSION["registration"]["email"] = $email;
            $_SESSION["reg"] = "true";
            header("Location: ../login-register.php");
            exit;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,}$/', $password)) {
            $_SESSION["registration"]["error"] = "Your password must be at least 8 characters and include uppercase letters, lowercase letters, a number, and a special character.";
            $_SESSION["registration"]["fullname"] = $fullName;
            $_SESSION["registration"]["email"] = $email;
            $_SESSION["reg"] = "true";
            header("Location: ../login-register.php");
            exit;
        }

        // if success unset these variables (double method)
        unset($_SESSION["registration"]["error"], $_SESSION["registration"]["fullname"], $_SESSION["registration"]["email"], $_SESSION["reg"]);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password_hash) VALUES (:fullName, :email, :hashedPassword)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            "fullName" => $fullName,
            "email" => $email,
            "hashedPassword" => $hashedPassword
        ]);

        if ($result) {
            header("Location: ../login-register.php");
            exit;
        }
    }
} catch (PDOException $e) {
    $_SESSION["registration"]["error"] = "Your email is already registered";
    $_SESSION["reg"] = "true";
    header("Location: ../login-register.php");
    exit;
}








