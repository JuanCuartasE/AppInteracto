<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Test - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light p-5">
    <div class="container bg-white p-4 shadow-sm">
        <h1>Pruebas: Login Endpoint</h1>
        <p>Este test prueba el endpoint de login con credenciales simuladas.</p>

        <button class="btn btn-primary" onclick="testLogin('admin@interacto.com', 'root1234')">Probar Login
            Exitoso</button>
        <button class="btn btn-danger" onclick="testLogin('wrong@test.com', 'wrong')">Probar Login Fallido</button>
        <button class="btn btn-warning" onclick="testLogout()">Probar Logout</button>

        <hr>
        <h5>Respuesta JSON:</h5>
        <pre id="jsonResponse" class="bg-dark text-success p-3 rounded">Esperando...</pre>
    </div>

    <script>
        function testLogin(email, pass) {
            $.post('login.php', { email: email, password: pass }, function (res) {
                $('#jsonResponse').text(JSON.stringify(res, null, 4));
            }, 'json');
        }
        function testLogout() {
            window.location.href = 'logout.php';
        }
    </script>
</body>

</html>