<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="application/style/login_form.css">
</head>
<body>
<div class="login-container">
    <form class="login-form" method="post" action="login">
        <h2>Login</h2>
        <?php if (!empty($error_message)): ?>
        <div class="error-message" style="color: red;">
            <?= htmlspecialchars($error_message) ?>
        </div>
        <?php endif; ?>
        <div class="input-group">
            <input type="text" id="email" name="email" placeholder="Email" required>
        </div>
        <div class="input-group">
            <input type="password" id="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
