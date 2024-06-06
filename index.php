<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load Composer's autoloader
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/application');
$dotenv->load();
// Include necessary files
require_once __DIR__ . '/application/config.php';
require_once __DIR__ . '/application/Repo/Database.php';
include __DIR__ . '/application/Repo/User.php';
// Check if user_management is in the URI
if (str_contains($_SERVER['REQUEST_URI'], '/wsd_user_management')) {

    // Extract the remaining part of the URI after /user_management
    $uri_segment = explode('/wsd_user_management/', $_SERVER['REQUEST_URI'])[1] ?? '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $user_id = $_GET['user_id'] ?? 0;
    // Handle specific routes within /user_management
    switch ($uri_segment) {
        case 'login':
        case '':
            // Include the login
            require_once 'application/Controller/login.php';
            break;
        case 'logout':
            // logout route
            include 'application/Controller/logout.php';
            break;
        default:
            echo "Invalid user_management route";
            break;
    }
} else {
    // Route not found
    http_response_code(404);
    echo "404 Not Found";
    exit();
}
