<?php
/**
* Determines whether the task is hot or not
*
* @param string $t The concrete task from our array $tasks
* @return bool $is_hot Shows whether there are 24 or less hours left before the task or not
*/
function is_hot ($task) {
    if (isset($task['dt_deadline']) and !$task['status']) {
        $task_ts = strtotime($task['dt_deadline']);
        $ts_diff = $task_ts - time();
        $hours = floor($ts_diff / 3600);
        return ($hours <= 24);
    }
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
            $count++;
        }
    }
    return ($count);
};

/**
 * In a request to the database, it creates an array based on the response or returns an error
 *
 * @param object $result response from our database to our request
 * @return string about our error or our array
 */
function array_or_error(object $result) {
    if (!$result) {
        return print('Ошибка запроса');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
