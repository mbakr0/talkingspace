<?php
require 'core/init.php';

$topicModel = new Topic();
$topicId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$topicId = ($topicId && $topicId > 0) ? $topicId : 0;
$record = $topicModel->getTopic($topicId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['do_reply'])) {
    if (!isLoggedIn()) {
        redirect('login.php', 'Please log in before replying', 'error');
    }
    if (!$record) {
        redirect('topics.php', 'That discussion could not be found', 'error');
    }

    $body = trim((string)($_POST['body'] ?? ''));
    if ($body === '') {
        redirect('topic.php?id=' . $topicId . '#reply', 'Please write a reply before submitting', 'error');
    }
    if (mb_strlen($body) > 10000) {
        redirect('topic.php?id=' . $topicId . '#reply', 'Your reply is too long (maximum 10,000 characters)', 'error');
    }

    $posted = $topicModel->reply([
        'topic_id' => $topicId,
        'body' => $body,
        'user_id' => (int)getUser()['user_id']
    ]);
    redirect('topic.php?id=' . $topicId . '#reply', $posted ? 'Your reply has been posted' : 'Your reply could not be posted', $posted ? 'success' : 'error');
}

$template = new Template('templates/topic.php');
$template->topic = $record;
$template->replies = $record ? $topicModel->getReplies($topicId) : [];
$template->title = $record['title'] ?? 'Discussion not found';
$template->totalTopics = $topicModel->getTotalTopics();
$template->totalCategories = $topicModel->getTotalCategories();

echo $template;
