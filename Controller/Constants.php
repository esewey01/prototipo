<?php
if (!defined('URL_VIEWS')) {
    // Usar HTTP en local, HTTPS en producción
    $protocol = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') ? 'http' : 'https';
    $host = $_SERVER['HTTP_HOST'];

    $script_dir = dirname($_SERVER['SCRIPT_NAME']);
    $base_path = str_replace('/Controller', '', $script_dir);
    $base_path = rtrim($base_path, '/');

    define('URL_VIEWS', "{$protocol}://{$host}{$base_path}/Views/");
    define('URL_PUBLIC', "{$protocol}://{$host}{$base_path}/public/");
}
if (!defined('ADDRESS')) {
    // Detecta ruta física a la carpeta Views/fotoproducto
    $project_root = str_replace('\\', '/', realpath(__DIR__ . '/..')); // sube a raíz del proyecto
    define('ADDRESS', $project_root . '/Views/fotoproducto/');
}
