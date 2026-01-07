<div class="login-container shadow-lg animate__animated animate__fadeIn">
    <div class="row g-0 h-100">
        <!-- Left Side: Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white p-5">
            <div class="login-form-wrapper w-100" style="max-width: 380px;">
                <div class="text-center mb-5">
                    <div class="login-logo-circle mb-4" style="width: auto; padding: 0 20px;">
                        <span class="fw-bold text-primary">Interacto</span>
                    </div>
                    <h2 class="fw-bold">Iniciar Sesión</h2>
                </div>

                <form id="formLogin">
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold text-muted">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-lg bg-light border-0" id="email"
                            name="email" required placeholder="Ingresa tu correo">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label small fw-bold text-muted">Contraseña</label>
                        <input type="password" class="form-control form-control-lg bg-light border-0" id="password"
                            name="password" required placeholder="Ingresa tu contraseña">
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" id="btnLogin"
                        style="border-radius: 12px;">
                        Ingresar
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Branding -->
        <div
            class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center login-brand-side p-5 text-white text-center">
            <div class="brand-content px-4">
                <h1 class="display-4 fw-bold mb-4">Gestor de Servicios</h1>
                <p class="lead opacity-75">Administra con eficiencia los servicios y servidores de tus clientes en un
                    solo lugar.</p>
            </div>

            <!-- Floating shapes for aesthetics -->
            <div class="shape-circle"></div>
            <div class="shape-triangle"></div>
            <div class="shape-square"></div>
        </div>
    </div>
</div>

<style>
    body.logged-out {
        background-color: #f3f6f9;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }

    .login-container {
        width: 1000px;
        height: 600px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    .login-logo-circle {
        width: auto;
        padding: 0 20px;
        height: 60px;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        font-size: 1.5rem;
    }

    .login-brand-side {
        background: linear-gradient(135deg, #5c7cff 0%, #3a5ceb 100%);
        position: relative;
        overflow: hidden;
    }

    .login-brand-side h1 {
        font-size: 2.5rem;
        z-index: 2;
        position: relative;
    }

    .login-brand-side p {
        z-index: 2;
        position: relative;
    }

    /* Decorative shapes */
    .shape-circle {
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .shape-triangle {
        position: absolute;
        bottom: 50px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.05);
        clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
        transform: rotate(25deg);
    }

    .shape-square {
        position: absolute;
        top: 40%;
        right: 10%;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        transform: rotate(-15deg);
    }

    @media (max-width: 992px) {
        .login-container {
            width: 95%;
            height: auto;
            max-width: 500px;
        }
    }

    .form-control-lg {
        padding: 0.8rem 1rem;
        font-size: 1rem;
        border-radius: 4px;
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
                        btn.prop('disabled', false).text('Ingresar');
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error crítico',
                        text: 'No se pudo conectar con el servidor.'
                    });
                    btn.prop('disabled', false).text('Ingresar');
                }
            });
        });
    });
</script>