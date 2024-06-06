<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to login page if not logged in
    header("Location:" . BASE_URL . "/login");
    exit();
}
$role = array_search($_SESSION['user_role'], USER_ROLE);

if ($role == "admin") {
    header("Location:" . BASE_URL . "/admin/dashboard");
    exit();
}
header("Location:" . BASE_URL . "/user/dashboard");
exit();
