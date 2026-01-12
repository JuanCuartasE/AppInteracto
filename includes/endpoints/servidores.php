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
        $query = "SELECT s.*, c.name as client_name 
                  FROM servers s 
                  LEFT JOIN clients c ON s.client_id = c.id 
                  ORDER BY s.id DESC";
        $result = $cnn->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("SELECT s.*, c.name as client_name FROM servers s LEFT JOIN clients c ON s.client_id = c.id WHERE s.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $server = $res->fetch_assoc();
        if ($server) {
            echo json_encode(['status' => 'success', 'data' => $server]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Servidor no encontrado']);
        }
        $stmt->close();
        break;

    case 'create':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $name = $_POST['name'] ?? '';
        $ipv4 = $_POST['ipv4'] ?? '';
        $ipv6 = $_POST['ipv6'] ?? '';
        $os = $_POST['os'] ?? '';
        $os_version = $_POST['os_version'] ?? '';
        $created_at = $_POST['created_at'] ?? null;

        $stmt = $cnn->prepare("INSERT INTO servers (client_id, name, ipv4, ipv6, os, os_version, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $client_id, $name, $ipv4, $ipv6, $os, $os_version, $created_at);

        if ($stmt->execute()) {
            logEvent('CREATE_SERVER', ['name' => $name, 'client_id' => $client_id]);
            echo json_encode(['status' => 'success', 'message' => 'Servidor creado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el servidor: ' . $cnn->error]);
        }
        $stmt->close();
        break;

    case 'update':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $name = $_POST['name'] ?? '';
        $ipv4 = $_POST['ipv4'] ?? '';
        $ipv6 = $_POST['ipv6'] ?? '';
        $os = $_POST['os'] ?? '';
        $os_version = $_POST['os_version'] ?? '';
        $created_at = $_POST['created_at'] ?? null;

        $stmt = $cnn->prepare("UPDATE servers SET client_id = ?, name = ?, ipv4 = ?, ipv6 = ?, os = ?, os_version = ?, created_at = ? WHERE id = ?");
        $stmt->bind_param("issssssi", $client_id, $name, $ipv4, $ipv6, $os, $os_version, $created_at, $id);

        if ($stmt->execute()) {
            logEvent('UPDATE_SERVER', ['id' => $id, 'name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Servidor actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el servidor']);
        }
        $stmt->close();
        break;

    case 'delete':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("DELETE FROM servers WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('DELETE_SERVER', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Servidor eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el servidor']);
        }
        $stmt->close();
        break;

    case 'list_clients':
        // Auxiliary list for the creation dropdown
        $result = $cnn->query("SELECT id, name FROM clients ORDER BY name ASC");
        $clients = [];
        // Add default Company owner
        $clients[] = ['id' => '', 'name' => 'INTERACTO SA'];

        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $clients]);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
