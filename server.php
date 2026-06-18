<?php
/**
 * PHP Built-in Server Router
 * Usage: php -S localhost:8000 -t public/ server.php
 * Wasmer: wasmer run .  (uses wasmer.toml config)
 */

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uri = urldecode(parse_url($uri, PHP_URL_PATH) ?: '/');
$docRoot = $_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/public';

if ($uri !== '/' && file_exists($docRoot . $uri)) {
    return false;
}

require $docRoot . '/index.php';
