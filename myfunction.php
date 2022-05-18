<?php

/**
 * Determines whether the task is hot or not
 *
 * @param string $task The concrete task from our array $tasks
 *
 * @return bool $is_hot Shows true or false there are 24h or less before the task
 */
function is_hot($task) : bool
{
    if (!empty($task['dt_deadline']) and !$task['status']) {
        $task_ts = strtotime($task['dt_deadline']);
        $ts_diff = $task_ts - time();
        $hours = floor($ts_diff / 3600);
        if ($hours <= 24) {
            return true;
        } else {
            return false;
        }
    }
    return false;
}

/**
 * In a request to the db, creates an array of the response or returns an error
 *
 * @param resource $result response from our database to our request
 *
 * @return array|null from db
 */
function array_or_error(object $result) : array|null
{
    if (!$result) {
        exit('Ошибка запроса');
        return null;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Gives user a message if there aren't results of search
 * or $project_id doesn't have request parameter or tasks
 *
 * @param int $project_id - our request parametr
 * @param array $projects_ids - a column with projects id in our projects array
 * @param array $tasks our array with tasks for $project_id
 * @param string $search Our search request to find tasks
 *
 * @return string|null about error with code 404
 */
function check_tasks_for_project_or_search(
    $project_id, $projects_ids, array $tasks, $search
) : string|null
{
    if (!empty($search) and empty($tasks)) {
        return 'Ничего не найдено по вашему запросу';
    } elseif (!empty($project_id)) {
        if (!in_array($project_id, $projects_ids) or empty($tasks)) {
            http_response_code(404);
            return 'Ошибка 404. Страница, которую Вы ищете, не может быть найдена';
        }
    } else {
        return null;
    }
}

/**
 * Check to exist sent project in our form to add tasks
 *
 * @param int $project_id - our id to check
 * @param array $allowed_list - an id column of our exist projects
 *
 * @return string|null about error
 */
function validate_project($project_id, $allowed_list) : string|null
{
    if (!in_array($project_id, $allowed_list)) {
        return 'Указан несуществующий проект';
    }
    return null;
}

/**
 * Check null of a name in our form
 *
 * @param string $name - the name of task or user
 *
 * @return string|null about error
 */
function validate_name($name) : string|null
{
    if (!$name) {
        return 'Название/имя не может быть пустым';
    }
    return null;
}

/**
 * Check date of task deadline to send right format and to be not earlier than today
 *
 * @param string $date - sent date of deadline
 *
 * @return string|null about errors
 */
function check_date($date) : string|null
{
    if (!is_date_valid($date)) {
        return 'Указан неверный формат даты';
    }
    $date_and_time = strtotime($date) + 3600 * 24 - 1;
    $deadline = date('Y-m-d H:i:s', $date_and_time);
    if (strtotime($deadline) < time()) {
        return 'Указана устаревшая дата';
    }
    return null;
}

/**
 * Return sent value from our form to add tasks
 *
 * @param string $name - name of sent value
 *
 * @return string|null sent value
 */
function get_post_value($name) : string|null
{
    if (!empty($name)) {
        return filter_input(INPUT_POST, $name);
    }
    return null;
}

/**
 * Check size of upload files
 *
 * @param $name - our file
 * @param $max_size_limit our max size for upload files
 *
 * @return string|null about error
 */
function validate_filesize($name, $max_size_limit) : string|null
{
    if (filesize($name) > $max_size_limit) {
        return 'Размер файла превышает допустимый';
    }
    return null;
}

/**
 * Check to exist sent user email in our form to sign up
 *
 * @param resource $email - our email to check
 * @param array $emails - an emails column of our users in database
 *
 * @return string|null about error
 */
function validate_email($email, $emails) : string|null
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введен некорректный e-mail';
    }
    if (in_array($email, $emails)) {
        return 'Указан уже зарегистрированный e-mail';
    }
    return null;
}

/**
 * Check the string to have legth between our bourders
 *
 * @param string $value our checked string
 * @param int $min min number of symbols
 * @param int $max max number of symbols
 *
 * @return string|null about error
 */
function validate_length($value, $min, $max) : string|null
{
    $length = strlen($value);
    if ($length < $min or $length > $max) {
        return "Некорректная длина: поле должно содержать $min - $max символов";
    }
    return null;
}

/**
 * Check existing of added project
 *
 * @param array $projects Projects which current user has
 * @param array $project_form New project which current user tries to create
 *
 * @return bool $same_name exist or not this name of project
 */
function check_name_project(array $projects, array $project_form) : bool
{
    $project_name = mb_strtolower($project_form['name']);
    foreach ($projects as $project) {
        if (mb_strtolower($project['name']) === $project_name) {
            return true;
        }
    }
    return false;
}
