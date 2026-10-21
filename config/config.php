<?php

// Set database credentials through environment variables on the server.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'talkingspace');

define('SITE_TITLE', 'Welcome to Talkingspace!');
$baseScheme = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
$baseHost = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
define('BASE_URI', rtrim(getenv('BASE_URI') ?: ($baseScheme . '://' . $baseHost . '/'), '/') . '/');
