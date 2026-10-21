<?php
require 'core/init.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_login'])) {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        redirect('login.php', 'Enter both your username and password', 'error');
    }

    if ($user->login($username, $password)) {
        redirect('index.php', 'Welcome back! You are now logged in.', 'success');
    }

    redirect('login.php', 'We could not sign you in with those details', 'error');
}

redirect('index.php');
