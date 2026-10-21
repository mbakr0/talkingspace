<?php
require 'core/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_logout'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        redirect('index.php', 'Your session expired. Please try again.', 'error');
    }
    $user = new User();
    if ($user->logout()) {
        redirect('index.php', 'You are logged out', 'success');
    }
}
redirect('index.php');
