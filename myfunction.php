<?php
/**
* Determines whether the task is hot or not
*
* @param string $t The concrete task from our array $tasks
* @return bool $is_hot Shows whether there are 24 or less hours left before the task or not
*/
function is_hot ($task) {
    if (!empty($task['dt_deadline']) and !$task['status']) {
        $task_ts = strtotime($task['dt_deadline']);
        $ts_diff = $task_ts - time();
        $hours = floor($ts_diff / 3600);
        return ($hours <= 24);
    }
};

/**
 * In a request to the database, it creates an array based on the response or returns an error
 *
 * @param object $result response from our database to our request
 * @return functino to print or create array
 */
function array_or_error(object $result) {
    if (!$result) {
        return 'Ошибка запроса';
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
};

/**
 * Returns code 404 when $project_id doesn't have request parameter or tasks
 *
 * @param int $project_id - our request parametr
 * @param array $projects_ids - a column with projects id in our projects array
 * @param array $tasks our array with tasks for $project_id
 * @return string about error with code 404
 */
function check_tasks_for_project($project_id, $projects_ids, array $tasks) {
    if (!empty($project_id)) {
        if (!in_array($project_id, $projects_ids) OR empty($tasks)) {
            http_response_code(404);
            return 'Ошибка 404. Страница, которую Вы ищете, не может быть найдена';
        }
    }
};

/**
 * Check to exist sent project in our form to add tasks
 *
 * @param int $id - our id to check
 * @param array $allowed_list - an id column of our exist projects
 * @return string about error
 */
function validate_project($id, $allowed_list) {
    if (!in_array($id, $allowed_list)) {
        return 'Указан несуществующий проект';
    }
    return null;
};

/**
 * Check null of a name in our form
 *
 * @param string $name - the name of task or user
 * @return string about error
 */
function validate_name($name) {
    if (!$name) {
        return 'Название/имя не может быть пустым';
    }
    return null;
};

/**
 * Check date of task deadline to send right format and to be not earlier than today
 *
 * @param string $date - sent date of deadline
 * @return string about errors
 */
function check_date($date) {
    if (!is_date_valid($date)) {
        return 'Указан неверный формат даты';
    };
    if (strtotime($date) >= time()) {
        return null;
    }
    return 'Указана устаревшая дата';
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

/**
 * Check to exist sent user email in our form to sign up
 *
 * @param $email - our email to check
 * @param array $emails - an emails column of our users in database
 * @return string about error
 */
function validate_email($email, $emails) {
    if (in_array($email, $emails)) {
        return 'Указан уже зарегистрированный e-mail, войдите по этому адресу или введите новый адрес';
    };
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Введен некорректный e-mail';
    }
    return null;
};

/**
 * Check the string to have legth between our bourders
 * @param string $value our checked string
 * @param int $min min number of symbols
 * @param int $max max number of symbols
 * @return string about error
 */
function validate_length ($value, $min, $max) {
    $length = strlen($value);
    if ($length < $min OR $length > $max) {
        return "Указана некорректная длина: строка должна содержать от $min до $max символов";
    }
    return null;
}
