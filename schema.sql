DROP DATABASE IF EXISTS business_is_good;

CREATE DATABASE business_is_good
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE business_is_good;

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(128) NOT NULL UNIQUE,
    password CHAR(255) NOT NULL,
    name VARCHAR(255),
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tasks (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    project_id INT,
    FOREIGN KEY (project_id) REFERENCES projects(id),
    name VARCHAR(255),
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    dt_deadline TIMESTAMP NULL,
    status BOOLEAN DEFAULT FALSE,
    file VARCHAR(255) DEFAULT NULL
);

CREATE INDEX t_name ON tasks(name);
CREATE FULLTEXT INDEX tasks_search ON tasks(name);
