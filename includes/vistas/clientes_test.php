<?php
session_start();
require_once '../../cn.php';

if (!isset($_SESSION['interacto_user_id'])) {
    die("Acceso denegado. Inicie sesión.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Test - Gestión de Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm">
        <h1>Diccionario de Variables: Clientes</h1>
        <table class="table table-bordered small">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Validación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>name</td>
                    <td>String</td>
                    <td>Requerido</td>
                </tr>
                <tr>
                    <td>type</td>
                    <td>Enum</td>
                    <td>Cliente, Empresa</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>Email</td>
                    <td>Opcional</td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td>String</td>
                    <td>Opcional</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <h3>Pruebas Manuales</h3>
        <div class="mb-3">
            <button class="btn btn-info" onclick="testList()">Probar SELECT (Listar)</button>
            <button class="btn btn-success" onclick="testCreate()">Probar INSERT (Simulado)</button>
        </div>

        <h5>Respuesta JSON:</h5>
        <pre id="jsonResponse" class="bg-dark text-success p-3 rounded"
            style="min-height: 100px;">Esperando acción...</pre>
    </div>

    <script>
        function testList() {
            $.post('clientes.php', { action: 'list' }, function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }

        function testCreate() {
            const mockData = {
                action: 'create',
                name: 'Cliente de Prueba ' + Date.now(),
                type: 'Empresa',
                email: 'test' + Date.now() + '@empresa.com',
                phone: '123456789'
            };
            $.post('clientes.php', mockData, function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }
    </script>
</body>

</html>