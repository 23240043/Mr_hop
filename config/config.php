<?php
/**
 * Configuración general del sistema
 */

// URL base del proyecto - AJUSTAR según tu entorno
define('BASE_URL', 'http://localhost/mr_hop/');

// Nombre del sistema
define('APP_NAME', 'Mr. Hop - Cerveza Artesanal');

// Ruta absoluta del proyecto
define('ROOT_PATH', dirname(__DIR__));

// Zona horaria
date_default_timezone_set('America/Mexico_City');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mostrar errores (desactivar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);
