<?php

/**
 * Determines whether the task is hot or not
 *
 * @param string $task The concrete task from our array $tasks
 *
 * @return bool $is_hot Shows true or false there are 24h or less before the task
 */
function is_hot($task)
{
    if (!empty($task['dt_deadline']) and !$task['status']) {
        $task_ts = strtotime($task['dt_deadline']);
        $ts_diff = $task_ts - time();
        $hours = floor($ts_diff / 3600);
        return ($hours <= 24);
    }
}

/**
 * In a request to the db, creates an array of the response or returns an error
 *
 * @param object $result response from our database to our request
 *
 * @return function to exit or create array
 */
function array_or_error(object $result)
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
 * @return string about error with code 404
 */
function check_tasks_for_project_or_search(
    $project_id, $projects_ids, array $tasks, $search
) {
    if (!empty($search) and empty($tasks)) {
        return 'Ничего не найдено по вашему запросу';
    } elseif (!empty($project_id)) {
        if (!in_array($project_id, $projects_ids) or empty($tasks)) {
            http_response_code(404);
            return 'Ошибка 404. Страница, которую Вы ищете, не может быть найдена';
        }
    }
}

/**
 * Check to exist sent project in our form to add tasks
 *
 * @param int $project_id - our id to check
 * @param array $allowed_list - an id column of our exist projects
 *
 * @return string about error
 */
function validate_project($project_id, $allowed_list)
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
 * @return string about error
 */
function validate_name($name)
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
 * @return string about errors
 */
function check_date($date)
{
    if (!is_date_valid($date)) {
        return 'Указан неверный формат даты';
    }
    $date_and_time = strtotime($date) + 3600 * 24 - 1;
    $deadline = date('Y-m-d H:i:s', $date_and_time);
    if (strtotime($deadline) < time()) {
        return 'Указана устаревшая дата';
    }
}

/**
 * Return sent value from our from to add tasks
 *
 * @param string $name - name of sent value
 *
 * @return string sent value
 */
function get_post_value($name)
{
    return filter_input(INPUT_POST, $name);
}

/**
 * Check size of upload files
 *
 * @param $name - our file
 * @param $max_size_limit our max size for upload files
 *
 * @return string about error
 */
function validate_filesize($name, $max_size_limit)
{
    if (filesize($name) > $max_size_limit) {
        return 'Размер файла превышает допустимый';
    }
    return null;
}

/**
 * Check to exist sent user email in our form to sign up
 *
 * @param $email - our email to check
 * @param array $emails - an emails column of our users in database
 *
 * @return string about error
 */
function validate_email($email, $emails)
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
 * @return string about error
 */
function validate_length($value, $min, $max)
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
 * @return boolean $same_name exist or not this name of project
 */
function check_name_project(array $projects, array $project_form)
{
    $project_name = mb_strtolower($project_form['name']);
    foreach ($projects as $project) {
        if (mb_strtolower($project['name']) === $project_name) {
            return true;
        }
    }
    return false;
}
