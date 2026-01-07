<aside class="sidebar">
    <div class="sidebar-logo p-4 text-center">
        <h5 class="text-white mb-0">AppInteracto</h5>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($_GET['view'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <a href="index.php?view=dashboard"><i class="fas fa-th-large"></i> Dashboard</a>
            </li>

            <!-- Clientes -->
            <li class="nav-item">
                <a href="#menuClientes" data-bs-toggle="collapse" class="d-flex align-items-center">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                    <i class="fas fa-chevron-down ms-auto small"></i>
                </a>
                <div class="collapse <?= (isset($_GET['view']) && (strpos($_GET['view'], 'cliente') !== false)) ? 'show' : '' ?>"
                    id="menuClientes">
                    <ul class="nav flex-column ps-3">
                        <li class="nav-item">
                            <a href="index.php?view=clientes"
                                class="nav-link <?= (isset($_GET['view']) && $_GET['view'] == 'clientes') ? 'active' : '' ?>">Gestión
                                de Clientes</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Gestión de Servicios -->
            <li class="nav-item">
                <a href="#menuServicios" data-bs-toggle="collapse" class="d-flex align-items-center">
                    <i class="fas fa-server"></i>
                    <span>Gestión de Servicios</span>
                    <i class="fas fa-chevron-down ms-auto small"></i>
                </a>
                <div class="collapse <?= (isset($_GET['view']) && (strpos($_GET['view'], 'servidor') !== false)) ? 'show' : '' ?>"
                    id="menuServicios">
                    <ul class="nav flex-column ps-3">
                        <li class="nav-item">
                            <a href="index.php?view=servidores"
                                class="nav-link <?= (isset($_GET['view']) && $_GET['view'] == 'servidores') ? 'active' : '' ?>">Servidores</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Configuración -->
            <li>
                <a href="#menuConfig" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-cog"></i> Configuración</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <div class="collapse <?= ($_GET['view'] ?? '') === 'usuarios' ? 'show' : '' ?>" id="menuConfig">
                    <ul class="list-unstyled">
                        <?php if (checkPermission('OWNER')): ?>
                            <li class="<?= ($_GET['view'] ?? '') === 'usuarios' ? 'active' : '' ?>">
                                <a href="index.php?view=usuarios" class="ps-5">Gestión de Usuarios</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</aside>