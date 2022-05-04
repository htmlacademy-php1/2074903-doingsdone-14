<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['email', 'password'];
    $errors= [];

    $rules =[
        'email' => function ($value) {
            return validate_email($value);
        },
        'password' => function ($value) {
            return validate_length($value, 8, 15);
        }
    ];

    $user_auth = filter_input_array(INPUT_POST, ['email' => FILTER_DEFAULT, 'password' => FILTER_DEFAULT], true);

    foreach ($user_auth as $key => $value) {
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
        $page_content = include_template('/auth.php', [
            'user' => $user_auth,
            'errors' => $errors]);
    } else {
        $result = ;
        if ($result) {
            $task_id = mysqli_insert_id($con);

            header("Location: index.php");
        }
    }
} else {
    $page_content = include_template('/auth.php', []);
}

$navigation_content = include_template('navigation.php', [
    'projects' => $projects,
    'project_id' => $project_id,
    'content' => $page_content]);

$layout_content = include_template('layout.php', [
    'navigation' => $navigation_content,
    'title' => 'Дела в порядке']);

print($layout_content);
