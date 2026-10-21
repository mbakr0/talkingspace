<?php require 'core/init.php'; ?>
<?php
$topic = new Topic();

if (!isLoggedIn()) {
    redirect('index.php', 'Please log in before creating a topic', 'error');
}

if (isset($_POST['do_create'])) {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        redirect('create.php', 'Your session expired. Please try again.', 'error');
    }
    $validate = new Validator();
    $data = [
        'title' => trim((string)($_POST['title'] ?? '')),
        'body' => trim((string)($_POST['body'] ?? '')),
        'category_id' => filter_var($_POST['category'] ?? null, FILTER_VALIDATE_INT),
        'user_id' => (int)getUser()['user_id']
    ];

    if (!$validate->isRequired(['title', 'body', 'category'])) {
        redirect('create.php', 'Please fill in all required fields', 'error');
    }
    if (!$data['category_id']) {
        redirect('create.php', 'Please select a valid category', 'error');
    }
    if (mb_strlen($data['title']) > 200 || mb_strlen($data['body']) > 10000) {
        redirect('create.php', 'Your topic is too long', 'error');
    }

    if ($topic->create($data)) {
        redirect('index.php', 'Your topic has been added', 'success');
    }
    redirect('create.php', 'Your topic could not be added', 'error');
}

$template = new Template('templates/create.php');
$template->totalTopics = $topic->getTotalTopics();
echo $template;
