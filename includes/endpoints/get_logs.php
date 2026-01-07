<?php
session_start();
require_once '../../cn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['interacto_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$stmt = $cnn->prepare("SELECT l.*, u.name as user_name FROM event_log l LEFT JOIN users u ON l.user_id = u.id ORDER BY l.created_at DESC LIMIT 10");
$stmt->execute();
$result = $stmt->get_result();
$logs = [];

while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode(['status' => 'success', 'data' => $logs]);

$stmt->close();
?>