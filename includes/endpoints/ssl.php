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
        $sql = "SELECT s.*, c.name as client_name, v.name as server_name, p.name as provider_name, p.url as provider_url 
                FROM ssl_certificates s 
                LEFT JOIN clients c ON s.client_id = c.id 
                INNER JOIN servers v ON s.server_id = v.id
                LEFT JOIN mst_proveedores p ON s.provider_id = p.id
                ORDER BY s.expiration_date ASC";

        $result = $cnn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $row['owner_display'] = $row['client_id'] ? $row['client_name'] : 'INTERACTO SA';
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("SELECT * FROM ssl_certificates WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Certificado no encontrado']);
        }
        break;

    case 'create':
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $server_id = $_POST['server_id'];
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;
        $creation_date = !empty($_POST['creation_date']) ? $_POST['creation_date'] : null;
        $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $responsible_email = $_POST['responsible_email'] ?? '';

        $stmt = $cnn->prepare("INSERT INTO ssl_certificates (client_id, server_id, provider_id, creation_date, expiration_date, responsible_email) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $client_id, $server_id, $provider_id, $creation_date, $expiration_date, $responsible_email);

        if ($stmt->execute()) {
            logEvent('create_ssl', ['server_id' => $server_id]);
            echo json_encode(['status' => 'success', 'message' => 'Certificado SSL registrado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $server_id = $_POST['server_id'];
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;
        $creation_date = !empty($_POST['creation_date']) ? $_POST['creation_date'] : null;
        $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $responsible_email = $_POST['responsible_email'] ?? '';

        $stmt = $cnn->prepare("UPDATE ssl_certificates SET client_id = ?, server_id = ?, provider_id = ?, creation_date = ?, expiration_date = ?, responsible_email = ? WHERE id = ?");
        $stmt->bind_param("iiisssi", $client_id, $server_id, $provider_id, $creation_date, $expiration_date, $responsible_email, $id);

        if ($stmt->execute()) {
            logEvent('update_ssl', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Certificado actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM ssl_certificates WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('delete_ssl', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Certificado eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
?>