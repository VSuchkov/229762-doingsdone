INSERT INTO projects SET name = "Входящие";
INSERT INTO projects SET name = "Учеба";
INSERT INTO projects SET name = "Работа";
INSERT INTO projects SET name = "Домашние дела";
INSERT INTO projects SET name = "Авто";

INSERT INTO users SET email = 'ignat.v@gmail.com', login = 'Игнат', password = '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka';
INSERT INTO users SET email = 'kitty_93@li.ru', login = 'Леночка', password = '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa';
INSERT INTO users SET email = 'warrior07@mail.ru', login = 'Руслан', password = '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW';

INSERT INTO tasks
SET project_id = 1, user_id = 1, task = "Собеседование в IT компании", date_done = "2017-06-01", project = "Работа", done = 0;
INSERT INTO tasks
SET project_id = 1, user_id = 1, task = "Выполнить тестовое задание", date_done = "2017-05-25", project = "Работа", done = 0;
INSERT INTO tasks
SET project_id = 2, user_id = 1, task = "Сделать задание первого раздела", date_done = "2017-04-21", project = "Учеба", done = 1;
INSERT INTO tasks
SET project_id = 3, user_id = 2, task = "Встреча с другом", date_done = "2017-04-22", project = "Входящие", done = 0;
INSERT INTO tasks
SET project_id = 4, user_id = 2, task = "Купить корм для кота", date_done = null, project = "Домашние дела", done = 0;
INSERT INTO tasks
SET project_id = 4, user_id = 3, task = "Заказать пиццу", date_done = null, project = "Домашние дела", done = 0;
