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

    $sqlSelect = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
    $user = $db->query($sqlSelect);

    if (count($user) > 0) {
        $isValid = false;
        $errors['username'] = 'Username is already exists';
    }

    if ($isValid) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $created_at = date('Y-m-d H:i:s');

        try {

            $sql = "INSERT INTO users (username, password, created_at) VALUES ('$username', '$password', '$created_at')";

            $db->query($sql);

            header('location: /');

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
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
    <title>Register</title>
</head>
<body>
<h1>Register</h1>
<a href="login.php">Login</a>
<form action="" method="post">
    <input type="text" name="username" placeholder="Username" value="<?= $_POST['username'] ?? '' ?>">
    <span style="color: red"><?= $errors['username'] ?? '' ?></span>
    <br>
    <input type="password" name="password" placeholder="Password" <?= $_POST['password'] ?? '' ?>>
    <span style="color: red"><?= $errors['password'] ?? '' ?></span>
    <br>
    <input type="submit" value="Register">
</form>
</body>
</html>