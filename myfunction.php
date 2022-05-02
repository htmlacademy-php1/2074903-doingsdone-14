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
 * @return functino to print or create array
 */
function array_or_error(object $result) {
    if (!$result) {
        return print('Ошибка запроса');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
};

/**
 * Returns code 404 when $project_id doesn't have request parameter or tasks
 *
 * @param int $project_id - our request parametr
 * @param array $projects - our array of projects to check exist project id
 * @param array $tasks our array with tasks for $project_id
 * @return string $page_content with code 404
 */
function code_404($project_id, array $projects, array $tasks) {
    if (!empty($project_id)) {
        $project_exist = 0;
        foreach ($projects as $project) {
            if ($project_id === $project['id']) {
                $project_exist++;
            }
        }
        if (!$project_exist OR empty($tasks)) {
            http_response_code(404);
            $page_content = print('Ошибка 404. Страница, которую Вы ищете, не может быть найдена');
            return ($page_content);
        }
    }
};

/**
 * Check to exist sent project in our form to add tasks
 *
 * @param int $id - our id to check
 * @param $allowed_list - an id column of our exist projects
 * @return string about error
 */
function validate_project($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return 'Указан несуществующий проект';
    }
    return null;
};

/**
 * Check null of a task name in out form to add tasks
 *
 * @param string $name - the name of task
 * @return string about error
 */
function validate_name($name) {
    if (!$name) {
        return 'Название задачи не может быть пустым';
    }
    return null;
};

/**
 * Check date of task deadline to send right format and to be not earlier than today
 *
 * @param string $date - sent date of deadline
 * @return string about errors
 */
function validate_date($date) {
    if (!is_date_valid($date)) {
        return 'Указан неверный формат даты';
    };
    $task_ts = strtotime($date);
    $ts_diff = $task_ts - time();
    if ($ts_diff < 0) {
        return 'Указана устаревшая дата';
    }
    return null;
};

/**
 * Return sent value from our from to add tasks
 * @param string $name - name of sent value
 * @return string sent value
 */
function get_post_value($name) {
    return filter_input(INPUT_POST, $name);
};

/**
 * Check size of upload files
 * @param $name - our file
 * @param $$max_size_limit our max size for upload files
 * @return string about error
 */
function validate_filesize($name, $max_size_limit) {
    if (filesize($name) > $max_size_limit) {
        return 'Размер файла превышает допустимый';
    }
    return null;
};
