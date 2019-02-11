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
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  due_date TIMESTAMP,
  completion_time TIMESTAMP,
  status BOOL DEFAULT 0,
  title CHAR(255) NOT NULL,
  attachment CHAR(128),
  user_id INT NOT NULL
);

CREATE INDEX t_user ON tasks(user_id);
CREATE INDEX t_due ON tasks(due_date);


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL
);

CREATE INDEX u_email ON users(email);
CREATE INDEX u_name ON users(name);
