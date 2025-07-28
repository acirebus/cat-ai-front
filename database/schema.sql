-- cat-AI Database Schema
-- MySQL/MariaDB compatible SQL schema

-- Create database
CREATE DATABASE IF NOT EXISTS `cat_ai` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cat_ai`;

-- Users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `avatar` varchar(255) DEFAULT NULL,
  `theme_preference` enum('light','dark') DEFAULT 'dark',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`),
  KEY `idx_is_admin` (`is_admin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chats table
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_chats_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages table
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `message_type` enum('user','ai','system','image') NOT NULL DEFAULT 'user',
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chat_id` (`chat_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_message_type` (`message_type`),
  KEY `idx_created_at` (`created_at`),
  CONSTRAINT `fk_messages_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_messages_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- API Usage Logs table
CREATE TABLE IF NOT EXISTS `api_usage_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `endpoint` varchar(255) NOT NULL,
  `method` enum('GET','POST','PUT','DELETE','PATCH') NOT NULL,
  `response_time` int(11) NOT NULL COMMENT 'Response time in milliseconds',
  `status_code` int(11) DEFAULT NULL,
  `request_size` int(11) DEFAULT NULL COMMENT 'Request size in bytes',
  `response_size` int(11) DEFAULT NULL COMMENT 'Response size in bytes',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_endpoint` (`endpoint`),
  KEY `idx_method` (`method`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_status_code` (`status_code`),
  CONSTRAINT `fk_api_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Session table (optional, for database-based sessions)
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_last_activity` (`last_activity`),
  CONSTRAINT `fk_sessions_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `created_at`) VALUES 
('admin', 'admin@cat-ai.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW())
ON DUPLICATE KEY UPDATE `username` = `username`;

-- Insert sample regular user (password: user123)
INSERT INTO `users` (`username`, `email`, `password`, `is_admin`, `created_at`) VALUES 
('testuser', 'user@cat-ai.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NOW())
ON DUPLICATE KEY UPDATE `username` = `username`;

-- Insert sample chat data
INSERT INTO `chats` (`user_id`, `title`, `created_at`) VALUES 
(1, 'welcome to cat-AI!', NOW()),
(2, 'my first chat with cat-AI', NOW())
ON DUPLICATE KEY UPDATE `title` = `title`;

-- Insert sample messages
INSERT INTO `messages` (`chat_id`, `user_id`, `content`, `message_type`, `created_at`) VALUES 
(1, 1, 'hello cat-AI!', 'user', NOW()),
(1, 1, 'meow! welcome to cat-AI, ur purrfect AI companion! 🐱', 'ai', NOW()),
(2, 2, 'can u help me with something?', 'user', NOW()),
(2, 2, 'meow! of course! i''m here to help u with anything u need! 🐾', 'ai', NOW())
ON DUPLICATE KEY UPDATE `content` = `content`;

-- Insert sample API usage logs
INSERT INTO `api_usage_logs` (`user_id`, `endpoint`, `method`, `response_time`, `status_code`, `created_at`) VALUES 
(1, '/api/chat', 'POST', 150, 200, NOW()),
(1, '/api/cat/image', 'GET', 89, 200, NOW()),
(2, '/api/chat', 'POST', 200, 200, NOW()),
(NULL, '/auth/login', 'POST', 45, 200, NOW()),
(1, '/api/cat/fact', 'GET', 120, 200, NOW())
ON DUPLICATE KEY UPDATE `endpoint` = `endpoint`;