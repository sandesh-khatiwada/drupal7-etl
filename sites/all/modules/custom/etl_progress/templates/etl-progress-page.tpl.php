<div class="progress-container">
  <h1><?php print t('My Learning Progress'); ?></h1>

  <div class="chart-row">
    <div class="chart-card">
      <h2><?php print t('Status Breakdown'); ?></h2>
      <canvas id="statusPieChart"></canvas>
    </div>

    <div class="chart-card">
      <h2><?php print t('Items Learned Over Time'); ?></h2>
      <canvas id="timeLineChart"></canvas>
    </div>
  </div>

  <div class="progress-summary">
    <h2><?php print t('Overall Completion'); ?></h2>
    <div class="progress-bar-outer">
      <div class="progress-bar-inner" style="width:<?php print $chart_data['completion']; ?>%"></div>
      <span><?php print $chart_data['completion']; ?>%</span>
    </div>
  </div>
</div>