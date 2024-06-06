<?php
session_start();
require_once __DIR__ . '/../repo/User.php';

if (isset($_SESSION['user_id'])) {
// Handle update form display
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        include __DIR__ . '/../Service/user_update.php';
    }

// Handle login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $errors = [];
        $db = Database::getInstance();
        $userObj = new User($db);
        if ($userObj->isEmailExists($_POST["email"])) {
            $errors[] = "Email address already exists. Please choose a different one.";
        }
        // Handle errors or success
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            // Display errors back to the user on the registration form
            include __DIR__ . '/../Service/user_update.php';
        } else {
            $user = [
                "name" => $_POST["name"],
                "email" => $_POST["email"],
                "id" => $_POST["user_id"]
            ];
            $is_updated = $userObj->updateUser($user);
            if (!$is_updated) {
                header("Location:" . BASE_URL . "/user_update");
                exit();
            }
            $_SESSION['errors'] = "none";
            if (isset($_SESSION['user_role'])) {
                if ($_SESSION['user_role'] == USER_ROLE['admin']) {
                    header("Location:" . BASE_URL . "/admin/dashboard");
                } else {
                    header("Location:" . BASE_URL . "/user/dashboard");
                }
            }
            exit();
        }
    }
} else {
    header("Location:" . BASE_URL . "/login");

    exit();
}
