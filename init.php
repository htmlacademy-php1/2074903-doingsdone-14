<?php
require_once('config/db.php');
date_default_timezone_set('Europe/Moscow');

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, 'utf8');

if (!$con) {
    $error = mysqli_connect_error();
    $content = print('Ошибка подключения: '.$error);
};
