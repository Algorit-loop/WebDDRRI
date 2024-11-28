<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Login</h1>
        <form action="<?= BASE_URL ?>?page=login_submit" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <button type="submit">Login</button>
        </form>
        <a href="<?= BASE_URL ?>?page=register">Don't have an account? Register here</a>
    </div>
</body>
</html>
