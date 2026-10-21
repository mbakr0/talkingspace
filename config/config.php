<?php

// Set database credentials through environment variables on the server.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'talkingspace');

define('SITE_TITLE', 'Welcome to Talkingspace!');
define('BASE_URI', getenv('BASE_URI') ?: ('http://' . ($_SERVER['SERVER_NAME'] ?? 'localhost') . '/www/talkingspace/'));
