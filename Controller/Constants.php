<?php
if (!defined('URL_VIEWS')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];

    // Detecta la ruta base del proyecto, sin importar en qué carpeta esté el archivo
    $script_dir = dirname($_SERVER['SCRIPT_NAME']); // por ejemplo: /Prototipo/Controller
    $base_path = str_replace('/Controller', '', $script_dir); // elimina "/Controller"
    $base_path = rtrim($base_path, '/'); // elimina '/' final si existe

    define('URL_VIEWS', "{$protocol}://{$host}{$base_path}/Views/");
}

if (!defined('ADDRESS')) {
    // Detecta ruta física a la carpeta Views/fotoproducto
    $project_root = str_replace('\\', '/', realpath(__DIR__ . '/..')); // sube a raíz del proyecto
    define('ADDRESS', $project_root . '/Views/fotoproducto/');
}
