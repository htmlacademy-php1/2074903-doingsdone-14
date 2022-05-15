<?php

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once 'init.php';
require_once 'model.php';
require_once 'helpers.php';
require_once 'myfunction.php';

if (empty($_SESSION['user'])) {
    $page_content = include_template('guest.php');
    $project_id = null;
    $projects = [];

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
} else {
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_NUMBER_INT);
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
    $task_id = filter_input(INPUT_GET, 'task_id', FILTER_SANITIZE_NUMBER_INT);
    $is_checked = filter_input(INPUT_GET, 'check', FILTER_SANITIZE_NUMBER_INT);
    $today = filter_input(INPUT_GET, 'today', FILTER_SANITIZE_NUMBER_INT);
    $tomorrow = filter_input(INPUT_GET, 'tomorrow', FILTER_SANITIZE_NUMBER_INT);
    $overdue = filter_input(INPUT_GET, 'overdue', FILTER_SANITIZE_NUMBER_INT);

    $projects = get_projects($con, $user_id);
    $projects_ids = array_column($projects, 'id');

    $tasks = get_tasks(
        $con, $project_id, $user_id, $search, $today, $tomorrow, $overdue
    );

    $checked = change_status_task($con, $task_id, $is_checked);

    $page_content = check_tasks_for_project_or_search(
        $project_id, $projects_ids, $tasks, $search
    );

    if (empty($page_content)) {
        $page_content = include_template(
            'main.php',
            [
                'tasks' => $tasks,
                'show_complete_tasks' => $show_complete_tasks,
                'today' => $today,
                'tomorrow' => $tomorrow,
                'overdue' => $overdue
            ]
        );
    }
    $navigation_content = include_template(
        'navigation.php',
        [
            'user' => $_SESSION['user'],
            'projects' => $projects,
            'project_id' => $project_id,
            'content' => $page_content
        ]
    );

    $layout_content = include_template(
        'layout.php',
        [
            'user' => $_SESSION['user'],
            'navigation' => $navigation_content,
            'title' => 'Дела в порядке'
        ]
    );
}

print($layout_content);
