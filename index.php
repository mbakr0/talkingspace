<?php require 'core/init.php';

$topic = new Topic();
$user = new User();

$template = new Template('templates/frontpage.php');
$template->topics = $topic->getAllTopics();
$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories = $topic->getTotalCategories();
$template->totalUsers = $user->getTotalUsers();

echo $template;
