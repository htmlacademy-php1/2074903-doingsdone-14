/* Создадим несколько пользователей */
INSERT INTO users (email, password, name) VALUES
    ('keks@gmail.com', '12578g', 'keks'),
    ('catnotdog@yandex.ru', 'gav057', 'your_cat'),
    ('kotik@mail.ru', '3pv007', 'kot_ik');

/* Запишем наши проекты в таблицу с проектами */
INSERT INTO projects (name, user_id) VALUES
    ('Входящие', (SELECT id FROM users WHERE id = 1)),
    ('Учеба', (SELECT id FROM users WHERE id = 3)),
    ('Работа', (SELECT id FROM users WHERE id = 2)),
    ('Домашние дела', (SELECT id FROM users WHERE id = 3)),
    ('Авто', (SELECT id FROM users WHERE id = 1));

/* Запишем данные по нашим задачам в таблицу с задачами */
INSERT INTO tasks (user_id, project_id, name, dt_deadline, status) VALUES
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 3), 'Собеседование в IT компании', '01.12.2019', 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 3), 'Выполнить тестовое задание', '25.12.2019', 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 2), 'Сделать задание первого раздела', '21.12.2019', 1),
    ((SELECT id FROM users WHERE id = 1), (SELECT id FROM projects WHERE id = 1), 'Встреча с другом', '22.12.2019', 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 4), 'Купить корм для кота', NULL, 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 4), 'Заказать пиццу', NULL, 0);

/* Получим список всех проектов для одного пользователя */
SELECT u.name, p.name FROM projects p INNER JOIN users u
    ON u.id = p.user_id WHERE u.id = '1';

/* Получим список всех задач для одного проекта */
SELECT p.name, t.name FROM tasks t INNER JOIN projects p
    ON p.id = t.project_id WHERE p.id = '3';

/* Изменим статус задачи под идентификтором 6 на выполнено */
UPDATE tasks SET status = 1 WHERE id = 6;

/* Изменим название задачи под идентификатором 4 */
UPDATE tasks SET name = 'Встреча с подругой' WHERE id = 4;
