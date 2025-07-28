<?php
// config/database.php - Database configuration

return [
    // Database Connection Settings
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? '3306',
    'database' => $_ENV['DB_DATABASE'] ?? 'cat_ai',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
    
    // Connection Options
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ],
    
    // Connection Pool Settings
    'persistent' => false,
    'timeout' => 30,
    
    // Development Settings
    'log_queries' => $_ENV['DB_LOG_QUERIES'] ?? false,
    'slow_query_threshold' => 2000, // milliseconds
];

/*
 * Environment Variables Setup:
 * 
 * Create a .env file in ur project root with:
 * 
 * DB_HOST=localhost
 * DB_PORT=3306
 * DB_DATABASE=cat_ai
 * DB_USERNAME=ur_username
 * DB_PASSWORD=ur_password
 * DB_LOG_QUERIES=false
 * 
 * Or update the values directly in this file for quick setup.
 */

?>