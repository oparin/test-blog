-- Create db
CREATE DATABASE IF NOT EXISTS test_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE test_blog;

-- Table categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table posts
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content LONGTEXT NOT NULL,
    image VARCHAR(255),
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table many to many
CREATE TABLE posts_categories (
     post_id INT,
     category_id INT,
     PRIMARY KEY (post_id, category_id),
     FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
     FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Indexes
CREATE INDEX idx_posts_created_at ON posts(created_at);
CREATE INDEX idx_posts_views ON posts(views);
CREATE INDEX idx_categories_name ON categories(name);
