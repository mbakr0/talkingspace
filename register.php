<?php require 'core/init.php'; ?>
<?php
$topic = new Topic();
$user = new User();
$validate = new Validator();

if (isset($_POST['register'])) {
    $data = [
        'name' => trim((string)($_POST['name'] ?? '')),
        'email' => trim((string)($_POST['email'] ?? '')),
        'username' => trim((string)($_POST['username'] ?? '')),
        'password' => (string)($_POST['password'] ?? ''),
        'password2' => (string)($_POST['password2'] ?? ''),
        'about' => trim((string)($_POST['about'] ?? '')),
        'avatar' => 'noimage.png'
    ];

    if (!$validate->isRequired(['name','email','username','password','password2'])) {
        redirect('register.php', 'Please fill in all required fields', 'error');
        exit;
    }
    if (!$validate->isValidEmail($data['email'])) {
        redirect('register.php', 'Please use a valid email address', 'error');
        exit;
    }
    if (strlen($data['password']) < 8) {
        redirect('register.php', 'Password must be at least 8 characters', 'error');
        exit;
    }
    if (!$validate->passwordMatch($data['password'], $data['password2'])) {
        redirect('register.php', 'Your passwords did not match', 'error');
        exit;
    }

    if ($user->uploadAvatar()) {
        $data['avatar'] = $_FILES['avatar']['safe_name'];
    }

    if ($user->register($data)) {
        redirect('index.php', 'You are registered and can now log in', 'success');
        exit;
    }

    redirect('register.php', 'Username or email is already registered', 'error');
    exit;
}

$template = new Template('templates/register.php');
$template->totalTopics = $topic->getTotalTopics();
echo $template;
