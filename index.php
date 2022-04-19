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
/**
* Counts the number of tasks in the project/category
*
* @param array $tasks Array with iterable tasks
* @param string $project Project/category under review
*
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
?>
