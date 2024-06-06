<?php
session_start();
require_once __DIR__ . '/../repo/User.php';

if (isset($_SESSION['user_id'])) {
// Handle login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $db = Database::getInstance();
        $userObj = new User($db);
        $userObj->deleteUser($_POST["user_id"]);
        if (isset($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] == USER_ROLE['admin']) {
                header("Location:" . BASE_URL . "/admin/dashboard");
            } else {
                header("Location:" . BASE_URL . "/logout");
            }
        }
        exit();
    }
} else {
    header("Location:" . BASE_URL . "/login");

    exit();
}
