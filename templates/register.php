<?php include __DIR__ . '/includes/header.php'; ?>
<p class="form-intro">Create your free account to start discussions, reply to other members, and be part of the community.</p>
<form class="auth-form" method="post" action="<?php echo BASE_URI; ?>register.php" enctype="multipart/form-data">
  <input type="hidden" name="csrf_token" value="<?php echo $escape(csrfToken()); ?>">
  <div class="form-group"><label for="register-name">Name</label><input id="register-name" type="text" name="name" class="form-control" placeholder="How should we address you?" autocomplete="name" maxlength="100" required></div>
  <div class="form-group"><label for="register-email">Email address</label><input id="register-email" type="email" name="email" class="form-control" placeholder="you@example.com" autocomplete="email" maxlength="254" required></div>
  <div class="form-group"><label for="register-username">Username</label><input id="register-username" type="text" name="username" class="form-control" placeholder="Choose a public username" autocomplete="username" maxlength="50" required></div>
  <div class="row">
    <div class="col-sm-6"><div class="form-group"><label for="register-password">Password</label><input id="register-password" type="password" name="password" class="form-control" placeholder="At least 8 characters" autocomplete="new-password" minlength="8" required></div></div>
    <div class="col-sm-6"><div class="form-group"><label for="register-password-confirm">Confirm password</label><input id="register-password-confirm" type="password" name="password2" class="form-control" placeholder="Repeat password" autocomplete="new-password" minlength="8" required></div></div>
  </div>
  <div class="form-group"><label for="register-avatar">Profile image <span class="heading-meta">(optional)</span></label><input id="register-avatar" type="file" name="avatar" class="form-control-file" accept="image/jpeg,image/png,image/gif"><small class="heading-meta">JPG, PNG, or GIF · up to 5 MB</small></div>
  <div class="form-group"><label for="register-about">A little about you <span class="heading-meta">(optional)</span></label><textarea id="register-about" rows="4" name="about" class="form-control" placeholder="What are you interested in?"></textarea></div>
  <button type="submit" name="register" value="1" class="btn btn-primary btn-block">Create my account <span aria-hidden="true">→</span></button>
  <p class="auth-footnote">Already a member? <a href="<?php echo BASE_URI; ?>login.php">Log in here</a>.</p>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>