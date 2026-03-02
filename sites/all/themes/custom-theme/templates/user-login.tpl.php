<?php
/**
 * @file
 * user-login.tpl.php
 * Custom template for Login form
 */
?>
<div class="auth-form-wrapper login-form">

  <?php if (!empty($form['#title'])): ?>
    <h2 class="form-title"><?php print $form['#title']; ?></h2>
  <?php endif; ?>

  <div class="form-fields">
    <!-- Username or Email -->
    <div class="form-item-wrapper">
      <?php print drupal_render($form['name']); ?>
    </div>

    <!-- Password -->
    <div class="form-item-wrapper">
      <?php print drupal_render($form['pass']); ?>
    </div>

    <!-- Remember me-->
    <?php if (!empty($form['persistent_login']) || !empty($form['remember_me'])): ?>
      <div class="form-item-wrapper checkbox-wrapper">
        <?php
          $remember_field = !empty($form['persistent_login']) ? $form['persistent_login'] : $form['remember_me'];
          print drupal_render($remember_field);
        ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Submit button -->
  <div class="form-actions-wrapper">
    <?php print drupal_render($form['actions']); ?>
  </div>

  <!-- Custom cross-links -->
  <div class="form-links auth-cross-links">
    <p class="register-link">
      <?php print t('No account?'); ?>
      <a href="<?php print url('user/register'); ?>"><?php print t('Register'); ?></a>
    </p>

    <p class="forgot-link">
      <a href="<?php print url('user/password'); ?>"><?php print t('Forgot password?'); ?></a>
    </p>
  </div>

  <!-- Render remaining form elements (tokens, hidden fields, etc.) -->
  <?php print drupal_render_children($form); ?>

</div>