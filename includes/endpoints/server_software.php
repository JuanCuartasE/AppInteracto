<?php
session_start();
require_once '../../cn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['interacto_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $server_id = $_POST['server_id'] ?? 0;
        $stmt = $cnn->prepare("SELECT * FROM server_software WHERE server_id = ? ORDER BY name ASC");
        $stmt->bind_param("i", $server_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        $stmt->close();
        break;

    case 'create':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $server_id = $_POST['server_id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $version = $_POST['version'] ?? '';
        $description = $_POST['description'] ?? '';
        $install_command = $_POST['install_command'] ?? '';

        $stmt = $cnn->prepare("INSERT INTO server_software (server_id, name, version, description, install_command) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $server_id, $name, $version, $description, $install_command);

        if ($stmt->execute()) {
            logEvent('ADD_SOFTWARE', ['server_id' => $server_id, 'software' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Software añadido correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al añadir software']);
        }
        $stmt->close();
        break;

    case 'update':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $version = $_POST['version'] ?? '';
        $description = $_POST['description'] ?? '';
        $install_command = $_POST['install_command'] ?? '';

        $stmt = $cnn->prepare("UPDATE server_software SET name = ?, version = ?, description = ?, install_command = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $version, $description, $install_command, $id);

        if ($stmt->execute()) {
            logEvent('UPDATE_SOFTWARE', ['id' => $id, 'name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Software actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar software']);
        }
        $stmt->close();
        break;

    case 'delete':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("DELETE FROM server_software WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('DELETE_SOFTWARE', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Software eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar software']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
