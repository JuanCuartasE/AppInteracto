<?php
require_once '../../cn.php';

header('Content-Type: application/json');

// Check permission
session_start();
if (!isset($_SESSION['interacto_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $sql = "SELECT d.*, c.name as client_name, p.name as provider_name, p.url as provider_url 
                FROM domains d 
                LEFT JOIN clients c ON d.client_id = c.id 
                LEFT JOIN mst_proveedores p ON d.provider_id = p.id
                ORDER BY d.expiration_date ASC";

        $result = $cnn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Logic for owner name
            $row['owner_display'] = $row['client_id'] ? $row['client_name'] : 'INTERACTO SA';
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("SELECT * FROM domains WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'data' => $row]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Dominio no encontrado']);
        }
        break;

    case 'create':
        $name = $_POST['name'];
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $registration_date = !empty($_POST['registration_date']) ? $_POST['registration_date'] : null;
        $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0.00;
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;

        $stmt = $cnn->prepare("INSERT INTO domains (name, client_id, provider_id, registration_date, expiration_date, sale_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siissd", $name, $client_id, $provider_id, $registration_date, $expiration_date, $sale_price);

        if ($stmt->execute()) {
            logEvent('create_domain', ['name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Dominio registrado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $client_id = !empty($_POST['client_id']) ? $_POST['client_id'] : null;
        $registration_date = !empty($_POST['registration_date']) ? $_POST['registration_date'] : null;
        $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;
        $sale_price = !empty($_POST['sale_price']) ? $_POST['sale_price'] : 0.00;
        $provider_id = !empty($_POST['provider_id']) ? $_POST['provider_id'] : null;

        $stmt = $cnn->prepare("UPDATE domains SET name = ?, client_id = ?, provider_id = ?, registration_date = ?, expiration_date = ?, sale_price = ? WHERE id = ?");
        $stmt->bind_param("siissdi", $name, $client_id, $provider_id, $registration_date, $expiration_date, $sale_price, $id);

        if ($stmt->execute()) {
            logEvent('update_domain', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Dominio actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM domains WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent('delete_domain', ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Dominio eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error: ' . $cnn->error]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
?>