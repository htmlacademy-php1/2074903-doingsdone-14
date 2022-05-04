<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

$users = get_users($con);
$emails = array_column($users, 'email');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password', 'name'];
    $errors= [];

    $rules =[
        'email' => function ($value) use ($emails) {
            return validate_email($value, $emails);
        },
        'password' => function ($value) {
            return validate_length($value, 8, 15);
        },
        'name' => function ($value) {
            return validate_name($value);
        }
    ];

    $user = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT, 'name' => FILTER_DEFAULT], true);

    foreach ($user as $key => $value) {
        if (!empty($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (count($errors)) {
        $page_content = include_template('register.php', [
            'user' => $user,
            'errors' => $errors]);
    } else {
        $password = password_hash($user['password'], PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (email, password, name) VALUES (?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, [$user['email'], $password, $user['name']]);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $user_id = mysqli_insert_id($con);

            header("Location: index.php");
        }
    }
} else {
    $page_content = include_template('register.php');
}

$navigation_content = include_template('navigation-closed.php', [
    'content' => $page_content]);

$layout_content = include_template('layout-closed.php', [
    'navigation' => $navigation_content,
    'title' => 'Дела в порядке']);

print($layout_content);
