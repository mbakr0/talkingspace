<?php require ('core/init.php'); ?>

<?php
//Get Template

$topic = new Topic();
$user = new User();

$template = new Template('templates/frontpage.php');

$template->topics = $topic->getAllTopics();
$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories= $topic->getTotalCategories();
$template->totalUsers = $user->getTotalUsers();

echo $template;

echo "<pre>".print_r($template->topics,true)."</pre>";
//echo "<pre>".print_r($GLOBALS,true)."</pre>";

