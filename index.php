<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'to_do' => 'Собеседование в IT компании',
        'deadline' => '01.12.2019',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'to_do' => 'Выполнить тестовое задание',
        'deadline' => '25.12.2019',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'to_do' => 'Сделать задание первого раздела',
        'deadline' => '21.12.2019',
        'category' => 'Учеба',
        'done' => true
    ],
    [
        'to_do' => 'Встреча с другом',
        'deadline' => '22.12.2019',
        'category' => 'Входящие',
        'done' => false
    ],
    [
        'to_do' => 'Купить корм для кота',
        'deadline' => null,
        'category' => 'Домашние дела',
        'done' => false
    ],
    [
        'to_do' => 'Заказать пиццу',
        'deadline' => null,
        'category' => 'Домашние дела',
        'done' => false
    ]
];

date_default_timezone_set('Europe/Moscow');

/**
* Determines whether the task is hot or not
*
* @param string $task The concrete task from our array
* @return bool $is_hot Shows whether there are 24 or less hours left before the task or not
*/
function is_hot ($task) {
    if (isset($task['deadline']) and !$task['done']) {
        $task_ts = strtotime($task['deadline']);
        $ts_diff = $task_ts - time();
        $hours = floor($ts_diff / 3600);
        if ($hours <= 24) {
            $is_hot = true;
        };
    };
    return ($is_hot);
};

/**
* Counts the number of tasks in the project/category
*
* @param array $tasks Array with iterable tasks
* @param string $project Project/category under review
* @return int $count The calculated number of tasks in the selected project
*/
function count_tasks(array $tasks, $project) {
    $count = 0;
    foreach ($tasks as $task) {
        if ($task['category'] === $project) {
            $count++;}
	}
        return ($count);
};

require_once('helpers.php');

$page_content = include_template('main.php', [
    'tasks' => $tasks,
    'projects' => $projects,
    'show_complete_tasks' => $show_complete_tasks]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке']);

print($layout_content);
