<?php
session_start();
require_once '../../cn.php';

header('Content-Type: application/json');

if (!checkPermission('OWNER')) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $stmt = $cnn->prepare("SELECT id, name, email, role, status FROM users ORDER BY name ASC");
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'create':
        $name = $_POST['name'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $cnn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $pass, $role);
        if ($stmt->execute()) {
            logEvent("CREATE_USER", ['email' => $email, 'role' => $role]);
            echo json_encode(['status' => 'success', 'message' => 'Usuario creado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear usuario']);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        if (!empty($_POST['password'])) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $cnn->prepare("UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $pass, $role, $id);
        } else {
            $stmt = $cnn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $email, $role, $id);
        }

        if ($stmt->execute()) {
            logEvent("UPDATE_USER", ['id' => $id, 'email' => $email]);
            echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar']);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent("DELETE_USER", ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
?>