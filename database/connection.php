<?php
// database/connection.php - Database connection and setup

class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $username;
    private $password;
    private $database;
    
    private function __construct() {
        // Load database configuration
        $config = require __DIR__ . '/../config/database.php';
        
        $this->host = $config['host'];
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->database = $config['database'];
        
        $this->connect();
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // In production, log this error instead of displaying it
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed. Please check ur database configuration.");
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database query failed: " . $e->getMessage());
            throw new Exception("Database query failed: " . $e->getMessage());
        }
    }
    
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function insert($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }
    
    public function update($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function delete($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollback();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    public function isConnected() {
        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    // Test database connection
    public static function testConnection() {
        try {
            $db = self::getInstance();
            return $db->isConnected();
        } catch (Exception $e) {
            return false;
        }
    }
    
    // Get database configuration status
    public static function getConfigStatus() {
        $configFile = __DIR__ . '/../config/database.php';
        
        if (!file_exists($configFile)) {
            return [
                'status' => 'missing',
                'message' => 'Database configuration file not found'
            ];
        }
        
        $config = require $configFile;
        
        $requiredKeys = ['host', 'username', 'password', 'database'];
        foreach ($requiredKeys as $key) {
            if (!isset($config[$key]) || empty($config[$key])) {
                return [
                    'status' => 'incomplete',
                    'message' => "Database configuration missing: {$key}"
                ];
            }
        }
        
        try {
            $testConnection = self::testConnection();
            if ($testConnection) {
                return [
                    'status' => 'connected',
                    'message' => 'Database connection successful'
                ];
            } else {
                return [
                    'status' => 'connection_failed',
                    'message' => 'Cannot connect to database with provided credentials'
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}

// Helper function to get database instance
function db() {
    return Database::getInstance();
}

// Helper function to escape HTML safely
function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>