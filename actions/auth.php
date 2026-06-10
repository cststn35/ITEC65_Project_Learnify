<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_newly_registered'])) {
    // relative path back to login.php in parent folder
    header("Location: ../login-register.php");
    exit;
}

if (($_SESSION['is_newly_registered']) == 1) {
    header("Location: ../pages/welcome_wizard.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    // relative path back to login.php in parent folder
    header("Location: ../login-register.php");
    exit;
}
