document.addEventListener("DOMContentLoaded", function () {
  const labRoomPreview = document.getElementById("labRoomPreview");
  if (labRoomPreview) {
      labRoomPreview.textContent = "Test Preview"; // Example content
  }
});

// Sidebar Toggle Script
document.getElementById('sidebarToggle').addEventListener('click', function () {
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

document.getElementById('closeSidebar').addEventListener('click', function () {
  document.getElementById('sidebar').classList.toggle('-translate-x-full');
});

document.querySelector('.bg-blue-700').addEventListener('click', function () {
  generateReportPreview();
});

function openModal() {
  document.getElementById('pdfPreviewModal').classList.remove('hidden');
}

function closeModal() {
  document.getElementById('pdfPreviewModal').classList.add('hidden');
}

function updatePreview() {
  const labRoom = document.getElementById("labRoom").value;
  const startDate = document.getElementById("startDate").value;
  const endDate = document.getElementById("endDate").value;
  const problemDescription = document.getElementById("problemDescription").value;

  document.getElementById("labRoomPreview").textContent = labRoom || "Not specified";
  document.getElementById("dateRangePreview").textContent = `${startDate} to ${endDate}` || "Not specified";
  document.getElementById("problemDescriptionPreview").textContent = problemDescription || "Not specified";
}

// Update preview on input changes
["startDate", "endDate", "labRoom", "problemDescription"].forEach(id => {
  const el = document.getElementById(id);
  if (el) el.addEventListener("input", updatePreview);
});

function generateReportPreview() {
  const room = document.querySelector('#labRoom').value;
  const startDate = document.getElementById('startDate').value;
  const endDate = document.getElementById('endDate').value;
  const graphView = document.querySelector('#graphView').value;
  const problemCheckbox = document.querySelector('input[type="checkbox"]').checked;
  const problemDescription = document.getElementById('problemDescription').value;

  const canvas = document.getElementById("reportChart");
  const graphImage = canvas.toDataURL("image/png");

  const previewContent = `
    <div class="text-center mb-4">
      <img src="utm_logo.jpg" alt="UTM Logo" class="mx-auto" style="width: 80px;">
      <h2 class="text-xl font-bold">Lab Report</h2>
    </div>
    <div class="flex justify-center">
      <img src="${graphImage}" alt="Graph Preview" class="w-full max-w-2xl">
    </div>
    <div class="mt-4">
      <p><strong>Lab Room:</strong> ${room}</p>
      <p><strong>Date Range:</strong> ${startDate} to ${endDate}</p>
      <p><strong>Graph View:</strong> ${graphView}</p>
      <p><strong>Problem Description:</strong></p>
      <p>${problemCheckbox ? problemDescription : "No problems reported."}</p>
    </div>
  `;

  document.getElementById("pdfPreviewContent").innerHTML = previewContent;
  openModal();
}

// Download Report Button to generate PDF
document.getElementById("downloadPDFButton").addEventListener("click", function () {
  const element = document.getElementById("pdfPreviewContent");

  // Convert the preview content to a Blob (PDF)
  html2pdf()
  .from(element)
  .set({
    filename: 'Lab_Report.pdf', // Name of the downloaded file
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }, // Configure PDF format
  })
  .save(); // Directly saves the PDF to the user's computer

});

document.getElementById("downloadCSVButton").addEventListener("click", function () {
  const labRoom = document.getElementById("labRoom").value;

  // Sample data (replace with actual data if available)
  const data = [
    { temperature: 23, humidity: 60, datetime: "2024-11-01 10:00" },
    { temperature: 25, humidity: 65, datetime: "2024-11-01 11:00" },
    { temperature: 24, humidity: 63, datetime: "2024-11-01 12:00" },
  ];

  // Add CSV header row
  let csvContent = `${labRoom}\n\nTemperature,Humidity,Datetime\n`;

  // Add data rows
  data.forEach(row => {
    csvContent += `${row.temperature},${row.humidity},${row.datetime}\n`;
  });

  // Create a blob and a link to download the CSV
  const blob = new Blob([csvContent], { type: "text/csv" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "Lab_Report.csv";
  link.click();
});


// Chart.js Initialization
let reportChart;
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById('reportChart').getContext('2d');
  reportChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [],
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
          beginAtZero: true,
        },
      },
    },
  });
});

// Chart Data Views
const dataViews = {
  days: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    temperatureData: [20, 21, 19, 22, 24, 23, 25],
    humidityData: [55, 60, 58, 63, 61, 65, 66],
},
weeks: {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    temperatureData: [23, 25, 24, 22],
    humidityData: [62, 65, 63, 60],
},
months: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    temperatureData: [22, 19, 25, 24, 28, 26, 27, 30, 31, 28, 26, 24],
    humidityData: [60, 58, 63, 65, 62, 61, 64, 66, 65, 63, 62, 60],
},
};

// Dropdown Event Listener
document.querySelector("#graphView").addEventListener("change", function () {
  const selectedView = this.value.toLowerCase();
  const selectedData = dataViews[selectedView];

  if (selectedData) {
    reportChart.data.labels = selectedData.labels;
    reportChart.data.datasets[0].data = selectedData.temperatureData;
    reportChart.data.datasets[1].data = selectedData.humidityData;
    reportChart.update();
  }
});
