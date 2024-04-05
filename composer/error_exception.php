<?php

require_once 'vendor/autoload.php';

use App\Exceptions\CustomException;

//ini_set("display_errors", 0);

//function handleError($errno, $errstr, $errfile, $errline)
//{
//    echo "Lỗi: [$errno] $errstr ở $errfile tại dòng  $errline\n";
//}
//
//function handleShutdown()
//{
//    $error = error_get_last();
//    handleError($error['type'], $error['message'], $error['file'], $error['line']);
//}
//
//set_error_handler("handleError");
//register_shutdown_function("handleShutdown");

function handleUncaughtException($e)
{
    // Hiển thị thông báo lỗi chung cho người dùng
    echo "Opps! Có lỗi xảy ra. Hãy thử lại hoặc liên hệ với chúng tôi nếu còn lỗi.";

    // Lấy thông tin lỗi
    $error = "Uncaught Exception: " . $message = date("Y-m-d H:i:s - ");
    $error .= $e->getMessage() . " trong file " . $e->getFile() . " ở dòng " . $e->getLine() . "\n";
    echo '<br/>';
//    echo $error;
}

// Đăng ký xử lý ngoại lệ tùy chỉnh
set_exception_handler("handleUncaughtException");


try {
    require 'abc.php';
} catch (Throwable $e) {
    echo "Lỗi: " . $e->getMessage();
}

//try {
//} catch (CustomException $e) {
//    echo $e->errorMessage();
//} catch (Exception $e) {
//    echo "Có lỗi xảy ra: " . $e->getMessage();
//}

//throw new CustomException("123");
//
//throw new Exception("Kiểm tra ngoại lệ!");