<?php
require_once '../../cn.php';

header('Content-Type: application/json');

session_start();
if (!isset($_SESSION['interacto_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $sql = "SELECT a.*, p.name as provider_name 
                FROM provider_accounts a 
                LEFT JOIN mst_proveedores p ON a.provider_id = p.id
                ORDER BY a.name ASC";

        $result = $cnn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("SELECT * FROM provider_accounts WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cuenta no encontrada']);
        }
        break;

    case 'create':
        $name = $_POST['name'];
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;
        $url = $_POST['url'] ?? '';
        $description = $_POST['description'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $cnn->prepare("INSERT INTO provider_accounts (name, provider_id, url, description, username, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $name, $provider_id, $url, $description, $username, $password);

        if ($stmt->execute()) {
            logEvent('create_account', ['name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Cuenta registrada correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;
        $url = $_POST['url'] ?? '';
        $description = $_POST['description'] ?? '';
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $cnn->prepare("UPDATE provider_accounts SET name = ?, provider_id = ?, url = ?, description = ?, username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sissssi", $name, $provider_id, $url, $description, $username, $password, $id);

        if ($stmt->execute()) {
            logEvent('update_account', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Cuenta actualizada']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM provider_accounts WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('delete_account', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Cuenta eliminada']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
?>