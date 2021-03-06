CREATE DATABASE things_are_okay
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE things_are_okay;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) UNIQUE NOT NULL,
    user_name VARCHAR(128) UNIQUE NOT NULL,
    password CHAR(64) NOT NULL
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(128) NOT NULL,
    user_id INT
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status TINYINT DEFAULT 0,
    task_name VARCHAR(200) NOT NULL,
    run_to DATE,
    user_id INT,
    project_id INT,
    file_path VARCHAR(512),
    file_name VARCHAR (128)
);

CREATE FULLTEXT INDEX tasks_ft_search ON tasks(task_name);
