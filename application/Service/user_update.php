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
    <link rel="stylesheet" href="application/style/login_form.css">
</head>
<body>

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
    <form class="login-form" method="post" action="user_update?user_id=<?php echo $_GET['user_id']; ?>">

        <div class="input-group">
            <input type="text" id="name" name="name" placeholder="Name" value="<?php echo $userData['name']; ?>"
                   required>
        </div>
        <div class="input-group">
            <input type="text" id="email" name="email" placeholder="Email" value="<?php echo $userData['email']; ?>"
                   required>
        </div>
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>"> <!-- Include a hidden field for user ID -->
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>