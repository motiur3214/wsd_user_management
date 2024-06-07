<?php
session_start();
require_once __DIR__ . '/../repo/User.php';

// Handle login form display
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include __DIR__ . '/../forms/registration_form.php';
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Input validation
    $errors = [];

    // Name validation
    $name = trim($_POST["name"]);
    if (empty($name)) {
        $errors[] = "Please enter your name.";
    }

    // Email validation
    $email = trim(strtolower($_POST["email"])); // Ensure lowercase for case-insensitive check
    if (empty($email)) {
        $errors[] = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    // Password validation
    $password = trim($_POST["password"]);
    if (empty($password)) {
        $errors[] = "Please enter a password.";
    } else if (strlen($password) < 8) { // Minimum password length
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check for duplicate email in database
    $db = Database::getInstance();
    $userObj = new User($db);
    if ($userObj->isEmailExists($email)) {
        $errors[] = "Email address already exists. Please choose a different one.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $user = [
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => USER_ROLE["user"]
        ];
        $is_stored = $userObj->registration($user);
        if (!$is_stored) {
            $errors[] = "An error occurred during registration. Please try again."; // Handle potential registration failures
        }
    }

    // Handle errors or success
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        // Display errors back to the user on the registration form
        include __DIR__ . '/../forms/registration_form.php';
    } else {
        // Registration successful, redirect based on user role
        if (isset($_SESSION['user_role'])) {
            if ($_SESSION['user_role'] == USER_ROLE['admin']) {
                header("Location:" . BASE_URL . "/admin/dashboard");
            } else {
                header("Location:" . BASE_URL . "/user/dashboard");
            }
        } else {
            header("Location:" . BASE_URL . "/login");
        }
        exit();
    }

}

