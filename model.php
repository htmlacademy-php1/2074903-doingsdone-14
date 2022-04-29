<?php

if ($con) {
    /**
     * Create the array with projects and counted tasks for each of them for user with id=2
     *
     * @param object $con Our connect to MySQL database
     * @return array $projects Our array of projects
     */
    function get_projects(object $con):array {
        $sql = 'SELECT p.id, p.name, COUNT(t.name) AS count '
                    .'FROM projects p '
                    .'LEFT JOIN tasks t ON p.id = t.project_id '
                    .'WHERE p.user_id = 2 '
                    .'GROUP BY p.id';
        $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                $content = print('Ошибка: '.$error);
            } else {
                $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return ($projects);
            }
    };

    /**
     * Create the array with tasks for user with id=2
     *
     * @param object $con Our connect to MySQL database
     * @return array $tasks Our array of tasks
     */
    function get_tasks(object $con):array {
        $sql = 'SELECT id, name, status, DATE_FORMAT(dt_deadline, "%d.%m.%Y") as dt_deadline, file FROM tasks '
                    .'WHERE user_id = 2';
        $result = mysqli_query($con, $sql);
            if (!$result) {
                $error = mysqli_error($con);
                $content = print('Ошибка: '.$error);
            } else {
                $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return ($tasks);
            }
    };
}
