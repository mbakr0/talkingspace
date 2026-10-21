<?php include __DIR__ . '/includes/header.php'; ?>
<?php
$escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); };
$searchQuery = trim((string)($_GET['q'] ?? ''));
?>
<form class="search-form" method="get" action="<?php echo BASE_URI; ?>topics.php" role="search" aria-label="Search discussions">
  <?php if (isset($_GET['category']) && ctype_digit((string)$_GET['category'])): ?><input type="hidden" name="category" value="<?php echo (int)$_GET['category']; ?>"><?php endif; ?>
  <?php if (isset($_GET['user']) && ctype_digit((string)$_GET['user'])): ?><input type="hidden" name="user" value="<?php echo (int)$_GET['user']; ?>"><?php endif; ?>
  <input class="form-control" type="search" name="q" value="<?php echo $escape($searchQuery); ?>" placeholder="Search discussions, questions, and ideas…" aria-label="Search discussions">
  <button class="btn btn-primary" type="submit">Search <span aria-hidden="true">⌕</span></button>
  <?php if ($searchQuery !== ''): ?><a class="btn btn-outline-secondary" href="<?php echo BASE_URI; ?>topics.php">Clear</a><?php endif; ?>
</form>
<div class="section-title">
  <div><h3><?php echo $searchQuery !== '' ? 'Search results' : 'Explore discussions'; ?></h3><p><?php echo $searchQuery !== '' ? 'Results matching “' . $escape($searchQuery) . '”.' : 'Ideas, questions, and answers from the community.'; ?></p></div>
  <?php if (isLoggedIn()): ?><a class="btn btn-primary" href="<?php echo BASE_URI; ?>create.php">＋ New topic</a><?php endif; ?>
</div>
<?php if (!empty($topics)): ?>
  <ul class="topic-list">
    <?php foreach ($topics as $item): ?>
      <?php
      $topicId = (int)($item['id'] ?? 0);
      $avatar = (string)($item['avatar'] ?? 'noimage.png');
      $avatarPath = BASE_URI . 'images/avatars/' . rawurlencode(basename($avatar));
      $bodyText = trim(strip_tags((string)($item['body'] ?? '')));
      $excerpt = function_exists('mb_strimwidth') ? mb_strimwidth($bodyText, 0, 145, '…', 'UTF-8') : substr($bodyText, 0, 145);
      ?>
      <li class="topic-card">
        <img class="topic-avatar" src="<?php echo $escape($avatarPath); ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src='<?php echo BASE_URI; ?>images/avatars/noimage.png';">
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
        <span class="reply-pill"><?php echo (int)replyCount($topicId); ?> replies</span>
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <div class="empty-state">
    <span class="empty-icon" aria-hidden="true">⌕</span>
    <h3><?php echo $searchQuery !== '' ? 'No matching discussions' : 'Nothing here just yet'; ?></h3>
    <p><?php echo $searchQuery !== '' ? 'Try a different search term, or clear your search to browse everything.' : 'Be the first to bring a new question or idea to this category.'; ?></p>
    <?php if ($searchQuery !== ''): ?><a class="btn btn-outline-secondary" href="<?php echo BASE_URI; ?>topics.php">Browse all discussions</a><?php elseif (isLoggedIn()): ?><a class="btn btn-primary" href="<?php echo BASE_URI; ?>create.php">Start a discussion</a><?php else: ?><a class="btn btn-primary" href="<?php echo BASE_URI; ?>register.php">Join the community</a><?php endif; ?>
  </div>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>