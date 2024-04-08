<?php

// PHP 8.2.0-dev

/**
 * Đăng ký hàm xử lý ngoại lệ tùy chỉnh
 *
 * @category Exception
 * @package  App\Exceptions
 * @author   Vinh Nguyen <nguyenduyquangvinh2906@gmail.com>
 * @license  None
 * @link     None
 */

require_once 'vendor/autoload.php';

use App\Exceptions\CustomException;

/**
 * Hàm xử lý ngoại lệ tùy chỉnh
 *
 * @param Exception $e Ngoại lệ
 *
 * @return void
 */
function handleUncaughtException($e)
{
    // Hiển thị thông báo lỗi chung cho người dùng
    echo "Opps! Có lỗi xảy ra. Hãy thử lại hoặc liên hệ với chúng tôi nếu còn lỗi.";

    // Lấy thông tin lỗi
    $error = "Uncaught Exception: " . $message = date("Y-m-d H:i:s - ");
    $error .= "{$e->getMessage()} trong file {$e->getFile()} ở dòng {$e->getLine()}";

    // Ghi thông tin lỗi vào file log
    file_put_contents("error.log", $error, FILE_APPEND);
}

// Đăng ký xử lý ngoại lệ tùy chỉnh
set_exception_handler("handleUncaughtException");

// Ném ra một ngoại lệ

try {
    throw new CustomException("123");
} catch (CustomException $e) {
    echo $e->errorMessage();
} catch (Exception $e) {
    echo "Có lỗi xảy ra: " . $e->getMessage();
}

?>
