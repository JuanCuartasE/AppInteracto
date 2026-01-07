<?php
// Script de prueba de base de datos
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>Prueba de Conexión</h3>";

if (!file_exists('.env')) {
    echo "<p style='color:red'>ERROR: Archivo .env no encontrado en la raíz.</p>";
} else {
    echo "<p style='color:green'>OK: Archivo .env encontrado.</p>";
}

require_once 'cn.php';

if (isset($cnn) && !$cnn->connect_error) {
    echo "<p style='color:green'>OK: Conexión a la base de datos establecida correctamente.</p>";

    $res = $cnn->query("SHOW TABLES");
    if ($res) {
        echo "<h4>Tablas encontradas:</h4><ul>";
        while ($row = $res->fetch_row()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color:red'>ERROR: No se pudo conectar a la base de datos.</p>";
}
?>