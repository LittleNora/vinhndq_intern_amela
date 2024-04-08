<?php

session_start();

date_default_timezone_set('Asia/Ho_Chi_Minh');

require_once 'vendor/autoload.php';

$user = isset($_SESSION['username']) ? $_SESSION['username'] : ($_COOKIE['username'] ?? null);

$id = isset($_SESSION['id']) ? $_SESSION['id'] : ($_COOKIE['id'] ?? null);


$sqlSelectComment = "SELECT comments.*, users.id, users.username FROM comments join users on comments.user_id = users.id order by comments.created_at desc";

if (!$user) {
    header('location: login.php');
}

$db = new \App\Models\Database();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];

    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO comments (content, user_id, created_at) VALUES ('$content', $id, '{$created_at}')";

    $db->query($sql);
}


$comments = $db->query($sqlSelectComment);

//echo '<pre>';
//print_r($comments);

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

<h1>Welcome <?= $user ?></h1>
<form action="logout.php" method="post">
    <button>Log out</button>
</form>
<div>
    <h1>Comments</h1>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li>
                <span style="<?= $comment->user_id == $id ? 'font-weight: bold' : '' ?>"><?= $comment->username ?>: </span>
                <span><?= $comment->content ?></span>
                <small><?= $comment->created_at ?? '' ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
    <form action="" method="post">
        <textarea name="content" id="" cols="30" rows="10"></textarea>
        <br>
        <button>Comment</button>
    </form>
</div>
</body>
</html>
