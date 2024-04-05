<?php

if (isset($_REQUEST['username']) && isset($_REQUEST['password'])) {
    echo "Username: " . $_GET['username'] . "<br>";
    echo "Password: " . $_GET['password'] . "<br>";
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post">
    Username: <input type="text" name="username" id="username" placeholder="Username">
    <br>
    Password: <input type="password" name="password" id="password" placeholder="Password">
</form>
</body>
</html>
