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
    ('Домашние дела', (SELECT id FROM users WHERE id = 7)),
    ('Авто', (SELECT id FROM users WHERE id = 1)),
    ('Входящие', (SELECT id FROM users WHERE id = 7)),
    ('Учеба', (SELECT id FROM users WHERE id = 7));

/* Запишем данные по нашим задачам в таблицу с задачами */
INSERT INTO tasks (user_id, project_id, name, dt_deadline, status) VALUES
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 3), 'Собеседование в IT компании', '2019-12-01 00:00:00', 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 3), 'Выполнить тестовое задание', '2019-12-25 00:00:00', 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 2), 'Сделать задание первого раздела', '2019-12-21 00:00:00', 1),
    ((SELECT id FROM users WHERE id = 1), (SELECT id FROM projects WHERE id = 1), 'Встреча с другом', '2019-12-22 00:00:00', 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 4), 'Купить корм для кота', NULL, 0),
    ((SELECT id FROM users WHERE id = 3), (SELECT id FROM projects WHERE id = 4), 'Заказать пиццу', NULL, 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 6), 'Принять ванну', '2022-04-28 00:00:00', 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 6), 'Взять выходной на 1 день', NULL, 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 7), 'Отправить задание на проверку', '2022-04-26 00:00:00', 0),
    ((SELECT id FROM users WHERE id = 2), (SELECT id FROM projects WHERE id = 7), 'Вынести работу с MySQL из index.php', '2022-04-26 00:00:00', 1);

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
