CREATE DATABASE doingsdone;

USE doingsdone;

CREATE TABLE projects (
id INT AUTO_INCREMENT PRIMARY KEY,
name CHAR(128),
user_id CHAR(128)
);

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
email CHAR(128),
login CHAR(128),
password CHAR(128),
avatar_path CHAR(255)
);

CREATE TABLE tasks (
id INT AUTO_INCREMENT PRIMARY KEY,
project_id INT,
user_id INT,
task CHAR(128),
date_done DATETIME,
project CHAR(128),
done INT,
path CHAR(255)
);

CREATE INDEX task ON tasks(task);
CREATE UNIQUE INDEX email ON users(email);

