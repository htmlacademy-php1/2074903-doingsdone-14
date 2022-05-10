<?php
require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

if (empty($_SESSION['user'])) {
    $page_content = include_template('guest.php');
    $project_id = NULL;
    $projects = [];
} else {
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
    $projects = get_projects($con, $id);
    $projects_ids = array_column($projects, 'id');

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $required = ['name', 'project_id'];
        $errors= [];
        $rules =[
            'name' => function ($value) {
                return validate_name($value);
            },
            'project_id' => function ($value) use ($projects_ids) {
                return validate_project($value, $projects_ids);
            }
        ];

        $task_form = filter_input_array(INPUT_POST, ['name' => FILTER_DEFAULT, 'project_id' => FILTER_DEFAULT], true);

        foreach ($task_form as $key => $value) {
            if (!empty($rules[$key])) {
                $rule = $rules[$key];
                $errors[$key] = $rule($value);
            }

            if (in_array($key, $required) && empty($value)) {
                $errors[$key] = "Поле $key надо заполнить";
            }
        }

        $errors = array_filter($errors);

        if (!empty($_POST['dt_deadline'])) {
            $dt_deadline = mysqli_real_escape_string($con, $_POST['dt_deadline']);
            $task_form['dt_deadline'] = check_date($dt_deadline);
        } else {
            $task_form['dt_deadline'] = NULL;
        }

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
        } else {
            $task_form['file'] = NULL;
        }

        if (count($errors)) {
            $page_content = include_template('add-task.php', [
                'task' => $task_form,
                'projects' => $projects,
                'errors' => $errors]);
        } else {
            $result = add_task($con, $task_form);
            if ($result) {
                $task_id = mysqli_insert_id($con);
                header("Location: index.php");
            }
        }
    } else {
        $page_content = include_template('add-task.php', ['projects' => $projects]);
    }
}

$navigation_content = include_template('navigation.php', [
    '_SESSION' => $_SESSION['user'],
    'projects' => $projects,
    'project_id' => $project_id,
    'content' => $page_content]);

$layout_content = include_template('layout.php', [
    '_SESSION' => $_SESSION['user'],
    'navigation' => $navigation_content,
    'title' => 'Дела в порядке']);

print($layout_content);
