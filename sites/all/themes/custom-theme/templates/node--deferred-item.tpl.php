<div class="di-wrapper">

  <div class="di-card">

    <!-- Header -->
    <div class="di-header">
      <div class="di-title-area">
        <h1><?php print $title; ?></h1>

        <div class="di-meta">
          <span class="di-status di-status-<?php print strtolower($learning_status); ?>">
            <?php print ucfirst(check_plain($learning_status)); ?>
          </span>

          <?php if (!empty($next_reminder_formatted)): ?>
            <span class="di-reminder">
              <span class="di-icon">📅</span>
              <?php print $next_reminder_formatted; ?>
            </span>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!empty($action_buttons)): ?>
        <div class="di-actions">
          <?php foreach ($action_buttons as $button): ?>
            <?php print $button; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Divider -->
    <div class="di-divider"></div>

    <!-- Main Body -->
    <div class="di-body">

      <!-- Left Column -->
      <div class="di-main">
        <div class="di-section">
          <div class="di-section-title">
            <span class="di-icon">🧠</span>
            What Did You Not Understand?
          </div>
          <div class="di-section-content">
            <?php print render($content['field_what_did_you_not_understan']); ?>
          </div>
        </div>

        <?php if (!empty($content['field_personal_note'])): ?>
          <div class="di-section">
            <div class="di-section-title">
              <span class="di-icon">📝</span>
              Personal Note
            </div>
            <div class="di-section-content">
              <?php print render($content['field_personal_note']); ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Right Column -->
      <div class="di-side">

        <?php if (!empty($content['field_reference_link'])): ?>
          <div class="di-side-box">
            <div class="di-side-title">
              🔗 Reference
            </div>
            <?php print render($content['field_reference_link']); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($content['field_documents'])): ?>
          <div class="di-side-box">
            <div class="di-side-title">
              📂 Documents
            </div>
            <?php print render($content['field_documents']); ?>
          </div>
        <?php endif; ?>

      </div>

    </div>

    <!-- Reflection History -->
    <?php
    // Fetch reflection logs directly from DB for non-fieldable entity
    $reflections = array();
    $result = db_select('reflection_log', 'r')
      ->fields('r')
      ->condition('deferred_item_id', $node->nid)
      ->orderBy('created', 'DESC')
      ->execute();

    foreach ($result as $record) {
      $reflections[] = $record;
    }
    ?>

    <?php if (!empty($reflections)): ?>
      <div class="di-divider"></div>
      <div class="di-reflections">
        <h2>Reflection History</h2>
        <?php foreach ($reflections as $r): ?>
          <div class="di-reflection-card">
            <div class="di-reflection-header">
              <span class="di-reflection-date"><?php print format_date($r->created, 'medium'); ?></span>
              <span class="di-reflection-confidence">
                Confidence: <?php print check_plain($r->confidence_rating); ?>
              </span>
            </div>
            <div class="di-reflection-note">
              <?php print check_plain($r->reflection_note); ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>

</div>