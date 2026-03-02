<?php
// page--user.tpl.php
// Simplified auth page:It contains no header, footer, sidebars.
?>
<div id="page-wrapper" class="auth-page-wrapper">

  <div id="main-wrapper" class="clearfix">
    <div id="content" class="column">
      <div class="section">

        <!-- Logo + Title row -->
        <div class="auth-brand-header">
          <?php if ($logo): ?>
            <a href="<?php print $front_page; ?>" 
               title="<?php print t('Home'); ?>" 
               rel="home" 
               id="logo" 
               class="brand-logo-link">
              <img src="<?php print $logo; ?>" 
                   alt="<?php print t('Home'); ?>" 
                   class="brand-logo" />
            </a>
          <?php endif; ?>

          <h1 class="brand-title">Explain This Later</h1>
        </div>

       
        <?php print $messages; ?>

        <?php print render($page['content']); ?> <!-- This renders the form -->

      </div>
    </div> <!-- /.section, /#content -->
  </div> <!-- /#main-wrapper -->

</div> <!-- /#page-wrapper -->