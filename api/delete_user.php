<?php
require_once __DIR__ . '/../components/php/functions.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
$id = intval($_POST['id'] ?? 0);
try {
    deleteUser($id);
    echo json_encode(['success' => true, 'message' => 'User deleted']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
