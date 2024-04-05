<?php

include 'Validator.php';

if ($_POST) {

    $validator = new Validator();


    $validator->make($_POST);

    $validator->validate([
        'name' => "required|regex:/^[a-zA-Z-' ]*$/",
        'tel' => ['required', 'regex:/(03|05|07|08|09|01[2|6|8|9])+([0-9]{8})/'],
        'email' => 'required|email',
        'password' => [
            'required',
            'min:8',
            'regex:/^(?=.*[!@#$%^&*(),.?":{}|<>])(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ]
    ], [
        'email.regex' => 'Password must contain at least one uppercase, one lowercase, one number and one special character',
    ]);

    if (!$validator->hasErrors()) {
        echo 'Name: ' . $_POST['name'] . '<br>';
        echo 'Tel: ' . $_POST['tel'] . '<br>';
        echo 'Job: ' . $_POST['job'] . '<br>';
        echo 'Email: ' . $_POST['email'] . '<br>';
        echo 'Password: ' . preg_replace('/./', '*', $_POST['password']) . '<br>';
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
<?php if (!isset($validator) || $validator->hasErrors()): ?>
<form action="" method="post">
    <label for="name">Name</label>
    <input type="text" name="name" id="name" placeholder="Name" value="<?= $_POST['name'] ?? '' ?>">
    <span style="color: red"><?= isset($validator) && $validator->hasErrors('name') ? $validator->getFirstError('name') : '' ?></span>
    <br>
    <label for="tel">Tel</label>
    <input type="text" name="tel" id="tel" placeholder="Tel" value="<?= $_POST['tel'] ?? '' ?>">
    <span style="color: red"><?= isset($validator) && $validator->hasErrors('tel') ? $validator->getFirstError('tel') : '' ?></span>
    <br>
    <label for="job">Job</label>
    <input type="text" name="job" id="job" placeholder="Job" value="<?= $_POST['job'] ?? '' ?>">
    <span style="color: red"><?= isset($validator) && $validator->hasErrors('job') ? $validator->getFirstError('job') : '' ?></span>
    <br>
    <label for="email">Email</label>
    <input type="text" name="email" id="email" placeholder="Email" value="<?= $_POST['email'] ?? '' ?>">
    <span style="color: red"><?= isset($validator) && $validator->hasErrors('email') ? $validator->getFirstError('email') : '' ?></span>
    <br>
    <label for="password">Password</label>
    <input type="text" name="password" id="password" placeholder="Password" value="<?= $_POST['password'] ?? '' ?>">
    <span style="color: red"><?= isset($validator) && $validator->hasErrors('password') ? $validator->getFirstError('password') : '' ?></span>
    <br>
    <button type="submit">Submit</button>
</form>
<?php endif; ?>
</body>
</html>