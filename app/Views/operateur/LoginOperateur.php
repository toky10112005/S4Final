<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operateur Login</title>
</head>
<body>
    <form action="operateur" method="post">
        <?= csrf_field() ?>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <input type="submit" value="Login">

    </form>
</body>
</html>