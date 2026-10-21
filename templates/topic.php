<?php include __DIR__ . '/includes/header.php'; ?>
<?php $escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }; ?>
<?php if (empty($topic)): ?>
  <div class="empty-state"><span class="empty-icon" aria-hidden="true">?</span><h3>Discussion not found</h3><p>This topic may have been removed, or the link may be incorrect.</p><a class="btn btn-primary" href="<?php echo BASE_URI; ?>topics.php">Browse discussions</a></div>
<?php else: ?>
  <article>
    <header class="discussion-header">
      <div class="topic-meta" style="margin-bottom:12px">
        <a class="category-pill" href="<?php echo BASE_URI; ?>topics.php?category=<?php echo (int)($topic['category_id'] ?? 0); ?>"><?php echo $escape($topic['category_name'] ?? 'Discussion'); ?></a>
        <span class="meta-dot"></span><time><?php echo $escape(formatDate($topic['create_date'] ?? 'now')); ?></time>
      </div>
      <h1><?php echo $escape($topic['title'] ?? 'Discussion'); ?></h1>
      <div class="topic-meta"><span>Started by <a href="<?php echo BASE_URI; ?>topics.php?user=<?php echo (int)($topic['user_id'] ?? 0); ?>"><?php echo $escape($topic['username'] ?? 'Community member'); ?></a></span><span class="meta-dot"></span><span><?php echo count($replies ?? []); ?> replies</span></div>
    </header>
    <div class="discussion-body">
      <div class="discussion-author">
        <img src="<?php echo BASE_URI; ?>images/avatars/<?php echo rawurlencode(basename((string)($topic['avatar'] ?? 'noimage.png'))); ?>" alt="" onerror="this.onerror=null;this.src='<?php echo BASE_URI; ?>images/avatars/noimage.png';">
        <div><strong><?php echo $escape($topic['name'] ?? $topic['username'] ?? 'Community member'); ?></strong><span>@<?php echo $escape($topic['username'] ?? 'member'); ?> · Topic author</span></div>
      </div>
      <div class="discussion-content"><?php echo nl2br($escape($topic['body'] ?? '')); ?></div>
      <?php if (!empty($replies)): ?>
        <div class="reply-form" style="margin-top:28px">
          <div class="section-title"><div><h3>Replies</h3><p>Keep the conversation thoughtful and constructive.</p></div><span class="reply-pill"><?php echo count($replies); ?> replies</span></div>
          <div class="reply-list">
            <?php foreach ($replies as $reply): ?>
              <article class="reply-card">
                <div class="discussion-author">
                  <img src="<?php echo BASE_URI; ?>images/avatars/<?php echo rawurlencode(basename((string)($reply['avatar'] ?? 'noimage.png'))); ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src='<?php echo BASE_URI; ?>images/avatars/noimage.png';">
                  <div><strong><?php echo $escape($reply['name'] ?? $reply['username'] ?? 'Community member'); ?></strong><span>@<?php echo $escape($reply['username'] ?? 'member'); ?> · <?php echo $escape(formatDate($reply['create_date'] ?? 'now')); ?></span></div>
                </div>
                <div class="discussion-content"><?php echo nl2br($escape($reply['body'] ?? '')); ?></div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else: ?>
        <div class="empty-state" style="margin-top:24px;padding:24px 16px"><span class="empty-icon" aria-hidden="true">↩</span><h3>Start the conversation</h3><p>No replies yet. Share a helpful thought or ask a follow-up question.</p></div>
      <?php endif; ?>
      <section class="reply-form" id="reply">
        <?php if (isLoggedIn()): ?>
          <h3>Add your perspective</h3><p class="form-intro">Be kind, stay on topic, and help make the discussion useful.</p>
          <form method="post" action="<?php echo BASE_URI; ?>topic.php?id=<?php echo (int)$topic['id']; ?>">
            <div class="form-group"><label for="reply-body">Your reply</label><textarea id="reply-body" rows="5" name="body" class="form-control" placeholder="Write a thoughtful reply…" maxlength="10000" required></textarea></div>
            <div class="form-actions"><span class="heading-meta">Please keep it respectful.</span><button name="do_reply" value="1" type="submit" class="btn btn-primary">Post reply <span aria-hidden="true">→</span></button></div>
          </form>
        <?php else: ?>
          <h3>Have something to add?</h3><p class="form-intro">Log in to reply and take part in the conversation.</p><a class="btn btn-primary" href="<?php echo BASE_URI; ?>login.php">Log in to reply</a> <a class="btn btn-outline-secondary" href="<?php echo BASE_URI; ?>register.php">Create account</a>
        <?php endif; ?>
      </section>
    </div>
  </article>
<?php endif; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>