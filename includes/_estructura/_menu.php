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
            <li>
                <a href="#menuClientes" data-bs-toggle="collapse"
                    class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-tie"></i> Clientes</span>
                    <i class="fas fa-chevron-down small"></i>
                </a>
                <div class="collapse <?= ($_GET['view'] ?? '') === 'clientes' ? 'show' : '' ?>" id="menuClientes">
                    <ul class="list-unstyled">
                        <li class="<?= ($_GET['view'] ?? '') === 'clientes' ? 'active' : '' ?>">
                            <a href="index.php?view=clientes" class="ps-5">Gestión de Clientes</a>
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