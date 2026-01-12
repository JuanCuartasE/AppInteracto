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
        $server_id = isset($_POST['server_id']) ? intval($_POST['server_id']) : null;
        $sql = "SELECT s.*, c.name as client_name, srv.name as server_name 
                  FROM services s 
                  LEFT JOIN clients c ON s.client_id = c.id 
                  LEFT JOIN servers srv ON s.server_id = srv.id";

        if ($server_id) {
            $sql .= " WHERE s.server_id = $server_id";
        }

        $sql .= " ORDER BY s.id DESC";
        $result = $cnn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("SELECT s.*, c.name as client_name, srv.name as server_name 
                                FROM services s 
                                LEFT JOIN clients c ON s.client_id = c.id 
                                LEFT JOIN servers srv ON s.server_id = srv.id 
                                WHERE s.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $service = $res->fetch_assoc();
        if ($service) {
            echo json_encode(['status' => 'success', 'data' => $service]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Servicio no encontrado']);
        }
        $stmt->close();
        break;

    case 'create':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $client_id = $_POST['client_id'] ?? null;
        $server_id = !empty($_POST['server_id']) ? $_POST['server_id'] : null;
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? 'Página Web';
        $path = $_POST['path'] ?? '';

        $stmt = $cnn->prepare("INSERT INTO services (client_id, server_id, name, type, path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $client_id, $server_id, $name, $type, $path);

        if ($stmt->execute()) {
            logEvent('CREATE_SERVICE', ['name' => $name, 'client_id' => $client_id]);
            echo json_encode(['status' => 'success', 'message' => 'Servicio creado correctamente', 'id' => $cnn->insert_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el servicio: ' . $cnn->error]);
        }
        $stmt->close();
        break;

    case 'update':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $client_id = $_POST['client_id'] ?? null;
        $server_id = !empty($_POST['server_id']) ? $_POST['server_id'] : null;
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? 'Página Web';
        $path = $_POST['path'] ?? '';

        $stmt = $cnn->prepare("UPDATE services SET client_id = ?, server_id = ?, name = ?, type = ?, path = ? WHERE id = ?");
        $stmt->bind_param("iisssi", $client_id, $server_id, $name, $type, $path, $id);

        if ($stmt->execute()) {
            logEvent('UPDATE_SERVICE', ['id' => $id, 'name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Servicio actualizado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el servicio']);
        }
        $stmt->close();
        break;

    case 'delete':
        if (!checkPermission('ADMINISTRADOR')) {
            echo json_encode(['status' => 'error', 'message' => 'Permiso denegado']);
            exit;
        }
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('DELETE_SERVICE', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Servicio eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el servicio']);
        }
        $stmt->close();
        break;

    case 'list_clients':
        $result = $cnn->query("SELECT id, name FROM clients ORDER BY name ASC");
        $clients = [];
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $clients]);
        break;

    case 'list_servers':
        $client_id = $_POST['client_id'] ?? 0;
        if ($client_id) {
            $stmt = $cnn->prepare("SELECT id, name FROM servers WHERE client_id = ? ORDER BY name ASC");
            $stmt->bind_param("i", $client_id);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $cnn->query("SELECT id, name FROM servers ORDER BY name ASC");
        }
        $servers = [];
        while ($row = $result->fetch_assoc()) {
            $servers[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $servers]);
        break;

    // Service Details CRUD
    case 'list_details':
        $service_id = $_POST['service_id'] ?? 0;
        $stmt = $cnn->prepare("SELECT * FROM service_details WHERE service_id = ? ORDER BY id ASC");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        $stmt->close();
        break;

    case 'create_detail':
        $service_id = $_POST['service_id'] ?? 0;
        $role_name = $_POST['role_name'] ?? '';
        $url = $_POST['url'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $observations = $_POST['observations'] ?? '';

        $stmt = $cnn->prepare("INSERT INTO service_details (service_id, role_name, url, username, password, observations) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $service_id, $role_name, $url, $username, $password, $observations);

        if ($stmt->execute()) {
            logEvent('CREATE_SERVICE_DETAIL', ['service_id' => $service_id, 'role' => $role_name]);
            echo json_encode(['status' => 'success', 'message' => 'Detalle técnico agregado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear detalle']);
        }
        $stmt->close();
        break;

    case 'update_detail':
        $id = $_POST['id'] ?? 0;
        $role_name = $_POST['role_name'] ?? '';
        $url = $_POST['url'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $observations = $_POST['observations'] ?? '';

        $stmt = $cnn->prepare("UPDATE service_details SET role_name = ?, url = ?, username = ?, password = ?, observations = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $role_name, $url, $username, $password, $observations, $id);

        if ($stmt->execute()) {
            logEvent('UPDATE_SERVICE_DETAIL', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Detalle actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar']);
        }
        $stmt->close();
        break;

    case 'delete_detail':
        $id = $_POST['id'] ?? 0;
        $stmt = $cnn->prepare("DELETE FROM service_details WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('DELETE_SERVICE_DETAIL', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Detalle eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
        }
        $stmt->close();
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
?>