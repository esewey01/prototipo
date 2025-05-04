<?php
if (!defined('URL_VIEWS')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    define('URL_VIEWS', "{$protocol}://{$host}{$path}Views/");
}


if (!defined('ADDRESS')) {
    define('ADDRESS', $_SERVER['DOCUMENT_ROOT'] . '/Prototipo/Views/fotoproducto/');
}

