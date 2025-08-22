<?php
// Redirect all requests to Laravel's public directory
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If accessing root or index, serve Laravel
if ($uri === '/' || $uri === '/index.php') {
    require_once __DIR__.'/public/index.php';
    exit;
}

// For other files, check if they exist in public directory
$filePath = __DIR__ . '/public' . $uri;
if (file_exists($filePath)) {
    // Serve static files directly
    $mimeType = mime_content_type($filePath);
    header('Content-Type: ' . $mimeType);
    readfile($filePath);
    exit;
}

// Otherwise, let Laravel handle it
require_once __DIR__.'/public/index.php';
