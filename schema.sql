CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
  id  INT AUTO_INCREMENT PRIMARY KEY,
  title CHAR(128) NOT NULL,
  user_id INT NOT NULL
);

CREATE INDEX p_user ON projects(user_id);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  create_time DATETIME DEFAULT NOW(),
  due_date DATETIME,
  completion_time DATETIME,
  completed BOOL DEFAULT FALSE,
  title CHAR(255) NOT NULL,
  attachment CHAR(128),
  user_id INT NOT NULL,
  project_id INT
);

CREATE INDEX t_user ON tasks(user_id);
CREATE INDEX t_due ON tasks(due_date);


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_time DATETIME DEFAULT NOW(),
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL
);

CREATE INDEX u_email ON users(email);
CREATE INDEX u_name ON users(name);
