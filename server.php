<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
$path = __DIR__ . str_replace('/', DIRECTORY_SEPARATOR, $uri);
$publicPath = __DIR__ . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $uri);

$targetFile = null;
if (file_exists($path) && !is_dir($path)) {
    $targetFile = $path;
} elseif (file_exists($publicPath) && !is_dir($publicPath)) {
    $targetFile = $publicPath;
}

if ($uri !== '/' && $targetFile) {
    $mimes = [
        'css' => 'text/css',
        'js'  => 'application/javascript',
        'jpg' => 'image/jpeg',
        'jpeg'=> 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'webp'=> 'image/webp',
        'woff'=> 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf' => 'font/ttf',
        'ico' => 'image/x-icon',
    ];
    $ext = pathinfo($targetFile, PATHINFO_EXTENSION);
    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    readfile($targetFile);
    exit;
}

if ($uri !== '/' && (file_exists($path) || file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $uri)))) {
    return false;
}

require_once __DIR__.'/index.php';
