CREATE DATABASE IF NOT EXISTS staff_permission_system;
USE staff_permission_system;
CREATE TABLE IF NOT EXISTS users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE,
password VARCHAR(255),
role ENUM('admin','staff','user'),
full_name VARCHAR(100)
);
CREATE TABLE IF NOT EXISTS permissions (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT,
start_date DATE,
end_date DATE,
reason TEXT,
status ENUM('pending','approved','rejected') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id)
);
INSERT INTO users (username,password,role,full_name) VALUES
('admin1','$2y$10$K1F36e1IbgvJk1kg2A/WAu6poEJ92yXU6n50sNx7v0PjqS96gFGQO','admin','Admin One'),
('staff1','$2y$10$K1F36e1IbgvJk1kg2A/WAu6poEJ92yXU6n50sNx7v0PjqS96gFGQO','staff','Staff One'),
('user1','$2y$10$K1F36e1IbgvJk1kg2A/WAu6poEJ92yXU6n50sNx7v0PjqS96gFGQO','user','User One');
