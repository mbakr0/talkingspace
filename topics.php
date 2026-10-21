<?php
require 'core/init.php';

$topic = new Topic();
$category = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT);
$userId = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);
$search = trim((string)($_GET['q'] ?? ''));
$template = new Template('templates/topics.php');

if ($category !== null && $category !== false && $category > 0) {
    $categoryData = $topic->getCategory($category);
    $template->topics = $topic->getByCategory($category, $search);
    $template->title = $categoryData ? 'In ' . $categoryData['name'] : 'Explore discussions';
} elseif ($userId !== null && $userId !== false && $userId > 0) {
    $template->topics = $topic->getByUser($userId, $search);
    $template->title = 'Member discussions';
} else {
    $template->topics = $topic->getAllTopics($search);
    $template->title = $search !== '' ? 'Search discussions' : 'Explore discussions';
}

$template->totalTopics = $topic->getTotalTopics();
$template->totalCategories = $topic->getTotalCategories();
echo $template;
