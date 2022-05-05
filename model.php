<?php

if ($con) {
    /**
     * Create the array with projects and counted tasks for each of them for user with id=2
     *
     * @param object $con Our connect to MySQL database
     * @return function array_or_error
     */
    function get_projects(object $con):array {
        $sql = 'SELECT p.id, p.name, COUNT(t.name) AS count '
                    .'FROM projects p '
                    .'LEFT JOIN tasks t ON p.id = t.project_id '
                    .'WHERE p.user_id = 2 '
                    .'GROUP BY p.id';
        $result = mysqli_query($con, $sql);
        return array_or_error($result);
    };

    /**
     * Create the array with tasks for user with id=2
     *
     * @param object $con Our connect to MySQL database
     * @param int $project_id Identify our project
     * @return function array_or_error
     */
    function get_tasks(object $con, $project_id):array {
        if (!empty($project_id)) {
            $sql = 'SELECT id, name, status, DATE_FORMAT(dt_deadline, "%d.%m.%Y") as dt_deadline, file FROM tasks '
                        .'WHERE user_id = 2 AND project_id =' . $project_id;
        } else {
            $sql = 'SELECT id, name, status, DATE_FORMAT(dt_deadline, "%d.%m.%Y") as dt_deadline, file FROM tasks '
                        .'WHERE user_id = 2 ORDER BY dt_add DESC';
        }
        $result = mysqli_query($con, $sql);
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
     * @return $result
     */
    function add_task(object $con, array $task_form) {
        $sql = 'INSERT INTO tasks (dt_add, user_id, name, project_id, dt_deadline, file, status) VALUES (NOW(), 2, ?, ?, ?, ?, 0)';
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
}
