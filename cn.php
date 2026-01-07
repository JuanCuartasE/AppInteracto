<?php
// Cargar variables de entorno manualmente si no se usa composer/phpdotenv
function loadEnv($path)
{
    if (!file_exists($path))
        return false;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

loadEnv(__DIR__ . '/.env');

$env = $_ENV['APP_ENV'] ?? 'local';

if ($env === 'production') {
    $host = $_ENV['DB_PROD_HOST'];
    $port = $_ENV['DB_PROD_PORT'];
    $user = $_ENV['DB_PROD_USER'];
    $pass = $_ENV['DB_PROD_PASS'];
    $db = $_ENV['DB_PROD_NAME'];
} else {
    $host = $_ENV['DB_LOCAL_HOST'];
    $port = $_ENV['DB_LOCAL_PORT'];
    $user = $_ENV['DB_LOCAL_USER'];
    $pass = $_ENV['DB_LOCAL_PASS'];
    $db = $_ENV['DB_LOCAL_NAME'];
}

try {
    $cnn = new mysqli($host, $user, $pass, $db, $port);
    if ($cnn->connect_error) {
        throw new Exception("Error de conexión: " . $cnn->connect_error);
    }
    $cnn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error crítico: " . $e->getMessage());
}

/**
 * Función global para registrar eventos
 */
function logEvent($action, $details = null)
{
    global $cnn;
    $userId = $_SESSION['interacto_user_id'] ?? null;
    $role = $_SESSION['interacto_user_role'] ?? 'GUEST';
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

    $stmt = $cnn->prepare("INSERT INTO event_log (user_id, role, action, details, ip) VALUES (?, ?, ?, ?, ?)");
    $detailsJson = $details ? json_encode($details) : null;
    $stmt->bind_param("issss", $userId, $role, $action, $detailsJson, $ip);
    $stmt->execute();
    $stmt->close();
}

/**
 * Función para verificar permisos
 */
function checkPermission($requiredRole)
{
    $userRole = $_SESSION['interacto_user_role'] ?? null;
    if (!$userRole)
        return false;

    $rolesPriority = [
        'OWNER' => 4,
        'ADMINISTRADOR' => 3,
        'COLABORADOR' => 2,
        'MONITOR' => 1
    ];

    return ($rolesPriority[$userRole] ?? 0) >= ($rolesPriority[$requiredRole] ?? 0);
}
?>