<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'cn.php';

echo "<h2>Migración de Base de Datos - Módulo Cuentas</h2>";

$sql = "CREATE TABLE IF NOT EXISTS provider_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    provider_id INT NULL,
    url VARCHAR(255) NULL,
    description VARCHAR(255) NULL,
    username VARCHAR(100) NULL,
    password VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES mst_proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($cnn->query($sql)) {
    echo "<div style='color: green; font-weight: bold;'>✓ Tabla 'provider_accounts' creada o ya existente.</div>";

    // Verificar si la tabla existe realmente
    $res = $cnn->query("SHOW TABLES LIKE 'provider_accounts'");
    if ($res->num_rows > 0) {
        echo "<p>Confirmación: La tabla existe en la base de datos.</p>";
    } else {
        echo "<p style='color: red;'>Error: El query se ejecutó pero la tabla no aparece.</p>";
    }
} else {
    echo "<div style='color: red; font-weight: bold;'>✗ Error creando tabla: " . $cnn->error . "</div>";
}

echo "<br><a href='index.php?view=cuentas'>Volver al módulo de cuentas</a>";
?>