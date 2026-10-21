<?php
$title = isset($title) && trim((string)$title) !== '' ? (string)$title : SITE_TITLE;
$escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$currentUser = getUser();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#6558e8">
  <meta name="description" content="Talkingspace is a community for thoughtful discussions, helpful answers, and shared ideas.">
  <title><?php echo $escape($title); ?> · Talkingspace</title>
  <link rel="stylesheet" href="<?php echo BASE_URI; ?>templates/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo BASE_URI; ?>templates/css/custom.css">
</head>
<body>
<a class="sr-only sr-only-focusable" href="#main-content">Skip to content</a>
<nav class="navbar navbar-default navbar-fixed-top" aria-label="Main navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#talkingspace-nav" aria-expanded="false" aria-controls="talkingspace-nav">
        <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo BASE_URI; ?>">
        <span class="brand-mark" aria-hidden="true">TS</span>
        <span class="brand-word">talking<span>space</span></span>
      </a>
    </div>
    <div class="collapse navbar-collapse" id="talkingspace-nav">
      <ul class="nav navbar-nav navbar-right">
        <li><a class="nav-link" href="<?php echo BASE_URI; ?>">Explore</a></li>
        <li><a class="nav-link" href="<?php echo BASE_URI; ?>topics.php">Discussions</a></li>
        <?php if ($currentUser): ?>
          <li><a class="nav-link" href="<?php echo BASE_URI; ?>create.php">Start a topic</a></li>
          <li>
            <form method="post" action="<?php echo BASE_URI; ?>logout.php" style="display:inline">
              <button class="btn btn-outline-secondary" type="submit" name="do_logout" value="1">Log out</button>
            </form>
          </li>
        <?php else: ?>
          <li><a class="nav-link" href="<?php echo BASE_URI; ?>login.php">Log in</a></li>
          <li><a class="btn btn-primary" href="<?php echo BASE_URI; ?>register.php">Join the community <span aria-hidden="true">↗</span></a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="app-shell">
  <div class="row layout-grid">
    <main id="main-content" class="col-md-8 main-col" tabindex="-1">
      <section class="block">
        <header class="page-heading">
          <span class="heading-kicker">A community for curious minds</span>
          <h1><?php echo $escape($title); ?></h1>
          <p>Ask better questions. Share what you know. Learn together.</p>
        </header>
        <div class="page-body">
          <?php displayMessage(); ?>