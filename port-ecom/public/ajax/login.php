<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/functions.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    $response['message'] = 'Invalid security token';
    echo json_encode($response);
    exit;
}

$email = sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $response['message'] = 'Please fill in all fields';
    echo json_encode($response);
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    $response['message'] = 'Invalid email or password';
    echo json_encode($response);
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $user['role'];

$response['success'] = true;
$response['message'] = 'Login successful!';
$response['redirect'] = SITE_URL . 'index.php';

echo json_encode($response);

