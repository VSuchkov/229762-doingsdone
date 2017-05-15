CREATE DATABASE doingsdone;

USE doingsdone;

  CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128),
  user_id CHAR(128)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email CHAR(128),
  login CHAR(128),
  password CHAR(32),
  avatar_path CHAR(255)
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT,
  user_id INT
  task CHAR;
  date_done DATETIME;
  category INT;
  done INT;
  path CHAR(255)
);

CREATE INDEX task ON tasks(task);
CREATE UNIQUE INDEX email ON users(email);

