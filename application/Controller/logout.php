<?php
require_once __DIR__ . '/../Service/SessionManager.php';


$sessionManager = new SessionManager();

$logout = $sessionManager->logout();

if ($logout) {
    header("Location:" . BASE_URL . "/login");
} else {
    echo "something went  wrong";
}
exit();
