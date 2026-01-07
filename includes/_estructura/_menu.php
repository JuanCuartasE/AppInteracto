<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="assets/images/logo.png" alt="Logo">
        <span class="brand-name">Interacto</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li class="<?= ($_GET['view'] ?? '') === 'dashboard' ? 'active' : '' ?>">
                <a href="index.php?view=dashboard">
                    <i class="fas fa-home"></i>
                    <span>Página principal</span>
                </a>
            </li>

            <li class="<?= (isset($_GET['view']) && $_GET['view'] == 'clientes') ? 'active' : '' ?>">
                <a href="index.php?view=clientes">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>

            <li class="<?= (isset($_GET['view']) && strpos($_GET['view'], 'servidor') !== false) ? 'active' : '' ?>">
                <a href="index.php?view=servidores">
                    <i class="fas fa-server"></i>
                    <span>Servidores</span>
                </a>
            </li>

            <?php if (checkPermission('OWNER')): ?>
                <li class="<?= ($_GET['view'] ?? '') === 'usuarios' ? 'active' : '' ?>">
                    <a href="index.php?view=usuarios">
                        <i class="fas fa-user-shield"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="index.php?view=configuracion">
                        <i class="fas fa-cog"></i>
                        <span>Configurar</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>