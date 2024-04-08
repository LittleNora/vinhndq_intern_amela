<?php

session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once 'vendor/autoload.php';

use App\Models\Database;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = new \App\Models\Database();

    $errors = [];

    $isValid = true;

    if (empty($_POST['username']) || empty($_POST['password'])) {

        $isValid = false;

        $errors['username'] = 'Username is required';
        $errors['password'] = 'Password is required';
    }

    if ($isValid) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sqlSelect = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $user = $db->query($sqlSelect);

        if (count($user) > 0) {
            if ($_POST['remember'] == 1) {
                setcookie('id', $user[0]->id, time() + 24 * 60 * 60 * 15);
                setcookie('username', $username, time() + 24 * 60 * 60 * 15);
            } else {
                $_SESSION['username'] = $username;
                $_SESSION['id'] = $user[0]->id;
            }

            header('location: /');
        } else {
            $errors['username'] = 'Username or password is incorrect';
        }

    }

}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<a href="register.php">Register</a>
<form action="" method="post">
    <input type="text" name="username" placeholder="Username" value="<?= $_POST['username'] ?? '' ?>">
    <span style="color: red"><?= $errors['username'] ?? '' ?></span>
    <br>
    <input type="password" name="password" placeholder="Password" <?= $_POST['password'] ?? '' ?>>
    <span style="color: red"><?= $errors['password'] ?? '' ?></span>
    <br>
    <input type="checkbox" name="remember" id="remember" value="1"> <label for="remember">Remember me</label>
    <br>
    <input type="submit" value="Login">
</form>
</body>
</html>