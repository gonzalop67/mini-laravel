<?php 

// middlewares.php
$authMiddleware = function() {
    // session_start();
    if (!isset($_SESSION['authenticated'])) {
        header('Location: ' . BASE_URL . '/');
        exit(); // Corta el flujo si no está autenticado
    }
};

$adminMiddleware = function() {
    if ($_SESSION['role'] !== 'admin') {
        die("Acceso denegado: No eres administrador");
    }
};

?>