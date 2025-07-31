// <?php
// // register.php - Handles user registration via AJAX
// require_once __DIR__ . '/components/php/functions.php';
// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405);
//     echo json_encode(['success' => false, 'message' => 'Method not allowed']);
//     exit;
// }

// // Get POST data
// $username = trim($_POST['username'] ?? '');
// $email = trim($_POST['email'] ?? '');
// $password = $_POST['password'] ?? '';

// try {
//     $user = createUser([
//         'username' => $username,
//         'email' => $email,
//         'password' => $password
//     ]);
//     echo json_encode(['success' => true, 'message' => 'Registration successful', 'user' => $user]);
// } catch (Exception $e) {
//     http_response_code(400);
//     echo json_encode(['success' => false, 'message' => $e->getMessage()]);
// }
