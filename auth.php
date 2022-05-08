<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password'];
    $errors= [];
    $user_auth = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], true);

    foreach ($user_auth as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);
    $user = get_user($con, $user_auth);

    if (!count($errors) AND $user) {
        if (password_verify($user_auth['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('auth.php', [
            'user' => $user_auth,
            'errors' => $errors]);
    } else {
        header("Location: index.php");
        exit();
    }
} else {
    $page_content = include_template('auth.php', []);
    if (!empty($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }
}

$navigation_content = include_template('navigation-closed.php', [
    'content' => $page_content]);

$layout_content = include_template('layout-closed.php', [
    'navigation' => $navigation_content,
    'title' => 'Дела в порядке']);

print($layout_content);
