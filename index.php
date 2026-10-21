<?php
require 'core/init.php';

$topic = new Topic();
$user = new User();
$searchQuery = trim((string)($_GET['q'] ?? ''));

$template = new Template('templates/frontpage.php');
$template->topics = $topic->getAllTopics($searchQuery);
$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories = $topic->getTotalCategories();
$template->totalUsers = $user->getTotalUsers();

echo $template;
