<?php

// Punto de entrada de la aplicación mini-Laravel

// Archivo de constantes
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/helpers.php';

// Autoloader
require_once __DIR__ . '/../autoload.php';
// Incluir rutas
require_once __DIR__ . '/../routes/web.php';
