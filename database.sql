CREATE DATABASE IF NOT EXISTS blog_db
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE blog_db;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT,
  PRIMARY KEY (id),
  name VARCHAR(100),
  email VARCHAR(100),
  password VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED AUTO_INCREMENT,
  PRIMARY KEY (id),
  title VARCHAR(255),
  content TEXT,
  cover VARCHAR(255),
  publication_date DATETIME,
  user_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT,
  PRIMARY KEY (id),
  name VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS article_categories (
  article_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (article_id) REFERENCES articles(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS comments (
  id INT UNSIGNED AUTO_INCREMENT,
  PRIMARY KEY (id),
  content TEXT,
  comment_date DATETIME,
  user_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  article_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (article_id) REFERENCES articles(id)
);