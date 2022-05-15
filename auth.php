<?php

require_once 'init.php';
require_once 'model.php';
require_once 'helpers.php';
require_once 'myfunction.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password'];
    $errors = [];
    $user_auth = filter_input_array(
        INPUT_POST,
        [
            'email' => FILTER_DEFAULT,
            'password' => FILTER_DEFAULT
        ],
        true
    );

    foreach ($user_auth as $key => $value) {
        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);
    $user = get_user($con, $user_auth);

    if (!count($errors) and !empty($user)) {
        if (password_verify($user_auth['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                $user_id = $_SESSION['user']['id'];
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template(
            'auth.php',
            [
                'user' => $user_auth,
                'errors' => $errors
            ]
        );
    } else {
        header("Location: index.php");
    }
} else {
    $page_content = include_template('auth.php', []);
    if (!empty($_SESSION['user'])) {
        header("Location: index.php");
    }
}

$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
$projects = get_projects($con, $user_id);

$navigation_content = include_template(
    'navigation.php',
    [
        'user' => [],
        'projects' => $projects,
        'project_id' => $project_id,
        'content' => $page_content
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'user' => [],
        'navigation' => $navigation_content,
        'title' => 'Дела в порядке'
    ]
);

print($layout_content);
