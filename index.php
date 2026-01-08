<?php
session_start();
require_once 'cn.php';

// Control de sesión
$is_logged_in = isset($_SESSION['interacto_user_id']);
$view = $_GET['view'] ?? ($is_logged_in ? 'dashboard' : 'login');

// Si no está logueado y no es el login, redirigir
if (!$is_logged_in && $view !== 'login') {
    header('Location: index.php?view=login');
    exit;
}

// Si está logueado y es el login, redirigir al dashboard
if ($is_logged_in && $view === 'login') {
    header('Location: index.php?view=dashboard');
    exit;
}

// Cargar estructura
include 'includes/_estructura/_header.php';

if ($is_logged_in) {
    include 'includes/_estructura/_menu.php';
    echo '<div class="sidebar-overlay"></div>';
    echo '<main class="content-wrapper">';
}

// Renderizar vista
$view_path = "includes/vistas/{$view}.php";
if (file_exists($view_path)) {
    include $view_path;
} else {
    echo "<h1>404 - Vista no encontrada</h1>";
}

if ($is_logged_in) {
    echo '</main>';
}

include 'includes/_estructura/_footer.php';
?>