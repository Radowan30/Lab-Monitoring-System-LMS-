// Sidebar Toggle Script
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

document.getElementById('closeSidebar').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

  
  // Chart.js Initialization
  const ctx = document.getElementById('reportChart').getContext('2d');
  const reportChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [], // Empty initially, will be filled based on selected view
      datasets: [
        {
          label: 'Temperature (°C)',
          data: [],
          borderColor: 'rgba(59, 130, 246, 1)',
          backgroundColor: 'rgba(59, 130, 246, 0.2)',
        },
        {
          label: 'Humidity (%)',
          data: [],
          borderColor: 'rgba(34, 197, 94, 1)',
          backgroundColor: 'rgba(34, 197, 94, 0.2)',
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
  
  // Define data for different views
  const dataViews = {
    days: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      temperatureData: [20, 21, 19, 22, 24, 23, 25],
      humidityData: [55, 60, 58, 63, 61, 65, 66]
    },
    weeks: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      temperatureData: [23, 25, 24, 22],
      humidityData: [62, 65, 63, 60]
    },
    months: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      temperatureData: [22, 19, 25, 24, 28, 26, 27, 30, 31, 28, 26, 24],
      humidityData: [60, 58, 63, 65, 62, 61, 64, 66, 65, 63, 62, 60]
    }
  };
  
  // Dropdown change event listener
  document.querySelector("select[data-chart]").addEventListener("change", function() {
    const selectedView = this.value.toLowerCase();
    const selectedData = dataViews[selectedView];
  
    if (selectedData) {
      reportChart.data.labels = selectedData.labels;
      reportChart.data.datasets[0].data = selectedData.temperatureData;
      reportChart.data.datasets[1].data = selectedData.humidityData;
      reportChart.update();
    }
  });
  