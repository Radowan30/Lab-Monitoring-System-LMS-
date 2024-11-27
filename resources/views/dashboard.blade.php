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
                    <i data-feather="alert-circle" class="text-red-500"></i>
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
                                    <div class="text-lg font-bold">20°C</div>
                                    <div class="text-lg font-bold">60 RH</div>
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


        </div>
    </main>

    <script>
        feather.replace();
    </script>

</x-app-layout>
