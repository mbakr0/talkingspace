<?php

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params(['lifetime' => 0, 'path' => '/', 'secure' => $isHttps, 'httponly' => true, 'samesite' => 'Lax']);
session_start();

require_once __DIR__ . '/../config/config.php';

require_once __DIR__ . '/../helpers/system_helper.php';
require_once __DIR__ . '/../helpers/format_helper.php';
require_once __DIR__ . '/../helpers/db_helper.php';

// __autoload() was removed in PHP 8; use the supported SPL autoloader.
spl_autoload_register(function ($className) {
    $className = basename(str_replace('\\', '/', $className));
    $file = __DIR__ . '/../libraries/' . $className . '.php';
    if (is_file($file)) require_once $file;
});

