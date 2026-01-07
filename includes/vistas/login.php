<div class="login-card p-4 bg-white shadow-sm rounded">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Ingresar</h2>
        <p class="text-muted small">Bienvenido a AppInteracto</p>
    </div>

    <form id="formLogin">
        <div class="mb-3">
            <label for="email" class="form-label small fw-bold">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="admin@interacto.com">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label small fw-bold">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="btnLogin">
            <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
        </button>
    </form>
</div>

<style>
    .login-card {
        width: 400px;
    }
</style>

<script>
    $(document).ready(function () {
        $('#formLogin').on('submit', function (e) {
            e.preventDefault();

            const btn = $('#btnLogin');
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Cargando...');

            $.ajax({
                url: 'includes/endpoints/login.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Bienvenido!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'index.php?view=dashboard';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                        btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión');
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error crítico',
                        text: 'No se pudo conectar con el servidor.'
                    });
                    btn.prop('disabled', false).html('<i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión');
                }
            });
        });
    });
</script>