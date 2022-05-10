<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

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
    $search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS), " ");

    $projects = get_projects($con, $id);
    $projects_ids = array_column($projects, 'id');

    $tasks = get_tasks($con, $project_id, $id, $search);

    $page_content = check_tasks_for_project_or_search($project_id, $projects_ids, $tasks, $search);

    if (empty($page_content)) {
        $page_content = include_template('main.php', [
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks]);
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
