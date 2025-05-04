<?php
if (!defined('URL_VIEWS')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/Views/';
    define('URL_VIEWS', "{$protocol}://{$host}{$path}");
}

if (!defined('ADDRESS')) {
    define('ADDRESS', $_SERVER['DOCUMENT_ROOT'] . '/Prototipo/Views/fotoproducto/');
}

