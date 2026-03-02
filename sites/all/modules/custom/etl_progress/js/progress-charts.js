(function ($) {
  Drupal.behaviors.etlProgressCharts = {
    attach: function (context, settings) {

      let data = settings.etl_progress.chart_data;
      if (!data) {
        console.warn('No chart data available.');
        return;
      }

      // Status Pie Chart
      let ctxPie = document.getElementById('statusPieChart');
      if (ctxPie) {
        new Chart(ctxPie, {
          type: 'pie',
          data: {
            labels: data.status.labels,
            datasets: [{
              data: data.status.data,
              backgroundColor: data.status.colors,
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'bottom' },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    let label = context.label || '';
                    let value = context.raw;
                    let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    let percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                    return `${label}: ${value} (${percent}%)`;
                  }
                }
              }
            }
          }
        });
      }

      // Time Line Chart
      let ctxLine = document.getElementById('timeLineChart');
      if (ctxLine) {
        new Chart(ctxLine, {
          type: 'line',
          data: {
            labels: data.time.labels,
            datasets: [{
              label: 'Learned Items',
              data: data.time.data,
              borderColor: '#27ae60',
              backgroundColor: 'rgba(39, 174, 96, 0.2)',
              fill: true,
              tension: 0.3
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: { beginAtZero: true }
            },
            plugins: {
              legend: { display: true, position: 'top' }
            }
          }
        });
      }
    }
  };
})(jQuery);