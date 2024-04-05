<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] != 0) {
        $img_errors = 'Chưa chọn file';
    } else {
        $max_size = 1024 * 1024 * 5;
        $valid_ext = ['jpg', 'jpeg'];
        $file = $_FILES['file'];
        $name = $file['name'];
        $tmp_name = $file['tmp_name'];
        $size = $file['size'];
        $error = $file['error'];

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if ($_POST['mode'] == 0 && file_exists('uploads/images/' . $name)) {
            $img_errors = 'File đã tồn tại';
        } elseif (!in_array($ext, $valid_ext)) {
            $img_errors = 'File không đúng định dạng';
        } elseif ($size > $max_size) {
            $img_errors = 'File quá lớn';
        } elseif ($error != 0) {
            $img_errors = 'Có lỗi xảy ra';
        } else {
            if ($_POST['mode'] == 1 && file_exists('uploads/images/' . $name)) {
                unlink('uploads/images/' . $name);
            }
            move_uploaded_file($tmp_name, 'uploads/images/' . $name);
            $img_success = 'Upload thành công';
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
    <title>Document</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <label for="file">Chọn ảnh</label> <input type="file" name="file" id="file">
    <span style="color: red"><?= $img_errors ?? '' ?></span>
    <span style="color: green"><?= $img_success ?? '' ?></span>
    <br>
    <label for="">Nếu ảnh đã tồn tại: </label> <input type="radio" name="mode" id="" value="0" checked>Không ghi đè
    <input
            type="radio" name="mode" id="" value="1">Ghi đè <br>
    <button type="submit">Upload</button>
</form>
</body>
</html>