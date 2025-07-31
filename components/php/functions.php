<?php
// components/php/functions.php - Utility functions and database operations

/**
 * Find user by email
 */
function findUserByEmail($email) {
    $userModel = new UserModel();
    $sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
    // Use the database connection from UserModel
    return $userModel->getUserByEmail($email);
}

// Include database models
require_once __DIR__ . '/../../database/models.php';

/**
 * Include and render a page template
 */
function renderPage($pageName, $data = []) {
    // Extract variables for use in the template
    extract($data);
    
    // Include the page template
    include __DIR__ . "/../../pages/{$pageName}.php";
}

/**
 * Sanitize and escape user input for safe HTML output
 */
function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'M j, Y') {
    return date($format, strtotime($date));
}

/**
 * Generate user avatar initials
 */
function getUserInitials($username) {
    return strtoupper(substr($username, 0, 1));
}

/**
 * Get user role display name
 */
function getUserRoleDisplay($user) {
    if ($user['is_admin']) {
        return 'admin kitty';
    } elseif ($user['id'] === 999) {
        return 'guest kitty';
    } else {
        return 'regular kitty';
    }
}

/**
 * Calculate average response time from API usage logs
 */
function calculateAverageResponseTime($apiUsage) {
    if (empty($apiUsage)) {
        return 0;
    }
    
    $totalTime = array_sum(array_column($apiUsage, 'response_time'));
    return round($totalTime / count($apiUsage));
}

/**
 * Count unique endpoints from API usage logs
 */
function countUniqueEndpoints($apiUsage) {
    if (empty($apiUsage)) {
        return 0;
    }
    
    return count(array_unique(array_column($apiUsage, 'endpoint')));
}

/**
 * Check if user is authenticated
 */
function isAuthenticated() {
    return isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isAuthenticated() && 
           isset($_SESSION['user']) && 
           isset($_SESSION['user']['is_admin']) && 
           $_SESSION['user']['is_admin'] === true;
}

/**
 * Check if user is guest
 */
function isGuest() {
    return isset($_SESSION['isGuest']) && $_SESSION['isGuest'] === true;
}

/**
 * Get current theme
 */
function getCurrentTheme() {
    return $_SESSION['theme'] ?? 'dark';
}

/**
 * Get current user
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Add message to session messages
 */
function addSessionMessage($content, $type = 'user') {
    if (!isset($_SESSION['messages'])) {
        $_SESSION['messages'] = [];
    }
    
    $_SESSION['messages'][] = [
        'type' => $type,
        'content' => $content,
        'timestamp' => time()
    ];
}

/**
 * Get session messages
 */
function getSessionMessages() {
    return $_SESSION['messages'] ?? [];
}

/**
 * Clear session messages
 */
function clearSessionMessages() {
    $_SESSION['messages'] = [];
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && 
           hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Log API usage (for development/debugging)
 */
function logApiUsage($endpoint, $method, $responseTime, $userId = null) {
    // In a real application, this would save to database
    // For now, we'll just store in session for demonstration
    if (!isset($_SESSION['api_logs'])) {
        $_SESSION['api_logs'] = [];
    }
    
    $_SESSION['api_logs'][] = [
        'id' => count($_SESSION['api_logs']) + 1,
        'user_id' => $userId ?? (getCurrentUser()['id'] ?? 'anonymous'),
        'endpoint' => $endpoint,
        'method' => $method,
        'response_time' => $responseTime,
        'created_at' => date('Y-m-d H:i:s')
    ];
}

/**
 * Get API usage logs from session
 */
function getApiUsageLogs() {
    return $_SESSION['api_logs'] ?? [];
}

/**
 * Send JSON response
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Handle AJAX errors consistently
 */
function handleAjaxError($message, $statusCode = 400) {
    sendJsonResponse([
        'success' => false,
        'message' => $message
    ], $statusCode);
}

/**
 * Handle AJAX success consistently
 */
function handleAjaxSuccess($data = [], $message = null) {
    $response = ['success' => true];
    
    if ($message) {
        $response['message'] = $message;
    }
    
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    
    sendJsonResponse($response);
}

/**
 * Validate username
 */
function validateUsername($username) {
    return !empty($username) && 
           strlen($username) >= 3 && 
           strlen($username) <= 20 && 
           preg_match('/^[a-zA-Z0-9_]+$/', $username);
}

/**
 * Validate password
 */
function validatePassword($password) {
    return !empty($password) && strlen($password) >= 6;
}

/**
 * Get Bootstrap theme class
 */
function getBootstrapThemeClass() {
    $theme = getCurrentTheme();
    return $theme === 'dark' ? 'dark' : 'light';
}

/**
 * Get body classes for theme
 */
function getBodyClasses() {
    $theme = getCurrentTheme();
    $classes = ['cat-theme'];
    
    if ($theme === 'dark') {
        $classes[] = 'bg-dark';
        $classes[] = 'text-light';
    } else {
        $classes[] = 'bg-light';
        $classes[] = 'text-dark';
    }
    
    return implode(' ', $classes);
}

/**
 * Include head section with Bootstrap and custom CSS
 */
function includeHead($title = 'cat-AI') {
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escapeHtml($title); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/cat-ai-bootstrap.css" rel="stylesheet">
    <?php
}

/**
 * Include scripts section with Bootstrap and custom JS
 */
function includeScripts() {
    ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/cat-ai.js"></script>
    <?php
}

// =================================================================
// DATABASE CRUD FUNCTIONS
// =================================================================

/**
 * Get all users from database
 */
function getAllUsers() {
    try {
        return ModelFactory::user()->getAllUsers();
    } catch (Exception $e) {
        error_log("Get users error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get user by ID
 */
function getUserById($id) {
    try {
        return ModelFactory::user()->getUserById($id);
    } catch (Exception $e) {
        error_log("Get user error: " . $e->getMessage());
        return null;
    }
}

/**
 * Create new user
 */
function createUser($data) {
    try {
        // Validate required fields
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new Exception("username, email, and password are required");
        }
        
        // Validate username format
        if (!validateUsername($data['username'])) {
            throw new Exception("invalid username format");
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("invalid email format");
        }
        
        // Validate password
        if (!validatePassword($data['password'])) {
            throw new Exception("password must be at least 6 characters");
        }
        
        return ModelFactory::user()->createUser($data);
    } catch (Exception $e) {
        error_log("Create user error: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Update user
 */
function updateUser($id, $data) {
    try {
        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("invalid user ID");
        }
        
        // Validate username if provided
        if (isset($data['username']) && !validateUsername($data['username'])) {
            throw new Exception("invalid username format");
        }
        
        // Validate email if provided
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("invalid email format");
        }
        
        // Validate password if provided
        if (isset($data['password']) && !empty($data['password']) && !validatePassword($data['password'])) {
            throw new Exception("password must be at least 6 characters");
        }
        
        return ModelFactory::user()->updateUser($id, $data);
    } catch (Exception $e) {
        error_log("Update user error: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Delete user
 */
function deleteUser($id) {
    try {
        // Validate ID
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("invalid user ID");
        }
        
        return ModelFactory::user()->deleteUser($id);
    } catch (Exception $e) {
        error_log("Delete user error: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get all API usage logs
 */
function getAllApiLogs($limit = 100) {
    try {
        return ModelFactory::apiLog()->getAllLogs($limit);
    } catch (Exception $e) {
        error_log("Get API logs error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get API log by ID
 */
function getApiLogById($id) {
    try {
        return ModelFactory::apiLog()->getLogById($id);
    } catch (Exception $e) {
        error_log("Get API log error: " . $e->getMessage());
        return null;
    }
}

/**
 * Create API usage log
 */
function createApiLog($data) {
    try {
        return ModelFactory::apiLog()->createLog($data);
    } catch (Exception $e) {
        error_log("Create API log error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete API usage log
 */
function deleteApiLog($id) {
    try {
        if (empty($id) || !is_numeric($id)) {
            throw new Exception("invalid log ID");
        }
        
        return ModelFactory::apiLog()->deleteLog($id);
    } catch (Exception $e) {
        error_log("Delete API log error: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Get API usage statistics
 */
function getApiStats() {
    try {
        return ModelFactory::apiLog()->getLogStats();
    } catch (Exception $e) {
        error_log("Get API stats error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get user chats
 */
function getUserChats($userId) {
    try {
        return ModelFactory::chat()->getUserChats($userId);
    } catch (Exception $e) {
        error_log("Get user chats error: " . $e->getMessage());
        return [];
    }
}

/**
 * Create new chat
 */
function createChat($userId, $title) {
    try {
        return ModelFactory::chat()->createChat($userId, $title);
    } catch (Exception $e) {
        error_log("Create chat error: " . $e->getMessage());
        throw $e;
    }
}

/**
 * Check database connection status
 */
function getDatabaseStatus() {
    return Database::getConfigStatus();
}

/**
 * Test if database is connected
 */
function isDatabaseConnected() {
    try {
        return Database::testConnection();
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Enhanced API usage logging with database storage
 */
function logApiUsageToDatabase($endpoint, $method, $responseTime, $statusCode = null, $userId = null) {
    try {
        $data = [
            'user_id' => $userId,
            'endpoint' => $endpoint,
            'method' => $method,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        return createApiLog($data);
    } catch (Exception $e) {
        error_log("Log API usage error: " . $e->getMessage());
        // Fallback to session logging
        logApiUsage($endpoint, $method, $responseTime, $userId);
        return false;
    }
}

/**
 * Get data for management panel with database fallback
 */
function getManagementData() {
    $data = [
        'users' => [],
        'apiUsage' => [],
        'dbConnected' => isDatabaseConnected()
    ];
    
    if ($data['dbConnected']) {
        $data['users'] = getAllUsers();
        $data['apiUsage'] = getAllApiLogs(50);
    } else {
        // Fallback to session data
        $data['apiUsage'] = getApiUsageLogs();
    }
    
    return $data;
}

/**
 * Initialize database tables if needed
 */
function initializeDatabase() {
    try {
        $db = Database::getInstance();
        
        // Check if users table exists
        $result = $db->query("SHOW TABLES LIKE 'users'");
        if ($result->rowCount() === 0) {
            // Run schema file
            $schema = file_get_contents(__DIR__ . '/../../database/schema.sql');
            $statements = explode(';', $schema);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $db->query($statement);
                }
            }
            
            return true;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("Initialize database error: " . $e->getMessage());
        return false;
    }
}

?>
