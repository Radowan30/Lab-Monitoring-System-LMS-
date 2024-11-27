<x-app-layout>

    <!-- Main Content -->
    <main class="flex-1 lg:ml-64 min-h-screen">
        <!-- Mobile Header -->
        <header class="lg:hidden dashboard-room-header flex justify-between items-center"
            style="background-image: url('{{ asset('images/headers/Preproom_room_header.png') }}')">
            <div class="header-content w-full text-center">
                <h1 class="text-xl font-semibold">Preparation Lab</h1>
            </div>
            <button onclick="toggleSidebar()" class="header-content absolute right-4 p-2">
                <i data-feather="more-vertical"></i>
            </button>
        </header>

        <!-- Desktop Header -->

        <header class="hidden lg:block dashboard-room-header"
            style="background-image: url('{{ asset('images/headers/Preproom_room_header.png') }}')">
            <div class="header-content">
                <h1 class="text-xl font-semibold">Preparation Lab</h1>
            </div>
        </header>

        <!-- Main Content -->
        <div class="p-4">
            <div class="bg-white rounded-lg p-6 shadow-lg">
                <!-- Status Section -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span class="text-sm">Sensor Status: ON</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm">
                            Latest Temperature: <span class="font-semibold">20°C</span>
                        </div>
                    </div>
                </div>

                <!-- Graph -->
                <div class="h-64 mb-6">
                    <canvas id="temperatureChart"></canvas>
                </div>

                <!-- Controls -->
                <div class="space-y-4">
                    <div class="flex justify-center">
                        <select id="graphView"
                            class="w-1/3 md:w-1/5 md:text-center px-4 py-2 border rounded-lg bg-white shadow-sm">
                            <option value="days">By Days</option>
                            <option value="weeks">By Weeks</option>
                            <option value="months">By Months</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <span class="text-sm font-medium">Range</span>
                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <input type="date" id="startDate" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <span class="flex items-center">to</span>
                            <div class="flex-1">
                                <input type="date" id="endDate" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        let chart; //global chart variable

        async function fetchSensorData(viewType = 'days', startDate = null, endDate = null) {
            const params = new URLSearchParams();
            params.append('viewType', viewType);
            params.append('sensorId', 1);
            if (startDate) params.append('startDate', startDate);
            if (endDate) params.append('endDate', endDate);

            const response = await fetch(`/sensor-data?${params.toString()}`);
            return response.json();
        }

        // Function to update chart dynamically
        async function updateChart(viewType, startDate = null, endDate = null) {
            const data = await fetchSensorData(viewType, startDate, endDate);

            // If the chart already exists, update its data
            if (chart) {
                chart.data.labels = data.labels;
                chart.data.datasets[0].data = data.temperature;
                chart.data.datasets[1].data = data.humidity;
                chart.data.datasets[2].data = data.limit_temp;
                chart.data.datasets[3].data = data.limit_hum;

                chart.update(); // Apply the updates
            } else {
                // Create the chart for the first time
                const ctx = document.getElementById('temperatureChart').getContext('2d');
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
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
                                pointRadius: 0, // Turn off circles
                                pointHoverRadius: 0, // Ensure they don't appear on hover
                                tension: 0.1,
                                fill: false,
                            },
                            {
                                label: 'Humidity Limit',
                                data: data.limit_hum,
                                borderColor: '#722ed1',
                                borderDash: [5, 5],
                                borderWidth: 2,
                                pointRadius: 0, // Turn off circles
                                pointHoverRadius: 0, // Ensure they don't appear on hover
                                tension: 0.1,
                                fill: false,
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

        // Initial Load: Fetch and display data for the last 7 days
        document.addEventListener('DOMContentLoaded', () => {
            updateChart('days');
        });

        // Event Listeners for viewType and date range
        document.getElementById('graphView').addEventListener('change', function(e) {
            const viewType = e.target.value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, startDate, endDate);
        });

        document.getElementById('startDate').addEventListener('change', () => {
            const viewType = document.getElementById('graphView').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, startDate, endDate);
        });

        document.getElementById('endDate').addEventListener('change', () => {
            const viewType = document.getElementById('graphView').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            updateChart(viewType, startDate, endDate);
        });
        // Initialize Feather Icons
        feather.replace();
    </script>

</x-app-layout>
