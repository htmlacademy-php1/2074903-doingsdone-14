<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('init.php');
require_once('model.php');
require_once('helpers.php');
require_once('myfunction.php');

$project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);

$projects = get_projects($con);
$tasks = get_tasks($con, $project_id);

$page_content = code_404($project_id, $projects, $tasks);

if (empty($page_content)) {
    $page_content = include_template('main.php', [
        'tasks' => $tasks,
        'projects' => $projects,
        'show_complete_tasks' => $show_complete_tasks,
        'project_id' => $project_id]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке']);

print($layout_content);
