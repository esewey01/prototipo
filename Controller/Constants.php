<?php
if (!defined('URL_VIEWS')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];

    // Ruta base del proyecto (sube dos niveles desde el archivo en ejecución)
    $base_path = dirname(dirname($_SERVER['SCRIPT_NAME'])); // /Prototipo
    $base_path = rtrim($base_path, '/'); // Elimina cualquier '/' al final

    define('URL_VIEWS', "{$protocol}://{$host}{$base_path}/Views/");
}

if (!defined('ADDRESS')) {
    // Ruta del sistema de archivos al directorio de fotos
    $root_path = dirname(dirname(__DIR__)); // Sube dos niveles desde el archivo actual
    define('ADDRESS', $root_path . '/Views/fotoproducto/');
}
