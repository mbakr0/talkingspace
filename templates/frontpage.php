<?php include __DIR__ . '/includes/header.php'; ?>
<?php
$escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$searchQuery = trim((string)($_GET['q'] ?? ''));
?>
<section class="hero-panel">
  <span class="hero-eyebrow"><span class="hero-dot"></span> Your next great conversation starts here</span>
  <h2>Ideas grow when we share them.</h2>
  <p>A welcoming corner of the internet to ask questions, exchange knowledge, and connect with people who are curious about the same things you are.</p>
  <?php if (isLoggedIn()): ?>
    <a class="btn" href="<?php echo BASE_URI; ?>create.php">Start a discussion <span aria-hidden="true">↗</span></a>
  <?php else: ?>
    <a class="btn" href="<?php echo BASE_URI; ?>register.php">Find your community <span aria-hidden="true">↗</span></a>
  <?php endif; ?>
  <div class="hero-stats">
    <div class="hero-stat"><strong><?php echo number_format((int)$totalTopics); ?></strong><span>Discussions started</span></div>
    <div class="hero-stat"><strong><?php echo number_format((int)$totalCategories); ?></strong><span>Topics to explore</span></div>
    <div class="hero-stat"><strong><?php echo number_format((int)$totalUsers); ?></strong><span>Community members</span></div>
  </div>
</section>
<form class="search-form" method="get" action="<?php echo BASE_URI; ?>topics.php" role="search" aria-label="Search discussions">
  <input class="form-control" type="search" name="q" value="<?php echo $escape($searchQuery); ?>" placeholder="Search discussions, questions, and ideas…" aria-label="Search discussions">
  <button class="btn btn-primary" type="submit">Search <span aria-hidden="true">⌕</span></button>
</form>
<div class="section-title">
  <div><h3>Fresh conversations</h3><p>Catch up on what the community is talking about.</p></div>
  <a class="heading-meta" href="<?php echo BASE_URI; ?>topics.php">View all →</a>
</div>
<?php if (!empty($topics)): ?>
  <ul class="topic-list">
    <?php foreach ($topics as $item): ?>
      <?php
      $topicId = (int)($item['id'] ?? 0);
      $avatarPath = avatarUrl($item['avatar'] ?? '');
      $bodyText = trim(strip_tags((string)($item['body'] ?? '')));
      $excerpt = function_exists('mb_strimwidth') ? mb_strimwidth($bodyText, 0, 145, '…', 'UTF-8') : substr($bodyText, 0, 145);
      ?>
      <li class="topic-card">
        <img class="topic-avatar" src="<?php echo $escape($avatarPath); ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src='<?php echo BASE_URI; ?>images/avatars/default-avatar.svg';">
        <div>
          <h3><a href="<?php echo BASE_URI; ?>topic.php?id=<?php echo $topicId; ?>"><?php echo $escape($item['title'] ?? 'Untitled discussion'); ?></a></h3>
          <?php if ($excerpt !== ''): ?><p class="topic-excerpt"><?php echo $escape($excerpt); ?></p><?php endif; ?>
          <div class="topic-meta">
            <span>by <a href="<?php echo BASE_URI; ?>topics.php?user=<?php echo (int)($item['user_id'] ?? 0); ?>"><?php echo $escape($item['username'] ?? 'Community member'); ?></a></span>
            <span class="meta-dot"></span>
            <a href="<?php echo BASE_URI; ?>topics.php?category=<?php echo (int)($item['category_id'] ?? 0); ?>"><?php echo $escape($item['name'] ?? 'General'); ?></a>
            <span class="meta-dot"></span><time><?php echo $escape(formatDate($item['create_date'] ?? 'now')); ?></time>
          </div>
        </div>
        <span class="reply-pill" title="Replies"><?php echo (int)($item['reply_count'] ?? 0); ?> replies</span>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <div class="empty-state">
    <span class="empty-icon" aria-hidden="true">✦</span>
    <h3>No discussions yet</h3>
    <p>Every great community starts with one good question. Be the first to start a conversation.</p>
    <?php if (isLoggedIn()): ?><a class="btn btn-primary" href="<?php echo BASE_URI; ?>create.php">Create the first topic</a><?php else: ?><a class="btn btn-primary" href="<?php echo BASE_URI; ?>register.php">Join and get started</a><?php endif; ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>