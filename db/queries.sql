-- DROP DATABASE IF EXISTS db_php;
-- CREATE DATABASE db_php;
USE db_php;

DROP TABLE IF EXISTS server_mailing;

CREATE TABLE IF NOT EXISTS server_mailing(
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(30) NOT NULL,
  file_url VARCHAR(255) NOT NULL,
  dating TIMESTAMP DEFAULT NOW()
);