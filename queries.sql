--получаем список из всех проектов для одного пользователя
SELECT * FROM projects, WHERE user_id = 100;

--получаем список из всех задач для одного проекта
SELECT * FROM tasks WHERE project_id = 50;

--помечаем задачу как выполненную
UPDATE tasks SET done = 1 WHERE task_id = 84;

--добавляем новый проект
INSERT INTO projects SET name = "Свободное время", user_id = 10;

--добавляем новую задачу (включает указание проекта, дату завершения, название)
INSERT INTO tasks SET task = "Погулять", date_done = '2017-05-20', project_id = 8, user_id = 5;

--получаем все задачи для завтрашнего дня
SELECT * FROM tasks WHERE date_done = (NOW(), INTERVAL 1 DAY);

--обновляем название задачи по её идентификатору
UPDATE tasks SET task = "купить воды" WHERE id = 1;
