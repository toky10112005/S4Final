<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if(session()->getFlashdata('error')): ?>
        <p style="color: red;"><?php echo session()->getFlashdata('error'); ?></p>
    <?php endif; ?>

    <form action="/login" method="post">
        <?= csrf_field() ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
        
    </form>

    <a href="/inscription">Inscription</a>
</body>
</html>