<?php
session_start();
require_once '../../cn.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Campos obligatorios vacíos']);
    exit;
}

$stmt = $cnn->prepare("SELECT id, name, password, role FROM users WHERE email = ? AND status = 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['interacto_user_id'] = $user['id'];
    $_SESSION['interacto_user_name'] = $user['name'];
    $_SESSION['interacto_user_role'] = $user['role'];

    logEvent('LOGIN', ['email' => $email]);

    echo json_encode(['status' => 'success', 'message' => 'Acceso concedido']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
}

$stmt->close();
?>