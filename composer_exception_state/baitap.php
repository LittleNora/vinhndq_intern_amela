<?php

session_start();

require_once 'vendor/autoload.php';

$isLogged = false;
$userType = '';
$type = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        if ($_POST['logout'] == 0) {
            unset($_SESSION['is_logged']);
        } else {
            setcookie('is_logged', false, time() - 3600, '/');
        }
        $isLogged = false;
        $userType = '';
        $type = null;
    } else {
        $type = $_POST['type'] ?? null;

        if ($type == 0) {
            $_SESSION['is_logged'] = true;
            $userType = 'Admin';
        } else {
            setcookie('is_logged', true, time() + (60 * 60 * 24 * 15), '/');
            $userType = 'User';
        }

        $isLogged = true;
    }
} else {
    if (isset($_SESSION['is_logged'])) {
        $isLogged = true;
        $userType = 'Admin';
        $type = 0;
    } elseif (isset($_COOKIE['is_logged'])) {
        $isLogged = true;
        $userType = 'User';
        $type = 1;
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
    <title>Document</title>
</head>
<body>
<div>
    <h1>Trạng thái đăng nhập: <?= $isLogged ? "Đã đăng nhập" : "Chưa đăng nhập" ?></h1>
    <?php if ($isLogged): ?>
        <h2>Loại tài khoản: <?= $userType ?></h2>
        <form action="" method="post">
            <input type="hidden" name="logout" value="<?= $type ?>">
            <button>Đăng xuất</button>
        </form>
    <?php endif; ?>
</div>
<?php if (!$isLogged): ?>
    <form action="" method="post">
        <input type="radio" name="type" id="admin-type" value="0" checked> <label for="admin-type">Admin</label> <br>
        <input type="radio" name="type" id="user-type" value="1"> <label for="user-type">User</label> <br>
        <button>Login</button>
    </form>
<?php endif; ?>
</body>
</html>
