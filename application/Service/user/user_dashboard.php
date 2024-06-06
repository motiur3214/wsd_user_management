<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $db = Database::getInstance(); // Create Database instance
    $user = new User($db); // Pass Database instance to User constructor
    $user_data = $user->getUserById($_SESSION['user_id']);
} else {
    // Redirect to login page if not logged in
    header("Location:" . BASE_URL . "/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../application/style/user_dashboard.css">
    <title>User Dashboard</title>

</head>
<body>
<div class="container">
    <header>
        <h1 style="">Welcome, <span style="color: #3e8e41"><?php echo $user_data["name"]; ?>!</span></h1>
        <a href="../logout" class="logout">Logout</a>
    </header>
    <main>
        <section class="user-info">
            <h2>User Information</h2>
            <ul>
                <li>Name: <?php echo $user_data["name"]; ?></li>
                <li>Email: <?php echo $user_data["email"]; ?></li>

            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Motiur Rahman</p>
    </footer>
</div>
</body>
</html>
