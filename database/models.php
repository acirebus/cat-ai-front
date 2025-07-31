<?php
// database/models.php - Database models and CRUD operations

require_once __DIR__ . '/connection.php';

class UserModel {
    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
        return $this->db->fetch($sql, [$email]);
    }
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllUsers() {
        $sql = "SELECT id, username, email, is_admin, last_login, created_at 
                FROM users ORDER BY created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getUserById($id) {
        $sql = "SELECT id, username, email, is_admin, avatar, theme_preference, last_login, created_at 
                FROM users WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->db->fetch($sql, [$username]);
    }
    
    public function createUser($data) {
        $sql = "INSERT INTO users (username, email, password, is_admin, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $isAdmin = isset($data['is_admin']) ? (int)$data['is_admin'] : 0;
        
        return $this->db->insert($sql, [
            $data['username'],
            $data['email'],
            $hashedPassword,
            $isAdmin
        ]);
    }
    
    public function updateUser($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['username'])) {
            $fields[] = "username = ?";
            $params[] = $data['username'];
        }
        
        if (isset($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }
        
        if (isset($data['password']) && !empty($data['password'])) {
            $fields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['is_admin'])) {
            $fields[] = "is_admin = ?";
            $params[] = (int)$data['is_admin'];
        }
        
        if (isset($data['theme_preference'])) {
            $fields[] = "theme_preference = ?";
            $params[] = $data['theme_preference'];
        }
        
        $fields[] = "updated_at = NOW()";
        $params[] = $id;
        
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
        return $this->db->update($sql, $params);
    }
    
    public function deleteUser($id) {
        // Don't delete admin users
        $user = $this->getUserById($id);
        if ($user && $user['is_admin']) {
            throw new Exception("Cannot delete admin users");
        }
        
        $sql = "DELETE FROM users WHERE id = ? AND is_admin = 0";
        return $this->db->delete($sql, [$id]);
    }
    
    public function updateLastLogin($id) {
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }
    
    public function getUserCount() {
        $sql = "SELECT COUNT(*) as count FROM users";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
    
    public function getAdminCount() {
        $sql = "SELECT COUNT(*) as count FROM users WHERE is_admin = 1";
        $result = $this->db->fetch($sql);
        return $result['count'];
    }
}

class ChatModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getUserChats($userId) {
        $sql = "SELECT id, title, created_at, updated_at 
                FROM chats WHERE user_id = ? AND is_active = 1 
                ORDER BY updated_at DESC";
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function getChatById($id) {
        $sql = "SELECT * FROM chats WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function createChat($userId, $title) {
        $sql = "INSERT INTO chats (user_id, title, created_at) VALUES (?, ?, NOW())";
        return $this->db->insert($sql, [$userId, $title]);
    }
    
    public function updateChat($id, $title) {
        $sql = "UPDATE chats SET title = ?, updated_at = NOW() WHERE id = ?";
        return $this->db->update($sql, [$title, $id]);
    }
    
    public function deleteChat($id) {
        $sql = "UPDATE chats SET is_active = 0 WHERE id = ?";
        return $this->db->update($sql, [$id]);
    }
}

class MessageModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getChatMessages($chatId) {
        $sql = "SELECT * FROM messages WHERE chat_id = ? ORDER BY created_at ASC";
        return $this->db->fetchAll($sql, [$chatId]);
    }
    
    public function createMessage($chatId, $userId, $content, $type = 'user') {
        $sql = "INSERT INTO messages (chat_id, user_id, content, message_type, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        return $this->db->insert($sql, [$chatId, $userId, $content, $type]);
    }
    
    public function deleteMessage($id) {
        $sql = "DELETE FROM messages WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
}

class ApiUsageLogModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAllLogs($limit = 100) {
        $sql = "SELECT * FROM api_usage_logs ORDER BY created_at DESC LIMIT ?";
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function getLogById($id) {
        $sql = "SELECT * FROM api_usage_logs WHERE id = ?";
        return $this->db->fetch($sql, [$id]);
    }
    
    public function createLog($data) {
        $sql = "INSERT INTO api_usage_logs (user_id, endpoint, method, response_time, status_code, 
                request_size, response_size, ip_address, user_agent, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        return $this->db->insert($sql, [
            $data['user_id'] ?? null,
            $data['endpoint'],
            $data['method'],
            $data['response_time'],
            $data['status_code'] ?? null,
            $data['request_size'] ?? null,
            $data['response_size'] ?? null,
            $data['ip_address'] ?? $_SERVER['REMOTE_ADDR'] ?? null,
            $data['user_agent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
    
    public function deleteLog($id) {
        $sql = "DELETE FROM api_usage_logs WHERE id = ?";
        return $this->db->delete($sql, [$id]);
    }
    
    public function getLogStats() {
        $sql = "SELECT 
                    COUNT(*) as total_requests,
                    AVG(response_time) as avg_response_time,
                    COUNT(DISTINCT endpoint) as unique_endpoints,
                    COUNT(DISTINCT user_id) as unique_users,
                    DATE(created_at) as date
                FROM api_usage_logs 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function deleteOldLogs($days = 30) {
        $sql = "DELETE FROM api_usage_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
        return $this->db->delete($sql, [$days]);
    }
}

// Factory class to get model instances
class ModelFactory {
    public static function user() {
        return new UserModel();
    }
    
    public static function chat() {
        return new ChatModel();
    }
    
    public static function message() {
        return new MessageModel();
    }
    
    public static function apiLog() {
        return new ApiUsageLogModel();
    }
}

?>
