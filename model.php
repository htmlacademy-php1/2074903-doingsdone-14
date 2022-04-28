<?php

if ($con) {
//Получим из таблицы проектов массив для пользователя с id=2 id, именем проекта и подсчетом задач по этому проекту из таблицы задач
    $sql = 'SELECT p.id, p.name, COUNT(t.name) AS count '
                .'FROM projects p '
                .'LEFT JOIN tasks t ON p.id = t.project_id '
                .'WHERE p.user_id = 2 '
                .'GROUP BY p.id';
    $result = mysqli_query($con, $sql);
        if ($result) {
            $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            $error = mysqli_error($con);
            $content = print('Ошибка: '.$error);
        }

//Получим из таблицы задач массив задач для пользователя с id=2
    $sql = 'SELECT id, name, status, DATE_FORMAT(dt_deadline, "%d.%m.%Y") as dt_deadline, file FROM tasks '
                .'WHERE user_id = 2';
    $result = mysqli_query($con, $sql);
        if ($result) {
            $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        else {
            $error = mysqli_error($con);
            $content = print('Ошибка: '.$error);
        }
};
