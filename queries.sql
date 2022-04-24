--Запишем данные по нашим задачам в таблицу с задачами
INSERT INTO tasks (id, user_id, project_id, name, dt_deadline, status) VALUES
    (1, 2, 3, 'Собеседование в IT компании', '01.12.2019',),
    (2, 2, 3, 'Выполнить тестовое задание', '25.12.2019',),
    (3, 3, 2, 'Сделать задание первого раздела', '21.12.2019', true),
    (4, 1, 1, 'Встреча с другом', '22.12.2019',),
    (5, 3, 4, 'Купить корм для кота', ,),
    (6, 3, 4, 'Заказать пиццу', ,);

--Запишем наши проекты в таблицу с проектами
INSERT INTO projects (id, name, user_id) VALUES
    (1, 'Входящие',1),
    (2, 'Учеба', 3),
    (3, 'Работа', 2),
    (4, 'Домашние дела', 3),
    (5, 'Авто', 1);

--Создадим несколько пользователей
INSERT INTO users (id, email, password, name) VALUES
    (1, 'keks@gmail.com', '12578g', 'keks'),
    (2, 'catnotdog@yandex.ru', 'gav057', 'your_cat'),
    (3, 'kotik@,ail.ru', '3pv007', 'kot_ik');

--Получим список всех проектов для одного пользователя
SELECT u.name, p.name FROM projects p INNER JOIN users u
    ON u.id = p.user_id GROUP BY u.name;

--Получим список всех задач для одного проекта
SELECT p.name, t.name FROM tasks t INNER JOIN projects p
    ON p.id = t.project_id GROUP BY p.name;

--Изменим статус задачи под идентификтором 6 на выполнено
UPDATE tasks SET status = 'true' WHERE id = 6;

--Изменим название задачи под идентификатором 4
UPDATE tasks SET name = 'Встреча с подругой' WHERE id = 4;
