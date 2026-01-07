<?php
session_start();
require_once '../../cn.php';

if (!checkPermission('OWNER')) {
    die("Acceso denegado. Se requiere rol OWNER.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Test - Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm">
        <h1>Diccionario de Variables: Usuarios</h1>
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
                    <td>email</td>
                    <td>Email</td>
                    <td>Requerido, Único</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>String</td>
                    <td>Requerido en creación</td>
                </tr>
                <tr>
                    <td>role</td>
                    <td>Enum</td>
                    <td>OWNER, ADMINISTRADOR, COLABORADOR, MONITOR</td>
                </tr>
            </tbody>
        </table>

        <hr>

        <h3>Pruebas Manuales</h3>
        <div class="mb-3">
            <button class="btn btn-info" onclick="testList()">Probar SELECT (Listar)</button>
            <button class="btn btn-success" onclick="testCreate()">Probar INSERT (Simulado)</button>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5>Query Plantilla (INSERT):</h5>
                <code>INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)</code>
            </div>
            <div class="col-md-6">
                <h5>Query con Datos (Ejemplo):</h5>
                <code>INSERT INTO users (name, email, password, role) VALUES ('Test User', 'test@test.com', 'hashed_pass', 'COLABORADOR')</code>
            </div>
        </div>

        <hr>

        <h5>Respuesta JSON:</h5>
        <pre id="jsonResponse" class="bg-dark text-success p-3 rounded"
            style="min-height: 100px;">Esperando acción...</pre>
    </div>

    <script>
        function testList() {
            $.post('usuarios.php', { action: 'list' }, function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }

        function testCreate() {
            const mockData = {
                action: 'create',
                name: 'Test Manual ' + Date.now(),
                email: 'test' + Date.now() + '@example.com',
                password: 'password123',
                role: 'MONITOR'
            };
            $.post('usuarios.php', mockData, function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }
    </script>
</body>

</html>