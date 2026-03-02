<?php
/**
 * @file
 * user-register-form.tpl.php
 * Custom template for Registration form
 */
?>
<div class="auth-form-wrapper register-form">

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
    <!-- Username -->
    <div class="form-item-wrapper">
      <?php print drupal_render($form['name']); ?>
    </div>

    <!-- Email -->
    <div class="form-item-wrapper">
      <?php print drupal_render($form['mail']); ?>
    </div>

    <!-- Password fields ( appears only if email verification is disabled in account settings) -->
    <?php if (!empty($form['pass'])): ?>
      <div class="form-item-wrapper password-parent">
        <?php print drupal_render($form['pass']['pass1']); ?>
      </div>
      <div class="form-item-wrapper password-confirm">
        <?php print drupal_render($form['pass']['pass2']); ?>
      </div>
    <?php endif; ?>

  </div>

 

  <!-- Render remaining form elements -->
  <?php print drupal_render_children($form); ?>

   <!-- Submit button -->
  <div class="form-actions-wrapper">
    <?php print drupal_render($form['actions']); ?>
  </div>

  <!-- Custom cross-links -->
  <div class="form-links auth-cross-links">
    <p class="login-link">
      <?php print t('Already have an account?'); ?>
      <a href="<?php print url('user/login'); ?>"><?php print t('Log in'); ?></a>
    </p>

    <p class="forgot-link">
      <a href="<?php print url('user/password'); ?>"><?php print t('Forgot password?'); ?></a>
    </p>
  </div>

</div>