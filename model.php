<?php

/**
 * Create the array with projects and counted tasks for each of them for current user
 *
 * @param object $con Our connect to MySQL database
 * @param int $id Our correct id of user who use our website right now
 * @return function array_or_error
 */
function get_projects(object $con, $id):array {
    $sql = "SELECT p.id, p.name, COUNT(t.name) AS count "
                ."FROM projects p "
                ."LEFT JOIN tasks t ON p.id = t.project_id "
                ."WHERE p.user_id = '$id' "
                ."GROUP BY p.id";
    $result = mysqli_query($con, $sql);
    return array_or_error($result);
};

/**
 * Create the array with tasks for user with id=2
 *
 * @param object $con Our connect to MySQL database
 * @param int $project_id Identify our project
 * @param int $id Our correct id of user who use our website right now
 * @param string $search Our search request to find tasks
 * @return function array_or_error
 */
function get_tasks(object $con, $project_id, $id, $search):array {
    if (!empty($search)) {
        $search = trim(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS), " ");
        $sql = "SELECT id, name, status, DATE_FORMAT(dt_deadline, '%d.%m.%Y') as dt_deadline, file FROM tasks "
                    ."WHERE user_id = '$id' AND MATCH(name) AGAINST(?) ORDER BY dt_add DESC";
        $stmt = db_get_prepare_stmt($con, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    } else if (!empty($project_id)) {
        $sql = "SELECT id, name, status, DATE_FORMAT(dt_deadline, '%d.%m.%Y') as dt_deadline, file FROM tasks "
                    ."WHERE user_id = '$id' AND project_id = '$project_id' ORDER BY dt_add DESC";
        $result = mysqli_query($con, $sql);
    } else {
        $sql = "SELECT id, name, status, DATE_FORMAT(dt_deadline, '%d.%m.%Y') as dt_deadline, file FROM tasks "
                    ."WHERE user_id = '$id' ORDER BY dt_add DESC";
        $result = mysqli_query($con, $sql);
    }
    return array_or_error($result);
};

/**
 * Create the array with existed emails from our database
 *
 * @param object $con Our connect to MySQL database
 * @return function array_or_error
 */
function get_users(object $con): array {
    $sql = 'SELECT * FROM users';
    $result = mysqli_query($con, $sql);
    return array_or_error($result);
};

/**
 * Add new task in our table of tasks
 *
 * @param object $con Our connect to MySQL database
 * @param array $task_form Info about new task from users
 * @param int $id Our correct id of user who use our website right now
 * @return $result
 */
function add_task(object $con, array $task_form, $id) {
    $sql = "INSERT INTO tasks (dt_add, user_id, name, project_id, dt_deadline, file, status) VALUES (NOW(), '$id', ?, ?, ?, ?, 0)";
    $stmt = db_get_prepare_stmt($con, $sql, $task_form);
    return mysqli_stmt_execute($stmt);
};

/**
 * Add new user in our table of users
 *
 * @param object $con Our connect to MySQL database
 * @param array $user_form Info about new user
 * @return $result
 */
function add_user(object $con, array $user_form) {
    $password = password_hash($user_form['password'], PASSWORD_DEFAULT);
    $sql = 'INSERT INTO users (email, password, name) VALUES (?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$user_form['email'], $password, $user_form['name']]);
    return mysqli_stmt_execute($stmt);
};

/**
 * Give us the data of the user who logged in on our website
 * @param object $con Our connect to MySQL database
 * @param array $user_auth Our user date to auth on our website
 * @return funtion array_or_error
 */
function get_user(object $con, array $user_auth) {
    $email = mysqli_real_escape_string($con, $user_auth['email']);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    return array_or_error($result)[0];
};

/**
 * Add new project in our table of projects
 *
 * @param object $con Our connect to MySQL database
 * @param array $project_form Info about new project from users
 * @param int $id Our correct id of user who use our website right now
 * @return $result
 */
function add_project(object $con, array $project_form, $id) {
    $sql = "INSERT INTO projects (user_id, name) VALUES ('$id', ?)";
    $stmt = db_get_prepare_stmt($con, $sql, $project_form);
    return mysqli_stmt_execute($stmt);
};
