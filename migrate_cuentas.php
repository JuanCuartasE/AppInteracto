<?php
require_once 'cn.php';

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
    echo "Tabla provider_accounts creada exitosamente.\n";
} else {
    echo "Error creando tabla: " . $cnn->error . "\n";
}
?>