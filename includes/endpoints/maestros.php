<?php
require_once '../../cn.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

// Array de tablas permitidas para gestión de maestros
// Solo permitiremos manipular tablas que empiecen por 'mst_'
$allowed_tables = ['mst_tipo_servicio', 'mst_proveedores'];

function is_allowed($table, $allowed_tables)
{
    return in_array($table, $allowed_tables);
}

switch ($action) {
    case 'list_tables':
        // Retornamos la lista de tablas configuradas como maestros
        $tables = [];
        foreach ($allowed_tables as $tb) {
            // Formatear nombre para mostrar (quitar mst_ y reemplazar _ por espacio)
            $display = ucwords(str_replace('_', ' ', str_replace('mst_', '', $tb)));
            $tables[] = ['table' => $tb, 'display_name' => $display];
        }
        echo json_encode(['status' => 'success', 'data' => $tables]);
        break;

    case 'list_records':
        $table = $_POST['table_name'] ?? '';
        if (!is_allowed($table, $allowed_tables)) {
            echo json_encode(['status' => 'error', 'message' => 'Tabla no permitida o no encontrada']);
            exit;
        }

        $sql = "SELECT * FROM $table ORDER BY id ASC";
        $result = $cnn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'create':
    case 'update':
        $table = $_POST['table_name'] ?? '';
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $url = $_POST['url'] ?? null; // Added for providers

        if (!is_allowed($table, $allowed_tables)) {
            echo json_encode(['status' => 'error', 'message' => 'Tabla no permitida']);
            exit;
        }

        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'El nombre es obligatorio']);
            exit;
        }

        if ($action === 'create') {
            // Check if column exists or just assume if it's provider
            if ($table === 'mst_proveedores') {
                $stmt = $cnn->prepare("INSERT INTO $table (name, description, url) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $description, $url);
            } else {
                $stmt = $cnn->prepare("INSERT INTO $table (name, description) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $description);
            }
        } else {
            if ($table === 'mst_proveedores') {
                $stmt = $cnn->prepare("UPDATE $table SET name = ?, description = ?, url = ? WHERE id = ?");
                $stmt->bind_param("sssi", $name, $description, $url, $id);
            } else {
                $stmt = $cnn->prepare("UPDATE $table SET name = ?, description = ? WHERE id = ?");
                $stmt->bind_param("ssi", $name, $description, $id);
            }
        }

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Registro guardado correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar (posible duplicado): ' . $cnn->error]);
        }
        break;

    case 'delete':
        $table = $_POST['table_name'] ?? '';
        $id = $_POST['id'] ?? null;

        if (!is_allowed($table, $allowed_tables)) {
            echo json_encode(['status' => 'error', 'message' => 'Tabla no permitida']);
            exit;
        }

        $stmt = $cnn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Registro eliminado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $cnn->error]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
        break;
}
