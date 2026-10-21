<?php include __DIR__ . '/includes/header.php'; ?>
<p class="form-intro">Good to see you again. Sign in to pick up the conversation where you left off.</p>
<form class="auth-form" method="post" action="<?php echo BASE_URI; ?>login.php">
  <input type="hidden" name="csrf_token" value="<?php echo $escape(csrfToken()); ?>">
  <div class="form-group">
    <label for="login-username">Username</label>
    <input id="login-username" type="text" name="username" class="form-control" placeholder="Your username" autocomplete="username" required>
  </div>
  <div class="form-group">
    <label for="login-password">Password</label>
    <input id="login-password" type="password" name="password" class="form-control" placeholder="Your password" autocomplete="current-password" required>
  </div>
  <button class="btn btn-primary btn-block" type="submit" name="do_login" value="1">Log in <span aria-hidden="true">→</span></button>
  <p class="auth-footnote">New to Talkingspace? <a href="<?php echo BASE_URI; ?>register.php">Create an account</a>.</p>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>