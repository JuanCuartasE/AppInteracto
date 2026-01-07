<?php
session_start();
require_once '../../cn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['interacto_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        $stmt = $cnn->prepare("SELECT * FROM clients ORDER BY name ASC");
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc())
            $data[] = $row;
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'fetch':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cliente no encontrado']);
        }
        break;

    case 'create':
        $name = $_POST['name'];
        $type = $_POST['type'];
        $doc_type = $_POST['doc_type'] ?? null;
        $doc_number = $_POST['doc_number'] ?? null;
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;
        $municipality = $_POST['municipality'] ?? null;
        $website = $_POST['website'] ?? null;
        $description = $_POST['description'] ?? null;

        $stmt = $cnn->prepare("INSERT INTO clients (name, type, doc_type, doc_number, email, phone, address, municipality, website, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $type, $doc_type, $doc_number, $email, $phone, $address, $municipality, $website, $description);
        if ($stmt->execute()) {
            logEvent("CREATE_CLIENT", ['name' => $name, 'type' => $type]);
            echo json_encode(['status' => 'success', 'message' => 'Cliente creado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear cliente']);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $doc_type = $_POST['doc_type'] ?? null;
        $doc_number = $_POST['doc_number'] ?? null;
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $address = $_POST['address'] ?? null;
        $municipality = $_POST['municipality'] ?? null;
        $website = $_POST['website'] ?? null;
        $description = $_POST['description'] ?? null;

        $stmt = $cnn->prepare("UPDATE clients SET name=?, type=?, doc_type=?, doc_number=?, email=?, phone=?, address=?, municipality=?, website=?, description=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $name, $type, $doc_type, $doc_number, $email, $phone, $address, $municipality, $website, $description, $id);

        if ($stmt->execute()) {
            logEvent("UPDATE_CLIENT", ['id' => $id, 'name' => $name]);
            echo json_encode(['status' => 'success', 'message' => 'Cliente actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar']);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent("DELETE_CLIENT", ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Cliente eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
?>