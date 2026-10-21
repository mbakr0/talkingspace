<?php
// Vercel PHP function entry point. Dispatch legacy root-level PHP pages safely.
$projectRoot = dirname(__DIR__);
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$route = $_GET['__route'] ?? basename($requestPath);

if ($route === '' || $route === '/' || $route === 'index.php') {
    $target = $projectRoot . '/index.php';
} else {
    $allowedPages = [
        'create.php',
        'login.php',
        'logout.php',
        'register.php',
        'topic.php',
        'topics.php',
    ];

    $page = basename((string) $route);
    if (!in_array($page, $allowedPages, true)) {
        http_response_code(404);
        echo 'Page not found';
        exit;
    }

    $target = $projectRoot . '/' . $page;
}

if (!is_file($target)) {
    http_response_code(404);
    echo 'Page not found';
    exit;
}

require $target;
