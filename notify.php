<?php

require_once 'init.php';
require_once 'model.php';
require_once 'helpers.php';
require_once 'myfunction.php';
require_once 'config/smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'vendor/autoload.php';

$mail = new PHPMailer();

$mail->isSMTP();
$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->Host = $smtp['host'];
$mail->Port = $smtp['port'];
$mail->SMTPSecure = $smtp['secure'];
$mail->SMTPAuth = true;
$mail->Username = $smtp['username'];
$mail->Password = $smtp['password'];

$mail->setFrom('keks@phpdemo.ru', 'doingsdone');

$users_notify = get_users_notify($con);
$date = date('Y-m-d', strtotime("now"));
foreach ($users_notify as $user) {
    $user_id = $user['id'];
    $tasks_notify = get_tasks_notify($con, $user_id, $date);
    if (!empty($tasks_notify)) {
        $tasks = array_column($tasks_notify, 'name');
        $mail->addAddress($user['email'], $user['name']);
        $mail->Subject = 'Уведомление от сервиса «Дела в порядке»';
        $mail->Body = 'Уважаемый, ' . $user['name']
                    . '. У вас запланирована задача ' . implode(", ", $tasks) . ' на ' . $date;
        $mail->AltBody = 'Уважаемый, ' . $user['name']
                    . '. У вас запланирована задача ' . implode(", ", $tasks) . ' на ' . $date;
        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message sent!';
        }
    }
}
