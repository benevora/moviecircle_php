CREATE DATABASE moviecircle;

use moviecircle;

CREATE TABLE users (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    lastname VARCHAR(100),
    email VARCHAR(200),
    password VARCHAR(200),
    image VARCHAR(200),
    token VARCHAR(200),
    bio TEXT,
    is_admin BOOLEAN DEFAULT FALSE,
    is_banned BOOLEAN DEFAULT FALSE
);

CREATE TABLE movies (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100),
    description TEXT,
    image VARCHAR(200),
    trailer VARCHAR(150),
    category VARCHAR(50),
    length VARCHAR(50),
    users_id INT UNSIGNED,
    FOREIGN KEY(users_id) REFERENCES users(id)
);

CREATE TABLE reviews (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    rating INT,
    review TEXT,
    users_id INT UNSIGNED,
    movies_id INT UNSIGNED,
    FOREIGN KEY (users_id) REFERENCES users(id),
    FOREIGN KEY (movies_id) REFERENCES movies(id)
);

CREATE TABLE followers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  follower_id INT UNSIGNED,
  following_id INT UNSIGNED,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE (follower_id, following_id)
);

SET SQL_SAFE_UPDATES = 0;

UPDATE users
SET is_admin = 1
WHERE email = 'your-email@example.com';

UPDATE movies 
SET category = 'Fantasy/Fiction' 
WHERE category LIKE '%fantasy%fiction%';

SET SQL_SAFE_UPDATES = 1;