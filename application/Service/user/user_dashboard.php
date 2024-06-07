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
    <link rel="stylesheet" href="../application/style/common.css">
    <link rel="stylesheet" href="../application/style/user_dashboard.css">
    <title>User Dashboard</title>

</head>
<body>
<div class="container">
    <header>
        <h1 style="">Welcome, <span style="color: #f5fcf5"><?php echo $user_data["name"]; ?>!</span></h1>
        <nav>
            <form action="../user_delete" method="post"
                  onsubmit="return confirm('Are you sure you want to delete your account?')">
                <input type='hidden' name='user_id' value=" <?php echo $user_data['id']; ?>">
                <button type="submit" class="nav-link" style=" color: #721c24">Delete Account</button>
            </form>
            <a href="<?php echo BASE_URL ?>/logout" class="nav-link">Logout</a>
        </nav>
    </header>
    <main>
        <?php
        if (isset($_SESSION['errors']) && count($_SESSION['errors']) == 0) {
            echo "<p class='success'>Successfully updated!</p>";
            unset($_SESSION['errors']); // Clear the session variable after displaying the message
        }
        ?>
        <section class="user-info">
            <h2>User Information</h2>
            <ul>
                <li>Name: <?php echo $user_data["name"]; ?></li>
                <li>Email: <?php echo $user_data["email"]; ?></li>

            </ul>
            <a href="<?php echo BASE_URL . "/user_update?user_id=" . $user_data["id"]; ?>">
                <button class='update-user-btn'>Update</button>
            </a>

        </section>
    </main>
    <footer>
        <p>&copy; 2024 Motiur Rahman</p>
    </footer>
</div>
</body>
</html>
