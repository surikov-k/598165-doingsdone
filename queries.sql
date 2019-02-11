INSERT INTO users (email, name, password) VALUES
('konst@mail.ru', 'Константин', 'secret'),
('ivan@mail.ru', 'Иван', 'enigma');

INSERT INTO projects (title, user_id) VALUES
('Входящие', 1),
('Учеба', 1),
('Работа', 1),
('Домашние дела', 1),
('Авто', 2);

INSERT INTO tasks (due_date, title, user_id, project_id) VALUES
('2019.12.01', 'Собеседование в IT компании', 1, 3),
('2019.12.25', 'Выполнить тестовое задание', 1, 3),
('2019.12.21', 'Сделать задание первого раздела', 1, 2),
('2019.12.22','Встреча с другом', 1, 1),
(null, 'Купить корм для кота', 1, 4),
(null, 'Заказать пиццу', 2, 4);

-- получить список из всех проектов для одного пользователя;
SELECT * FROM projects WHERE user_id = 1;

-- получить список из всех задач для одного проекта;
SELECT * FROM tasks WHERE project_id = 3;

-- пометить задачу как выполненную;
UPDATE tasks SET status = TRUE WHERE id = 1;

-- обновить название задачи по её идентификатору.
UPDATE tasks SET title = 'Новая задача' WHERE id = 1;
