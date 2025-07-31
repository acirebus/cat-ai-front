<?php
require_once __DIR__ . '/../components/php/functions.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
$id = intval($_POST['id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? 'user';
$status = $_POST['status'] ?? 'active';
try {
    $data = [];
    if ($username) $data['username'] = $username;
    if ($email) $data['email'] = $email;
    $data['is_admin'] = ($role === 'admin') ? 1 : 0;
    updateUser($id, $data);
    echo json_encode(['success' => true, 'message' => 'User updated']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
