<?php

include 'Validator.php';

$validator = new Validator();

if ($_POST) {

    $validator->make($_POST);

    $validator->validate([
        'name' => "required|regex:/^[a-zA-Z-' ]*$/",
        'tel' => ['required', 'regex:/(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})/'],
        'email' => 'required|email',
    ], [
        'required' => ":attribute bắt buộc nhập",
        'regex' => ':attribute không hợp lệ',
        'email.email' => 'Cần nhập đúng định dạng email'
    ], [
        'name' => 'Tên',
        'tel' => 'Số điện thoại',
        'email' => 'Email'
    ]);
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
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="<?= $_POST['name'] ?? '' ?>">
    <span style="color: red"><?= $validator->hasErrors('name') ? $validator->getFirstError('name') : '' ?></span>
    <br>
    <label for="tel">Tel</label>
    <input type="text" name="tel" id="tel" placeholder="Tel" value="<?= $_POST['tel'] ?? '' ?>">
    <span style="color: red"><?= $validator->hasErrors('tel') ? $validator->getFirstError('tel') : '' ?></span>
    <br>
    <label for="email">Email</label>
    <input type="text" name="email" id="email" placeholder="Email" value="<?= $_POST['email'] ?? '' ?>">
    <span style="color: red"><?= $validator->hasErrors('email') ? $validator->getFirstError('email') : '' ?></span>
    <br>
    <button type="submit">Submit</button>
</form>
</body>
</html>