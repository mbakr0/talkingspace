<?php include __DIR__ . '/includes/header.php'; ?>
<?php $escape = static function ($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }; ?>
<p class="form-intro">Have a question, an idea, or something useful to share? Give your discussion a clear title so others can find it.</p>
<form method="post" action="<?php echo BASE_URI; ?>create.php">
  <div class="form-group">
    <label for="topic-title">Discussion title</label>
    <input id="topic-title" type="text" name="title" class="form-control" placeholder="What would you like to discuss?" maxlength="200" required>
    <small class="heading-meta">Keep it specific and easy to understand.</small>
  </div>
  <div class="form-group">
    <label for="topic-category">Category</label>
    <select id="topic-category" class="form-control" name="category" required>
      <?php foreach (getCategories() as $category): ?>
        <option value="<?php echo (int)$category['id']; ?>"><?php echo $escape($category['name']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-group">
    <label for="topic-body">Your message</label>
    <textarea id="topic-body" rows="9" name="body" class="form-control" placeholder="Add context, details, and what you'd like the community to help with…" maxlength="10000" required></textarea>
  </div>
  <div class="form-actions"><span class="heading-meta">Be constructive. Be curious. Be kind.</span><button name="do_create" value="1" type="submit" class="btn btn-primary">Publish discussion <span aria-hidden="true">→</span></button></div>
</form>
<?php include __DIR__ . '/includes/footer.php'; ?>