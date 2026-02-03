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

$name = sanitize($_POST['name'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';

$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) {
    $errors[] = 'Valid 10-digit phone number is required';
}

if (empty($password) || strlen($password) < 6) {
    $errors[] = 'Password must be at least 6 characters';
}

if (!empty($errors)) {
    $response['message'] = implode(', ', $errors);
    echo json_encode($response);
    exit;
}

$db = getDB();
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->fetch()) {
    $response['message'] = 'Email already registered';
    echo json_encode($response);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')");
$stmt->execute([$name, $email, $phone, $hashedPassword]);

$userId = $db->lastInsertId();

$_SESSION['user_id'] = $userId;
$_SESSION['user_name'] = $name;
$_SESSION['user_email'] = $email;
$_SESSION['user_role'] = 'customer';

$response['success'] = true;
$response['message'] = 'Account created successfully!';
$response['redirect'] = SITE_URL . 'index.php';

echo json_encode($response);

