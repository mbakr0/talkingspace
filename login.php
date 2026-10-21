<?php
require 'core/init.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_login'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        redirect('login.php', 'Your session expired. Please try again.', 'error');
    }

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

$topic = new Topic();
$template = new Template('templates/login.php');
$template->title = 'Welcome back';
$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories = $topic->getTotalCategories();
echo $template;
