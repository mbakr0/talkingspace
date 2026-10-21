        </div>
      </section>
    </main>
    <aside class="col-md-4 sidebar" aria-label="Community sidebar">
      <section class="block">
        <?php if (isLoggedIn()): ?>
          <h2 class="sidebar-title"><span class="title-icon" aria-hidden="true">✦</span>Your space</h2>
          <div class="user-welcome">
            <span class="user-avatar-small" aria-hidden="true"><?php echo $escape(strtoupper(substr((string)(getUser()['username'] ?? 'U'), 0, 1))); ?></span>
            <div><strong>Welcome back, <?php echo $escape(getUser()['username'] ?? 'member'); ?></strong><span>Ready to join the conversation?</span></div>
          </div>
          <a class="btn btn-primary btn-block" href="<?php echo BASE_URI; ?>create.php">＋ Start a discussion</a>
        <?php else: ?>
          <h2 class="sidebar-title"><span class="title-icon" aria-hidden="true">✦</span>Join the conversation</h2>
          <p class="sidebar-subtitle">Sign in to reply, or create an account to share your own ideas.</p>
          <form class="auth-form" method="post" action="<?php echo BASE_URI; ?>login.php">
            <input type="hidden" name="csrf_token" value="<?php echo $escape(csrfToken()); ?>">
            <div class="form-group"><label for="sidebar-username">Username</label><input id="sidebar-username" name="username" type="text" class="form-control" placeholder="Your username" autocomplete="username" required></div>
            <div class="form-group"><label for="sidebar-password">Password</label><input id="sidebar-password" name="password" type="password" class="form-control" placeholder="Your password" autocomplete="current-password" required></div>
            <button class="btn btn-primary btn-block" type="submit" name="do_login" value="1">Log in <span aria-hidden="true">→</span></button>
          </form>
          <p class="auth-footnote">New around here? <a href="<?php echo BASE_URI; ?>register.php">Create your account</a></p>
        <?php endif; ?>
      </section>
      <section class="block">
        <h2 class="sidebar-title"><span class="title-icon" aria-hidden="true">▦</span>Browse topics</h2>
        <p class="sidebar-subtitle">Find a conversation that interests you.</p>
        <nav class="category-list" aria-label="Discussion categories">
          <a href="<?php echo BASE_URI; ?>topics.php" class="category-link <?php echo is_active(null); ?>"><span>All discussions</span><span class="category-count"><?php echo (int)($totalTopics ?? 0); ?></span></a>
          <?php foreach (getCategories() as $category): ?>
            <a href="<?php echo BASE_URI; ?>topics.php?category=<?php echo (int)$category['id']; ?>" class="category-link <?php echo is_active($category['id']); ?>"><span><?php echo $escape($category['name']); ?></span><span class="category-count">›</span></a>
          <?php endforeach; ?>
        </nav>
      </section>
      <section class="block">
        <h2 class="sidebar-title"><span class="title-icon" aria-hidden="true">◎</span>Community values</h2>
        <p class="sidebar-subtitle" style="margin-bottom:0">Stay curious, be constructive, and make room for different perspectives. The best discussions help everyone leave a little wiser.</p>
      </section>
    </aside>
  </div>
</div>
<footer class="site-footer">
  <div class="site-footer-inner">
    <span><strong>talkingspace</strong> — good questions bring people together.</span>
    <span>Built for thoughtful conversations · <a href="<?php echo BASE_URI; ?>topics.php">Explore discussions</a></span>
  </div>
</footer>
<script src="<?php echo BASE_URI; ?>templates/js/jquery-3.3.1.js"></script>
<script src="<?php echo BASE_URI; ?>templates/js/bootstrap.js"></script>
<script>
(function () {
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
      var button = form.querySelector('button[type="submit"],input[type="submit"]');
      if (!button || button.dataset.busy === '1') return;
      button.dataset.busy = '1';
      button.dataset.originalText = button.textContent || button.value || '';
      if (button.tagName === 'BUTTON') button.textContent = 'Please wait…';
      else button.value = 'Please wait…';
      window.setTimeout(function () {
        button.dataset.busy = '0';
        if (button.tagName === 'BUTTON') button.textContent = button.dataset.originalText;
        else button.value = button.dataset.originalText;
      }, 5000);
    });
  });
})();
</script>
</body>
</html>