<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

if (empty($_SESSION['user'])) {
    $page_content = include_template('guest.php');
    $project_id = NULL;
    $projects = [];

    $navigation_content = include_template('navigation.php', [
        'user' => $_SESSION['user'],
        'projects' => $projects,
        'project_id' => $project_id,
        'content' => $page_content]);

    $layout_content = include_template('layout.php', [
        'user' => $_SESSION['user'],
        'navigation' => $navigation_content,
        'title' => 'Дела в порядке']);
} else {
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
    $projects = get_projects($con, $user_id);
    $projects_ids = array_column($projects, 'id');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required = ['name'];
        $errors= [];
        $rules =[
            'name' => function ($value) {
                return validate_name($value);
            }
        ];

        $project_form = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT], true);

        foreach ($project_form as $key => $value) {
            if (!empty($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($value);
            }

            if (in_array($key, $required) && empty($value)) {
                $errors[$key] = "Поле $key надо заполнить";
            }
        }

        $errors = array_filter($errors);

        if (!count($errors) AND check_name_project($projects, $project_form)) {
            $errors['name'] = 'У вас уже есть такой проект';
        } else if (!count($errors) AND !check_name_project($projects, $project_form)) {
            $result = add_project($con, $project_form, $user_id);
            if ($result) {
                $project_id = mysqli_insert_id($con);
                header("Location: index.php");
            }
        }
        if (count($errors)) {
            $page_content = include_template('add-project.php', ['errors' => $errors]);
        }
    } else {
        $page_content = include_template('add-project.php');
    }
    $navigation_content = include_template('navigation.php', [
        'user' => $_SESSION['user'],
        'projects' => $projects,
        'project_id' => $project_id,
        'content' => $page_content]);

    $layout_content = include_template('layout.php', [
        'user' => $_SESSION['user'],
        'navigation' => $navigation_content,
        'title' => 'Дела в порядке']);
}

print($layout_content);
