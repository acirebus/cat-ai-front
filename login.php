// <?php
// // login.php - Handles user login via AJAX
// require_once __DIR__ . '/components/php/functions.php';
// session_start();
// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405);
//     echo json_encode(['success' => false, 'message' => 'Method not allowed']);
//     exit;
// }

// $email = trim($_POST['email'] ?? '');
// $password = $_POST['password'] ?? '';

// // Replace with your actual user lookup logic
// $user = findUserByEmail($email); // You must implement this in functions.php or models.php
// if ($user && password_verify($password, $user['password'])) {
//     // Set session for user
//     $_SESSION['user'] = [
//         'id' => $user['id'],
//         'name' => $user['username'],
//         'email' => $user['email'],
//         'is_admin' => !empty($user['is_admin']) ? true : false,
//         'isGuest' => false,
//         'role' => !empty($user['is_admin']) ? 'admin' : 'user'
//     ];
//     $_SESSION['isLoggedIn'] = true;
//     $_SESSION['isGuest'] = false;
//     echo json_encode(['success' => true, 'message' => 'Login successful', 'user' => $_SESSION['user']]);
// } else {
//     http_response_code(401);
//     echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
// }
