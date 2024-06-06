<?php
session_start();
// routes/login.php
require_once __DIR__ . '/../Repo/User.php';

// Handle login form display
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $error_message = $_SESSION["error_message"] ?? '';
    unset($_SESSION["error_message"]);
    include __DIR__ . '/../Forms/login_form.html';
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $db = Database::getInstance();
    $userObj = new User($db);
    $user = $userObj->login($email, $password);

    if ($user) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = $user['role'];
        print_r("success");
        exit();

    } else {
        $_SESSION["error_message"] = "Invalid email or password. Please try again.";
        header("Location:" . BASE_URL . "/login");
    }
    exit();
}