<?php
session_start();
require_once __DIR__ . '/../repo/User.php';

if (isset($_SESSION['user_id'])) {
// Handle update form display
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        include __DIR__ . '/../Forms/user_update_form.php';
    }

// Handle login form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $errors = [];
        $db = Database::getInstance();
        $userObj = new User($db);

        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);

        //check for any duplicate email
        if ($userObj->isEmailDuplicate($email, $_GET["user_id"])) {
            $errors[] = "Email address already exists. Please choose a different one.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        //check for password validation rules
        if (empty($password)) {
            $errors[] = "Please enter a password.";
        } else if (strlen($password) < 8) { // Minimum password length
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Handle errors or success
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            // Display errors back to the user on the registration form
            include __DIR__ . '/../Forms/user_update_form.php';
        } else {
            $user = [
                "name" => trim($_POST["name"]) ?? $_SESSION['user_name'],
                "email" => $email ?? $_SESSION['user_email'],
                "password" => password_hash($password, PASSWORD_DEFAULT),
                "id" => $_GET["user_id"]
            ];
            $is_updated = $userObj->updateUser($user);
            if (!$is_updated) {
                $errors[] = "Something went wrong.";
                $_SESSION['errors'] = $errors;
                header("Location:" . BASE_URL . "/user_update");
            }

            if (isset($_SESSION['user_role'])) {
                $_SESSION['success'] = true;
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
