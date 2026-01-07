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
        <div class="sidebar-overlay d-lg-none"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
        </div>
        <header class="top-header d-flex justify-content-between align-items-center px-4">
            <div class="header-left d-flex align-items-center">
                <button id="sidebarToggle" class="btn text-white d-lg-none me-2 p-0">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>
            <div class="header-right d-flex align-items-center">
                <span class="text-white me-3">Bienvenido, <strong>
                        <?= $_SESSION['interacto_user_name'] ?>
                    </strong></span>
                <div class="dropdown">
                    <button class="btn btn-link text-white dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="includes/endpoints/logout.php" id="btnLogout">Cerrar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
    <?php endif; ?>