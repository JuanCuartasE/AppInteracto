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
        $client_id = $_POST['client_id'];
        $stmt = $cnn->prepare("SELECT * FROM contacts WHERE client_id = ? ORDER BY is_primary DESC, first_name ASC");
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc())
            $data[] = $row;
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'create':
        $client_id = $_POST['client_id'];
        $salutation = $_POST['salutation'] ?? null;
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $position = $_POST['position'] ?? null;
        $email_1 = $_POST['email_1'];
        $email_2 = $_POST['email_2'] ?? null;
        $phone_1 = $_POST['phone_1'];
        $phone_2 = $_POST['phone_2'] ?? null;
        $whatsapp_1 = isset($_POST['whatsapp_1']) ? 1 : 0;
        $whatsapp_2 = isset($_POST['whatsapp_2']) ? 1 : 0;
        $is_primary = isset($_POST['is_primary']) ? 1 : 0;

        // Si es primario, resetear otros del mismo cliente
        if ($is_primary) {
            $stmt_reset = $cnn->prepare("UPDATE contacts SET is_primary = 0 WHERE client_id = ?");
            $stmt_reset->bind_param("i", $client_id);
            $stmt_reset->execute();
        }

        $stmt = $cnn->prepare("INSERT INTO contacts (client_id, salutation, first_name, last_name, position, email_1, email_2, phone_1, phone_2, whatsapp_1, whatsapp_2, is_primary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssiii", $client_id, $salutation, $first_name, $last_name, $position, $email_1, $email_2, $phone_1, $phone_2, $whatsapp_1, $whatsapp_2, $is_primary);

        if ($stmt->execute()) {
            logEvent("CREATE_CONTACT", ['client_id' => $client_id, 'name' => $first_name . ' ' . $last_name]);
            echo json_encode(['status' => 'success', 'message' => 'Contacto creado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al crear contacto']);
        }
        break;

    case 'update':
        $id = $_POST['id'];
        $client_id = $_POST['client_id'];
        $salutation = $_POST['salutation'] ?? null;
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $position = $_POST['position'] ?? null;
        $email_1 = $_POST['email_1'];
        $email_2 = $_POST['email_2'] ?? null;
        $phone_1 = $_POST['phone_1'];
        $phone_2 = $_POST['phone_2'] ?? null;
        $whatsapp_1 = isset($_POST['whatsapp_1']) ? 1 : 0;
        $whatsapp_2 = isset($_POST['whatsapp_2']) ? 1 : 0;
        $is_primary = isset($_POST['is_primary']) ? 1 : 0;

        if ($is_primary) {
            $stmt_reset = $cnn->prepare("UPDATE contacts SET is_primary = 0 WHERE client_id = ? AND id != ?");
            $stmt_reset->bind_param("ii", $client_id, $id);
            $stmt_reset->execute();
        }

        $stmt = $cnn->prepare("UPDATE contacts SET salutation=?, first_name=?, last_name=?, position=?, email_1=?, email_2=?, phone_1=?, phone_2=?, whatsapp_1=?, whatsapp_2=?, is_primary=? WHERE id=?");
        $stmt->bind_param("ssssssssiiii", $salutation, $first_name, $last_name, $position, $email_1, $email_2, $phone_1, $phone_2, $whatsapp_1, $whatsapp_2, $is_primary, $id);

        if ($stmt->execute()) {
            logEvent("UPDATE_CONTACT", ['id' => $id, 'name' => $first_name . ' ' . $last_name]);
            echo json_encode(['status' => 'success', 'message' => 'Contacto actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar']);
        }
        break;

    case 'delete':
        $id = $_POST['id'];
        $stmt = $cnn->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            logEvent("DELETE_CONTACT", ['id' => $id]);
            echo json_encode(['status' => 'success', 'message' => 'Contacto eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
}
?>