<?php

$db = Database::getInstance();
$userObj = new User($db);

// Get the user data by ID
$userId = $_GET['user_id'];
$userData = $userObj->getUserById($userId);
// Populate the form input fields with the retrieved user data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Form</title>
    <link rel="stylesheet" href="application/style/common.css">
    <link rel="stylesheet" href="application/style/login_form.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Update, <span><?php echo $userData["name"]; ?>!</span></h1>
        <nav>
            <a href="<?php echo BASE_URL ?>/dashboard" class="nav-link">Dashboard</a>
            <a href="<?php echo BASE_URL ?>/logout" class="nav-link">Logout</a>
        </nav>
    </header>
    <div class="login-container">

        <?php if (isset($_SESSION['errors'])): // Check if errors are present in session ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo $error; ?></li> <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); // Clear errors after displaying them  ?>
        <?php endif; ?>
        <form class="login-form" method="post" action="user_update?user_id=<?php echo $userId; ?>">

            <div class="input-group">
                <label class="input-group">Name</label>
                <input type="text" id="name" name="name" placeholder="Name" value="<?php echo $userData['name']; ?>">
            </div>
            <div class="input-group">
                <label class="input-group">Email</label>
                <input type="text" id="email" name="email" placeholder="Email"
                       value="<?php echo $userData['email']; ?>">
            </div>
            <div class="input-group">
                <label class="input-group">Password</label>
                <input type="password" id="password" name="password" placeholder="*****" value="" autocomplete="off">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>

</div>
<footer>
    <p>&copy; 2024 Motiur Rahman</p>
</footer>
</body>
</html>