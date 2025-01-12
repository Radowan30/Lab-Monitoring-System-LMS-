<x-app-layout>
    <!-- Main Content -->
    <main class="flex-1 lg:ml-64 min-h-screen">
        <!-- Mobile Header -->
        <header class="lg:hidden dashboard-header flex justify-between items-center">
            <div class="w-full text-center">
                <h1 class="text-xl font-semibold">Dashboard</h1>
            </div>
            <button onclick="toggleSidebar()" class="absolute right-4 p-2">
                <i data-feather="more-vertical"></i>
            </button>
        </header>

        <!-- Desktop Header -->
        <header class="hidden lg:block dashboard-header">
            <h1 class="text-xl font-semibold">Dashboard</h1>
        </header>

        <!-- Dashboard Content -->
        <div class="p-4">
            <!-- Welcome Section -->
            <div class="mb-4">
                <h2 class="text-lg">Hello Lab Technician</h2>
                <p class="text-sm text-gray-600">View labs...</p>
            </div>

            <!-- Sensor Status Card -->
            <div class="bg-white rounded-lg pb-12 px-5 pt-5 shadow mb-6 md:mx-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <span>Sensors Status: ON</span>
                    </div>
                    <div onclick="showNotificationModal()" id="notificationIcon" class="cursor-pointer text-gray-500">
                        <i data-feather="alert-circle"></i>
                    </div>
                </div>


                <!-- Lab Room Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-5 mx-5">
                    <!-- Preparation Lab -->
                    <a href="{{ route('lab_rooms.prep-lab') }}"
                        class="transition ease-in-out delay-100 hover:-translate-y-1 hover:scale-105 relative group drop-shadow-md text-center">
                        <div class="rounded-lg overflow-hidden h-full">
                            <img src={{ asset('images/dashboard/Preproom_room.png') }} alt="Preparation Lab"
                                class="w-full h-full object-fill">
                            <div
                                class="absolute bottom-0 left-0 right-0 bg-white/60 backdrop-blur-sm md:py-4 flex justify-center items-center rounded-b-lg md:h-30">
                                <div
                                    class="text-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent text-center">
                                    <div class="text-lg font-bold"><span id="Sensor1Temp"></span>°C</div>
                                    <div class="text-lg font-bold"><span id="Sensor1Humi"></span> RH</div>
                                </div>
                            </div>
                        </div>
                        <span class="font-bold">Preparation Lab</span>
                    </a>

                    <!-- FETEM Room -->
                    <a href="{{ route('lab_rooms.fetem-room') }}"
                        class="transition ease-in-out delay-100 hover:-translate-y-1 hover:scale-105 relative group drop-shadow-md text-center">
                        <div class="rounded-lg overflow-hidden h-full">
                            <img src={{ asset('images/dashboard/FETEM_room.png') }} alt="FETEM Room"
                                class="w-full h-full object-cover">
                            <div class="absolute bottom-0 left-0 right-0 bg-white/70 backdrop-blur-sm p-3 rounded-b-lg">
                                <div class="flex justify-between items-center h-full">
                                    <div class="flex-1 flex justify-center items-center">
                                        <div
                                            class="text-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                                            <div class="text-lg font-bold">20°C</div>
                                            <div class="text-lg font-bold">60 RH</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex justify-center items-center">
                                        <div
                                            class="bg-transparent rounded-lg p-1 text-center border-2 border-blue-500 w-20">
                                            <span>❄️</span>
                                            <div
                                                class="text-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                                                <div class="text-xs font-bold">20°C</div>
                                                <div class="text-xs font-bold">60 RH</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="font-bold">FETEM Room</span>
                    </a>

                    <!-- FESEM Room -->
                    <a href="{{ route('lab_rooms.fesem-room') }}"
                        class="transition ease-in-out delay-100 hover:-translate-y-1 hover:scale-105 relative group drop-shadow-md text-center">
                        <div class="rounded-lg overflow-hidden h-full">
                            <img src={{ asset('images/dashboard/FESEM_room.png') }} alt="FESEM Room"
                                class="w-full h-full object-fill">
                            <div class="absolute bottom-0 left-0 right-0 bg-white/70 backdrop-blur-sm p-3 rounded-b-lg">
                                <div class="flex justify-between items-center h-full">
                                    <div class="flex-1 flex justify-center items-center">
                                        <div
                                            class="text-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                                            <div class="text-lg font-bold">20°C</div>
                                            <div class="text-lg font-bold">60 RH</div>
                                        </div>
                                    </div>
                                    <div class="flex-1 flex justify-center items-center">
                                        <div
                                            class="bg-transparent rounded-lg p-1 text-center border-2 border-blue-500 w-20">
                                            <span>❄️</span>
                                            <div
                                                class="text-blue-400 bg-gradient-to-r from-blue-500 to-blue-600 bg-clip-text text-transparent">
                                                <div class="text-xs font-bold">20°C</div>
                                                <div class="text-xs font-bold">60RH</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="font-bold">FESEM Room</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="w-full md:w-1/2 mx-auto space-y-4 px-10 pb-20">
            <!-- Generate Report -->
            <button
                class="transition ease-in-out delay-100 hover:-translate-y-1 hover:scale-102 w-full bg-[#3B82F6] text-white py-3 rounded-lg flex items-center justify-center space-x-2 drop-shadow-lg">
                <span>Generate Report</span>
                <i data-feather="file-text"></i>
            </button>
            <!-- Customer Analytics -->
            <button
                class="transition ease-in-out delay-100 hover:-translate-y-1 hover:scale-102 w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-lg flex items-center justify-center space-x-2 drop-shadow-lg">
                <span>Customer Analytics</span>
                <i data-feather="users"></i>
            </button>
        </div>

        <!-- Notifications list modal -->
        <div id="notificationModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="min-h-screen px-4 text-center">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <!-- Modal header -->
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                            <button onclick="closeNotificationModal()" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <i data-feather="x"></i>
                            </button>
                        </div>

                        <!-- Date range selectors -->
                        <div class="mb-4 flex space-x-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" id="notifStartDate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" id="notifEndDate"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Notification list -->
                        <div id="notificationList" class="max-h-96 overflow-y-auto">
                            <!-- Notifications will be loaded here -->
                        </div>

                        <div id="loadMoreContainer" class="text-center py-4 hidden">
                            <button onclick="loadMoreNotifications()"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Load More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification detail modal -->
        <div id="notificationDetailModal" class="fixed inset-0 z-50 hidden">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
            
            <!-- Center content -->
            <div class="fixed inset-0 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900">Notification Detail</h3>
                        <button onclick="closeNotificationDetailModal()" class="text-gray-400 hover:text-gray-500">
                            <i data-feather="x"></i>
                        </button>
                    </div>
                    
                    <!-- Content -->
                    <div id="notificationDetailContent" class="px-6 py-4">
                        <!-- Content will be injected here -->
                    </div>
                    
                </div>
            </div>
        </div>

    </main>

    <script>
        feather.replace();

        //For getting the sensor data 
        function fetchSensor1Data() {
            $.ajax({
                url: '/dashboard/sensor1', // The URL the request is sent to
                method: 'GET', // AJAX sends a GET request to retrieve data
                success: function(data) {
                    $('#Sensor1Temp').text(data.temperature);
                    $('#Sensor1Humi').text(data.humidity);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }


        //For notifications

        function toggleNotificationIndicator(hasUnseenNotifications) {
            console.log("hasUnseenNotifications", hasUnseenNotifications);

            const iconWrapper = document.getElementById('notificationIcon');;
            console.log(iconWrapper);

            if (hasUnseenNotifications) {
                iconWrapper.classList.remove('text-gray-500');
                iconWrapper.classList.add('text-red-500');
            } else {
                iconWrapper.classList.remove('text-red-500');
                iconWrapper.classList.add('text-gray-500');
            }
            feather.replace();

        }

        function showNotificationModal() {
            feather.replace();

            document.getElementById('notificationModal').classList.remove('hidden');
            loadNotifications();
        }

        function closeNotificationModal() {
            document.getElementById('notificationModal').classList.add('hidden');
        }

        function showNotificationDetail(id) {
            feather.replace();

            // Show detail modal and mark as seen
            fetch(`/notifications/${id}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('notificationDetailContent').innerHTML = `
        <div class="space-y-4">
            <p>${data.message}</p>
            <p class="text-sm text-gray-500">Detected at: ${data.timestamp}</p>
        </div>
    `;
                    document.getElementById('notificationDetailModal').classList.remove('hidden');
                    markAsSeen(id);
                });
        }

        function closeNotificationDetailModal() {
            document.getElementById('notificationDetailModal').classList.add('hidden');
            loadNotifications(); // Reload list to update seen status
        }

        function markAsSeen(id) {
            fetch(`/notifications/${id}/mark-as-seen`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(() => checkUnseenNotifications());
        }



        let currentPage = 1;

        function loadNotifications(resetPage = true) {
            if (resetPage) {
                currentPage = 1;
            }

            const startDate = document.getElementById('notifStartDate').value;
            const endDate = document.getElementById('notifEndDate').value;

            fetch(`/notifications?start_date=${startDate}&end_date=${endDate}&page=${currentPage}`)
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const loadMoreContainer = document.getElementById('loadMoreContainer');

                    const notifications = data.notifications.map(notification => `
                <div onclick="showNotificationDetail(${notification.id})" 
                    class="cursor-pointer p-4 hover:bg-gray-50 border-b ${!notification.seen ? 'bg-blue-50' : ''}">
                    <div class="flex justify-between">
                        <p class="text-sm font-medium ${!notification.seen ? 'text-blue-600' : 'text-gray-900'}">
                            ${notification.message.substring(0, 100)}...
                        </p>
                        <span class="text-xs text-gray-500">
                            ${new Date(notification.created_at).toLocaleDateString()}
                        </span>
                    </div>
                </div>
            `).join('');

                    if (currentPage === 1) {
                        notificationList.innerHTML = notifications;
                    } else {
                        notificationList.innerHTML += notifications;
                    }

                    loadMoreContainer.classList.toggle('hidden', !data.hasMore);
                });
        }

        function loadMoreNotifications() {
            currentPage++;
            loadNotifications(false);
        }


        // Add real-time updates using setInterval
        setInterval(() => {
            const startDate = document.getElementById('notifStartDate').value;
            const endDate = document.getElementById('notifEndDate').value;

            fetch(`/notifications?start_date=${startDate}&end_date=${endDate}&page=1`)
                .then(response => response.json())
                .then(data => {
                    const existingFirstNotification = document.querySelector(
                        '#notificationList div:first-child');
                    const existingFirstId = existingFirstNotification ?
                        parseInt(existingFirstNotification.getAttribute('onclick').match(/\d+/)[0]) : 0;

                    const newNotifications = data.notifications.filter(notification =>
                        !document.querySelector(`[onclick*="showNotificationDetail(${notification.id})"]`)
                    );

                    if (newNotifications.length > 0) {
                        const notificationList = document.getElementById('notificationList');
                        const newNotificationsHtml = newNotifications.map(notification => `
                    <div onclick="showNotificationDetail(${notification.id})" 
                        class="cursor-pointer p-4 hover:bg-gray-50 border-b bg-blue-50 animate-fadeIn">
                        <div class="flex justify-between">
                            <p class="text-sm font-medium text-blue-600">
                                ${notification.message.substring(0, 100)}...
                            </p>
                            <span class="text-xs text-gray-500">
                                ${new Date(notification.created_at).toLocaleDateString()}
                            </span>
                        </div>
                    </div>
                `).join('');

                        notificationList.insertAdjacentHTML('afterbegin', newNotificationsHtml);
                        checkUnseenNotifications();
                    }
                });
        }, 5000); // Check for new notifications every 5 seconds



        function checkUnseenNotifications() {
            fetch('/notifications/unseen-count')
                .then(response => response.json())
                .then(data => {
                    toggleNotificationIndicator(data.count > 0);
                });
        }

        // Event listeners for date range filters
        document.getElementById('notifStartDate').addEventListener('change', loadNotifications);
        document.getElementById('notifEndDate').addEventListener('change', loadNotifications);

        // Initial check for unseen notifications
        document.addEventListener('DOMContentLoaded', () => {
            checkUnseenNotifications();
        });


        // Fetch data every 5 seconds
        setInterval(fetchSensor1Data, 5000);

        // Initial fetch
        fetchSensor1Data();
    </script>

    {{-- @include('components.notification-modal') --}}

</x-app-layout>
