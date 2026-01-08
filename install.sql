-- Librava Database Schema
-- Installation Instructions:
-- 1. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Create a new database named 'librava'
-- 3. Import this file into the 'librava' database
-- 4. Default admin credentials: username = admin, password = admin123

-- Drop tables if exist (for clean reinstall)
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `admins`;

-- Create admins table
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: admin123)
-- Password hash generated with: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO `admins` (`username`, `password_hash`) VALUES
('admin', '$2y$10$jbu3eo7Izkkg5sE/fL826OGQFnMgedSYXAVuCTQyZ.YMAcwM4y9Ey');

-- Create books table
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `pdf_path` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample books (optional - you can remove this section)
INSERT INTO `books` (`title`, `author`, `description`, `category`) VALUES
('Getting Started with Librava', 'Mohammad Taha Abdinasab', 'Learn how to use Librava digital library system for managing your book collection efficiently.', 'Tutorial'),
('Sample Book', 'Test Author', 'This is a sample book entry to demonstrate the system. You can delete this from the Android app.', 'Sample');

