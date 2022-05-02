<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

$projects = get_projects($con);
$projects_ids = array_column($projects, 'id');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required = ['name', 'project_id', 'dt_deadline'];
    $errors= [];

    $rules =[
        'name' => function ($value) {
            return validate_name($value);
        },
        'project_id' => function ($value) use ($projects_ids) {
            return validate_project($value, $projects_ids);
        },
        'dt_deadline' => function ($value) {
            return validate_date($value);
        }
    ];

    $task = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT, 'project_id' => FILTER_DEFAULT, 'dt_deadline' => FILTER_DEFAULT], true);

    foreach ($task as $key => $value) {
        if (!empty($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);


    if (!empty($_FILES['file']['name'])) {
        $tmp_name = $_FILES['file']['tmp_name'];
        if (validate_filesize($tmp_name, $max_size_limit)) {
            $errors['file'] = validate_filesize($tmp_name, $max_size_limit);
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            $type = stristr($file_type, '/');
            $end_name = substr($type, 1);
            $file_name = uniqid() . ".$end_name";
            move_uploaded_file($tmp_name, 'uploads/' . $file_name);
            $task['file'] = $file_name;
        }
    } else $task['file'] = NULL;

    if (count($errors)) {
        $page_content = include_template('add-task.php', [
            'task' => $task,
            'projects' => $projects,
            'errors' => $errors]);
    } else {
        $sql = 'INSERT INTO tasks (dt_add, user_id, name, project_id, dt_deadline, file, status) VALUES (NOW(), 2, ?, ?, ?, ?, 0)';
        $stmt = db_get_prepare_stmt($con, $sql, $task);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $task_id = mysqli_insert_id($con);

            header("Location: index.php");
        }
    }
} else {
    $page_content = include_template('add-task.php', ['projects' => $projects]);
}

$navigation_content = include_template('navigation.php', [
    'projects' => $projects,
    'project_id' => $project_id,
    'content' => $page_content]);

$layout_content = include_template('layout.php', [
    'navigation' => $navigation_content,
    'title' => 'Дела в порядке']);

print($layout_content);
