<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <style>
        canvas#reportChart {
    height: 400px; /* Set a fixed height */
    max-height: 500px; /* Optional: Limit maximum height */
}

    </style>
</head>

<body class="bg-white min-h-screen flex">
    <!-- Main Content -->
    <div class="flex-grow p-8">
        <div class="w-full">
            <div class="relative bg-cover bg-center rounded-t-lg p-4 flex justify-between items-center"
                style="background-image: url('{{ asset('bg_image_report.jpg') }}'); background-size: cover; background-position: center; height: 150px;">
                <div class="absolute inset-0 bg-blue-900 opacity-50 rounded-t-lg"></div> <!-- Overlay -->
                <!-- Header Title -->
                <h1 class="text-white text-2xl font-semibold relative z-10">Reports</h1>
            </div>
        </div>

    
       

        <!-- Report Form Container -->
        <div class="bg-white shadow-lg rounded-lg p-6 max-w-3xl mx-auto">
            <form id="reportForm" action="{{ route('generate.report') }}">
                <!-- Lab Room and Graph View Selection -->
                <div class="flex flex-wrap md:flex-nowrap space-y-4 md:space-y-0 md:space-x-4 mb-4">
                    <div class="w-full md:w-1/2">
                        <label for="lab-room" class="block text-gray-700 font-medium">Choose Lab Room</label>
                        <select id="lab-room" name="lab_room_name"
                            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:border-blue-500">
                            <option disabled selected >Select Lab Room</option>
                            <option value="FESEM">FESEM</option>
                            <option value="FETEM">FETEM</option>
                            <option value="Preparation Room">Preparation Room</option>

                        </select>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label for="graphView" class="block text-gray-700 font-medium">Graph View</label>
                        <select id="graphView"
                            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:border-blue-500">
                            <option disabled selected>Select Graph View</option>
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                        </select>
                    </div>
                </div>

    

                <!-- Chart Canvas -->
                <div class="mt-6">
                    <canvas id="reportChart" class="w-full h-48 md:h-64"></canvas>
                </div>

                <!-- Date Range Selection -->
                <div class="flex space-x-4 mb-4">
                    <div class="w-1/2">
                        <label for="startDate" class="block text-gray-700 font-medium">Start Date</label>
                        <input type="date" id="startDate" name="start_date"
                            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:border-blue-500" 
                            required>
                    </div>
                    <div class="w-1/2">
                        <label for="endDate" class="block text-gray-700 font-medium">End Date</label>
                        <input type="date" id="endDate" name="end_date"
                            class="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:border-blue-500"
                            required>
                    </div>
                </div>

                <!-- Problem Description Section -->
                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" id="problemCheckbox" class="form-checkbox text-blue-500">
                        <span class="ml-2">Problem present?</span>
                    </label>
                    <textarea id="problemDescription" placeholder="Problem Description"
                        class="border border-gray-300 rounded-lg w-full mt-2 p-2"></textarea>
                </div>

                <div id="pdfPreviewModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
                    <div class="bg-white p-4 rounded-lg shadow-lg max-w-3xl w-full relative">
                        <button onclick="closePdfPreviewModal()" class="absolute top-2 right-2 text-gray-500 text-2xl">&times;</button>
                        <div id="pdfPreviewContent" class="preview-container" style="max-height: 80vh;">
                            <h2 class="text-lg font-semibold text-gray-700">Report Preview</h2>
                            <div id="labRoomPreview" class="text-gray-700 mt-2"></div>
                            <div id="dateRangePreview" class="text-gray-700 mt-2"></div>
                            <div id="problemDescriptionPreview" class="text-gray-700 mt-2"></div>
                        </div>
                        <button id="downloadPDFButton" class="mt-4 bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 block mx-auto">
                            Download Report
                        </button>
                    </div>
                </div>
            

                <!-- Action Buttons -->
                <button type="button" class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 w-full mb-4"onclick="showPdfPreviewModal()">
                Generate Report
            </button>
            
                
                    <button id="downloadCSVButton" type="button" 
                        class="bg-blue-700 text-white px-6 py-2 rounded-lg hover:bg-blue-600 w-full">
                        Download CSV
                    </button>
                </div>  
            </form>
        </div>
    </div>

    

    <script>

        let chart;
        // let selectedSensorId = 1; // Default value for sensorId

        // Function to fetch sensor data dynamically
        async function fetchSensorData(viewType = 'days', sensorId, startDate = null, endDate = null) {
            if (!sensorId) {
                console.error('sensorId is required');
                return { labels: [], temperature: [], humidity: [], limit_temp: [], limit_hum: [] };
    }
            const params = new URLSearchParams();
            params.append('viewType', viewType);
            params.append('sensorId', sensorId);
            if (startDate) params.append('startDate', startDate);
            if (endDate) params.append('endDate', endDate);

            try {
        const response = await fetch(`/sensor-data?${params.toString()}`);
        if (!response.ok) throw new Error(`Error: ${response.status}`);
        return await response.json();
    } catch (error) {
        console.error('Error fetching sensor data:', error);
        return { labels: [], temperature: [], humidity: [], limit_temp: [], limit_hum: [] };
    }
}


        // Function to update chart dynamically
        async function updateChart(viewType, sensorId, startDate = null, endDate = null) {
    const data = await fetchSensorData(viewType, sensorId, startDate, endDate);

    if (!data.labels || data.labels.length === 0) {
        console.error("No data available for the chart");
        if (chart) chart.destroy(); // Destroy the chart if it exists
        return; // Do not initialize or update the chart
    }

    if (chart) {
        chart.data.labels = data.labels;
        chart.data.datasets[0].data = data.temperature;
        chart.data.datasets[1].data = data.humidity;
        chart.data.datasets[2].data = data.limit_temp;
        chart.data.datasets[3].data = data.limit_hum;
        chart.update();
    } else {
        const ctx = document.getElementById('reportChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Temperature',
                        data: data.temperature,
                        borderColor: '#ff4d4f',
                        tension: 0.1,
                        fill: false,
                    },
                    {
                        label: 'Humidity',
                        data: data.humidity,
                        borderColor: '#722ed1',
                        tension: 0.1,
                        fill: false,
                    },
                    {
                        label: 'Temperature Limit',
                        data: data.limit_temp,
                        borderColor: '#ff4d4f',
                        borderDash: [5, 5],
                        borderWidth: 2,
                    },
                    {
                        label: 'Humidity Limit',
                        data: data.limit_hum,
                        borderColor: '#722ed1',
                        borderDash: [5, 5],
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            },
        });
    }
}


        // Event listener for lab-room selector
        document.getElementById('lab-room').addEventListener('change', async (e) => {
    const labRoom = e.target.value; // Get the selected lab room value
    console.log('Lab room changed:', labRoom);

    // Fetch the sensorId dynamically based on the selected lab room
    const response = await fetch(`/get-sensor-id?lab_room_name=${labRoom}`);
    const result = await response.json();

    if (response.ok && result.sensor_id) {
        selectedSensorId = result.sensor_id; // Set the selectedSensorId
        console.log('Sensor ID:', selectedSensorId);

        // Trigger chart update after sensor ID is set
        const viewType = document.getElementById('graphView').value || 'days';
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        updateChart(viewType, selectedSensorId, startDate, endDate);
    } else {
        console.error('Failed to fetch sensor ID for the selected lab room.');
    }
});



       // Download CSV
       document.getElementById("downloadCSVButton").addEventListener("click", function (event) {
    event.preventDefault();

    // Get input values
    const labRoom = document.getElementById("lab-room").value; // Lab room name
    const startDate = document.getElementById("startDate").value; // Start date
    const endDate = document.getElementById("endDate").value; // End date

    // Validate inputs
    if (!labRoom || !startDate || !endDate) {
        alert("Please select a lab room and specify the date range.");
        return;
    }

    // Construct the URL
    const url = new URL("{{ route('download.csv') }}");
    url.searchParams.append("lab_room_name", labRoom);
    url.searchParams.append("start_date", startDate);
    url.searchParams.append("end_date", endDate);

    // Navigate to the URL to download the CSV
    window.location.href = url.toString();
});



        // Initial Load: Fetch and display data for the last 7 days
        document.addEventListener('DOMContentLoaded', () => {
            updateChart('days');
        });

        // Event Listeners for viewType and date range
        document.getElementById('graphView').addEventListener('change', function(e) {
            const viewType = e.target.value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, selectedSensorId, startDate, endDate);
        });

        document.getElementById('startDate').addEventListener('change', () => {
            const viewType = document.getElementById('graphView').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, selectedSensorId, startDate, endDate);
        });

        document.getElementById('endDate').addEventListener('change', () => {
            const viewType = document.getElementById('graphView').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, selectedSensorId, startDate, endDate);
        });

        // Function to update report preview
    async function updatePreview() {
        const labRoom = document.getElementById("lab-room").value || null;
        const startDate = document.getElementById("startDate").value || null;
        const endDate = document.getElementById("endDate").value || null;
        const problemDescription = document.getElementById("problemDescription").value || 'No description provided.';

        try {
            const response = await fetch(`/generate-report?labRoom=${labRoom}&startDate=${startDate}&endDate=${endDate}&problemDescription=${problemDescription}`, {
                method: 'GET', // Use GET method
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            });

            const result = await response.json();
            if (result.success) {
                console.log('Preview data:', result.data);

                // Update the report preview dynamically
                const previewContent = `
                    <p><strong>Lab Room:</strong> ${labRoom}</p>
                    <p><strong>Date Range:</strong> ${startDate} to ${endDate}</p>
                    <p><strong>Max Temperature:</strong> ${result.data.max_temp} °C</p>
                    <p><strong>Max Humidity:</strong> ${result.data.max_hum} %</p>
                    <p><strong>Average Temperature:</strong> ${result.data.avg_temp} °C</p>
                    <p><strong>Average Humidity:</strong> ${result.data.avg_hum} %</p>
                    <p><strong>Problem Description:</strong> ${problemDescription}</p>
                `;

                document.getElementById('pdfPreviewContent').innerHTML = previewContent;
            } else {
                console.error('Error fetching preview data:', result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }



        // Generate Report
        function showPdfPreviewModal(event) {
            if (event) {
                event.preventDefault();
            }
    const labRoom = document.getElementById('lab-room').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const problemPresent = document.getElementById('problemCheckbox').checked;
    const problemDescription = problemPresent ? 
        document.getElementById('problemDescription').value : 
        'No description provided.';

    const chartCanvas = document.getElementById('reportChart');
    const chartImage = chartCanvas.toDataURL('image/png');

    if (!labRoom || !startDate || !endDate) {
        alert("Please select a lab room and specify the date range.");
        return;
    }


    // Construct the URL with parameters
    const url = new URL('/generate-report', window.location.origin);
    url.searchParams.append('lab_room_name', labRoom);
    url.searchParams.append('start_date', startDate);
    url.searchParams.append('end_date', endDate);
    url.searchParams.append('problemDescription', problemDescription);
    url.searchParams.append('chartImage', chartImage);

    console.log('Fetching data from URL:', url.toString());

    fetch(url, { method: 'GET' })
        .then(response => response.blob())
        .then(blob => {
            const fileURL = URL.createObjectURL(blob);
            const pdfPreviewContainer = document.getElementById('pdfPreviewContent');
            const downloadBtn = document.getElementById('downloadPDFButton');

            // Display the modal
            document.getElementById('pdfPreviewModal').classList.remove('hidden');

            // Display report details
            document.getElementById('labRoomPreview').innerText = `Lab Room: ${labRoom}`;
            document.getElementById('dateRangePreview').innerText = `Date Range: ${startDate} - ${endDate}`;
            document.getElementById('problemDescriptionPreview').innerText = `Problem Description: ${document.getElementById('problemDescription').value}`;

            // Use PDF.js to display the first page of the PDF in the modal
            pdfjsLib.getDocument(fileURL).promise.then(pdfDoc_ => {
                const pdfDoc = pdfDoc_;
                pdfDoc.getPage(1).then(function (page) {
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    const viewport = page.getViewport({ scale: 1 });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    page.render({ canvasContext: context, viewport: viewport }).promise.then(function () {
                        pdfPreviewContainer.innerHTML = '';
                        pdfPreviewContainer.appendChild(canvas);
                    });
                });
            });

            // Add event listener to the download button
            downloadBtn.onclick = function () {
                const link = document.createElement('a');
                link.href = fileURL;
                link.download = 'lab_report.pdf';
                link.click();
            };
        });
}

function closePdfPreviewModal() {
    document.getElementById('pdfPreviewModal').classList.add('hidden');
}


        feather.replace();
    </script>
</body>

</html>
