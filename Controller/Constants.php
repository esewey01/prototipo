<?php
// Determinar si estamos en localhost o producción
$isLocal = ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1');

// Definir URL base dinámica
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
$baseUrl = $protocol . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__DIR__));

// Definir constantes
if (!defined('URL_VIEWS')) {
    define('URL_VIEWS', rtrim($baseUrl, '/') . '/Views/');
}

if (!defined('ADDRESS')) {
    // Ruta física del servidor (independiente del ambiente)
    define('ADDRESS', dirname(__DIR__) . '/Views/fotoproducto/');
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $baseUrl);
}