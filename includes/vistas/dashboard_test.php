<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Test - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm">
        <h1>Pruebas: Dashboard Activity</h1>
        <p>Este test verifica la carga de logs de actividad.</p>

        <button class="btn btn-primary" onclick="testLogs()">Obtener Logs (AJAX)</button>

        <hr>
        <h5>Respuesta JSON:</h5>
        <pre id="jsonResponse" class="bg-dark text-success p-3 rounded">Esperando...</pre>
    </div>

    <script>
        function testLogs() {
            $.post('get_logs.php', function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }
    </script>
</body>

</html>