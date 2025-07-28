<?php
session_start();

// Set default user as guest - no login required
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 'guest_' . uniqid(),
        'name' => 'anonymous cat',
        'email' => 'guest@cat-ai.com',
        'role' => 'user',
        'isGuest' => true
    ];
}

// Handle route switching
$page = $_GET['page'] ?? 'chat';

switch ($page) {
    case 'management':
        include 'pages/management.php';
        break;
    case 'login':
        include 'pages/login.php';
        break;
    case 'chat':
    default:
        include 'pages/chat.php';
        break;
}
?>