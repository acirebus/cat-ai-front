<?php
require_once __DIR__ . '/../components/php/functions.php';
header('Content-Type: application/json');
try {
    $users = getAllUsers();
    $formatted = array_map(function($u) {
        return [
            'id' => $u['id'],
            'username' => $u['username'],
            'email' => $u['email'],
            'role' => !empty($u['is_admin']) ? 'admin' : 'user',
            'status' => 'active',
            'created_at' => isset($u['created_at']) ? date('Y-m-d', strtotime($u['created_at'])) : ''
        ];
    }, $users);
    echo json_encode(['users' => $formatted]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['users' => [], 'error' => $e->getMessage()]);
}
