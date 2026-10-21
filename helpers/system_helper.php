<?php

function redirect($page = false, $message = null, $message_type = null)
{
    $location = is_string($page) ? $page : $_SERVER['SCRIPT_NAME'];
    if ($message !== null) $_SESSION['message'] = $message;
    if ($message_type !== null) $_SESSION['message_type'] = $message_type;
    header('Location: ' . $location);
    exit;
}

function displayMessage()
{
    if (empty($_SESSION['message'])) return;
    $message = htmlspecialchars((string)$_SESSION['message'], ENT_QUOTES, 'UTF-8');
    $type = ($_SESSION['message_type'] ?? '') === 'error' ? 'danger' : 'success';
    echo "<div class='alert alert-{$type}'>" . $message . '</div>';
    unset($_SESSION['message'], $_SESSION['message_type']);
}

function isLoggedIn()
{
    return !empty($_SESSION['is_logged_in']) && !empty($_SESSION['user_id']);
}

function getUser()
{
    if (!isLoggedIn()) return null;
    return [
        'user_id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'] ?? '',
        'username' => $_SESSION['username'] ?? ''
    ];
}


/** Generate a per-session token for state-changing forms. */
function csrfToken()
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Validate a submitted CSRF token using a timing-safe comparison. */
function verifyCsrfToken($token)
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && is_string($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}
