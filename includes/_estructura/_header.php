<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AppInteracto</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Favicon fix -->
    <link rel="icon" href="data:,">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="<?= isset($_SESSION['interacto_user_id']) ? 'logged-in' : 'logged-out' ?>">

    <?php if (isset($_SESSION['interacto_user_id'])): ?>
        <header class="top-header">
            <div class="header-left d-flex align-items-center">
                <button class="btn btn-link text-muted me-3 d-lg-none" id="sidebarToggle">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <div class="nav-context d-none d-sm-flex">
                    <a href="index.php?view=dashboard">Interacto</a>
                    <i class="fas fa-chevron-right"></i>
                    <span class="fw-bold text-dark"><?= ucfirst($_GET['view'] ?? 'Dashboard') ?></span>
                </div>
            </div>

            <div class="header-search">
                <div class="search-input-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar en la aplicación...">
                </div>
            </div>

            <div class="header-right d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-muted dropdown-toggle d-flex align-items-center text-decoration-none"
                        type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar-header me-2">
                            <?= substr($_SESSION['interacto_user_name'] ?? 'U', 0, 1) ?>
                        </div>
                        <span
                            class="small fw-medium d-none d-md-inline text-muted"><?= $_SESSION['interacto_user_name'] ?? 'Usuario' ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 8px;">
                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-circle me-2"></i> Mi Perfil</a>
                        </li>
                        <li><a class="dropdown-item py-2" href="#"><i class="fas fa-cog me-2"></i> Preferencias</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-2 text-danger" href="includes/endpoints/logout.php"><i
                                    class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <style>
            .user-avatar-header {
                width: 32px;
                height: 32px;
                background-color: #1a73e8;
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.8rem;
                font-weight: 500;
            }
        </style>
    <?php endif; ?>