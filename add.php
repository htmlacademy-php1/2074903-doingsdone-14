<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

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
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($value);
        }

        if (in_array($key, $required) && empty($value)) {
            $errors[$key] = "Поле $key надо заполнить";
        }
    }

    $errors = array_filter($errors);

    if (!empty($_FILES['file']['name'])) {
        if (validate_filesize($_FILES['name'], 2097152)) {
            $errors['file'] = validate_filesize($_FILES['name'], 2097152);
        } else {
            $tmp_name = $_FILES['file']['tmp_name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finto_file($finfo, $tmp_name);
            $file_name = uniqid() . '.$file_type';
            move_uploaded_file($tmp_name, 'uploads/' . $file_name);
            $task['file'] = $file_name;
        }
    }

    if (count($errors)) {
        $page_content = include_template('add-task.php', [
            'projects' => $projects,
            'task' => $task,
            'errors' => $errors]);
    } else {
        $sql = 'INSERT INTO tasks (dt_add, user_id, name, project_id, dt_deadline, file, status) VALUES (NOW(), 2, ?, ?, ?, 0)';
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

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке']);

print($layout_content);
