<?php
/**
 * @file
 * Template for demo data table
 */
?>

<div class="demo-data-table-wrapper">

  <h2><?php print t('Demo Records'); ?></h2>

  <p>
    <?php print l(t('Add New Item'), 'demo-data/add'); ?>
  </p>

  <?php if (empty($rows)): ?>

    <p><?php print t('No records found.'); ?></p>

  <?php else: ?>

    <?php
      $table_rows = array();

      foreach ($rows as $row) {
        $table_rows[] = array(
          $row['id'],
          check_plain($row['title']),
          check_plain($row['description'] ?: '—'),
          $row['weight'],
          format_date($row['created'], 'short'),
          $row['operations'],
        );
      }
    ?>

    <?php print theme('table', array(
      'header' => $header,
      'rows' => $table_rows,
      'attributes' => array('class' => array('demo-data-table')),
      'empty' => t('No records available.'),
    )); ?>

  <?php endif; ?>

</div>
