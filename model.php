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
}
