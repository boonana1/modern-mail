<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {

    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');

    header('Access-Control-Allow-Headers: token, Content-Type');

    header('Access-Control-Max-Age: 1728000');

    header('Content-Length: 0');

    header('Content-Type: text/plain');

    die();
}

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json');

mb_internal_encoding('UTF-8');

mb_regex_encoding('UTF-8');

mb_http_output('UTF-8');

mb_language('uni');

header('Content-type: text/html; charset=utf-8');

date_default_timezone_set('Europe/Moscow');
$data = json_decode(file_get_contents("php://input"), 256);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Создаем объект PHPMailer
$mail = new PHPMailer(true);
try {
    // Настройки SMTP
    $mail->isSMTP();
    $mail->Host = getenv('EMAIL_HOST');
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL_USER');
    $mail->Password = getenv('EMAIL_PASS');
    $mail->SMTPSecure = 'tls';
    $mail->Port = getenv('EMAIL_PORT');

    // Настройки письма
    $mail->setFrom($data['email'], 'Your Name');
    $mail->addAddress('resume@ooo-modern.ru', 'Recipient Name');
    $mail->Subject = 'Questions from DreamCreditMaker: ' . $data['subject'];
    $mail->Body = "Email: " . $data['email'] . "\nName: " . $data['name'] . "\nQuestion: " . $data['question'] . "\nPhone Number: " . $data['phone'];

    // Отправляем письмо
    if ($mail->send()) {
        echo 'Письмо успешно отправлено';
    } else {
        echo 'Ошибка отправки письма: ' . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo 'Сообщение не былоб отправленно. Причина: ', $mail->ErrorInfo;
}
