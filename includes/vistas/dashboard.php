<div class="page-title-section animate__animated animate__fadeIn">
    <h1>Página principal</h1>
    <p class="text-muted">Bienvenido a tu centro de control de AppInteracto.</p>
</div>

<div class="row g-4 mb-5 animate__animated animate__fadeIn">
    <div class="col-12">
        <div class="card border-0 bg-light p-5 position-relative overflow-hidden"
            style="min-height: 280px; background: linear-gradient(90deg, #f8f9fa 0%, #e8f0fe 100%);">
            <div class="row align-items-center position-relative" style="z-index: 2;">
                <div class="col-md-7">
                    <h2 class="fw-bold mb-3">Empieza a gestionar tus servicios</h2>
                    <p class="text-muted mb-4 lead">Administra clientes, servidores y usuarios desde una interfaz
                        profesional inspirada en el análisis de datos de vanguardia.</p>
                    <div class="d-flex gap-2">
                        <a href="index.php?view=servidores" class="btn btn-primary px-4 py-2"
                            style="background-color: #1a73e8; border-color: #1a73e8;">
                            <i class="fas fa-server me-2"></i> Ver Servidores
                        </a>
                        <a href="index.php?view=clientes" class="btn btn-outline-primary px-4 py-2">
                            <i class="fas fa-users me-2"></i> Clientes
                        </a>
                    </div>
                </div>
            </div>
            <!-- Simple GA-style illustration (CSS/Symbol based) -->
            <div class="position-absolute d-none d-md-block"
                style="right: 50px; top: 50%; transform: translateY(-50%); opacity: 0.8;">
                <img src="https://www.gstatic.com/analytics-suite/header/suite/v2/ic_analytics.svg"
                    style="width: 200px; filter: grayscale(1) opacity(0.2);">
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm-hover transition">
            <div class="card-body p-4 text-center">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 50px; height: 50px; background-color: #1a73e8 !important;">
                    <i class="fas fa-plus fa-lg"></i>
                </div>
                <h5 class="fw-bold">Nuevos Servicios</h5>
                <p class="text-muted small">Registra nuevos servidores y software instalado de forma rápida.</p>
                <a href="index.php?view=servidores" class="btn btn-link px-0 text-decoration-none fw-bold">Comenzar <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm-hover transition">
            <div class="card-body p-4 text-center">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-address-book fa-lg"></i>
                </div>
                <h5 class="fw-bold">Directorio de Clientes</h5>
                <p class="text-muted small">Mantén el control total de tus clientes y sus contactos técnicos.</p>
                <a href="index.php?view=clientes" class="btn btn-link px-0 text-decoration-none fw-bold">Gestionar <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm-hover transition">
            <div class="card-body p-4 text-center">
                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                    style="width: 50px; height: 50px;">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
                <h5 class="fw-bold">Auditoría de Eventos</h5>
                <p class="text-muted small">Visualiza todas las acciones realizadas en la plataforma.</p>
                <a href="#" class="btn btn-link px-0 text-decoration-none fw-bold">Ver Logs <i
                        class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-sm-hover:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-2px);
    }

    .transition {
        transition: all 0.3s ease;
    }
</style>