<?php
session_start();

require_once('config/db.php');
date_default_timezone_set('Europe/Moscow');

$con = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($con, 'utf8');

if (!$con) {
    $error = mysqli_connect_error();
    $content = 'Ошибка подключения: '.$error;
};

$max_size_limit = 1024*1024*80;
