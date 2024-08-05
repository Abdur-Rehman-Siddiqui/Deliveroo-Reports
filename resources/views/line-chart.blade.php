!DOCTYPE html>
<html lang="en">
<head>
  <title>Line Chart Example</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div style="width: 80%; margin: auto;">
  <canvas id="lineChart"></canvas>
</div>

<script>
  var ctx = document.getElementById('lineChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($data['labels']),
      datasets: [{
        label: 'Data',
        data: @json($data['data']),
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1,
        fill: false
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
</body>
</html>
