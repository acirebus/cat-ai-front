<?php
require_once __DIR__ . '/../components/php/functions.php';
header('Content-Type: application/json');
try {
    $logs = getAllApiLogs();
    $formatted = array_map(function($log) {
        return [
            'timestamp' => isset($log['created_at']) ? date('Y-m-d H:i:s', strtotime($log['created_at'])) : '',
            'user' => $log['user_id'] ?? '-',
            'endpoint' => $log['endpoint'] ?? '-',
            'method' => $log['method'] ?? '-',
            'status' => $log['status'] ?? 200,
            'response_time' => $log['response_time'] ?? 0,
            'tokens_used' => $log['tokens_used'] ?? '-'
        ];
    }, $logs);
    echo json_encode(['logs' => $formatted]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['logs' => [], 'error' => $e->getMessage()]);
}
