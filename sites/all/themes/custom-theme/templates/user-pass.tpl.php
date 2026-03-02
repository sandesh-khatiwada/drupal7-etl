<?php
/**
 * @file
 * user-pass.tpl.php
 * Custom template for Password Reset form
 */
?>
<div class="auth-form-wrapper password-reset-form">

  <?php if (!empty($form['#title'])): ?>
    <h2 class="form-title"><?php print $form['#title']; ?></h2>
  <?php endif; ?>

  <?php if (!empty($form['#description'])): ?>
    <div class="form-description">
      <?php
        $description = $form['#description'];
        print drupal_render($description);
      ?>
    </div>
  <?php endif; ?>

  <div class="form-fields">
    <!-- Name or Email field -->
    <div class="form-item-wrapper">
      <?php print drupal_render($form['name']); ?>
    </div>
  </div>

  <!-- Submit button -->
  <div class="form-actions-wrapper">
    <?php print drupal_render($form['actions']); ?>
  </div>

  <!-- Cross-links (back to login + optional register) -->
  <div class="form-links auth-cross-links">
    <p>
      <?php print t('Back to'); ?>
      <a href="<?php print url('user/login'); ?>"><?php print t('Log in'); ?></a>
      <?php print t('or'); ?>
      <a href="<?php print url('user/register'); ?>"><?php print t('Register'); ?></a>
    </p>
  </div>

  <!-- Render remaining elements -->
  <?php print drupal_render_children($form); ?>

</div>