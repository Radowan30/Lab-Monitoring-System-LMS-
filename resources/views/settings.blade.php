<x-app-layout>
    <!-- Main Content -->
    <main class="flex-1 lg:ml-64 min-h-screen">
        <!-- Mobile Header -->
        <header class="lg:hidden dashboard-header flex justify-between items-center">
            <div class="w-full text-center">
                <h1 class="text-xl font-semibold">Settings</h1>
            </div>
            <button onclick="toggleSidebar()" class="absolute right-4 p-2">
                <i data-feather="more-vertical"></i>
            </button>
        </header>

        <!-- Desktop Header -->
        <header class="hidden lg:block dashboard-header">
            <h1 class="text-xl font-semibold">Settings</h1>
        </header>

        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
            <h1 class="text-2xl font-bold mb-6">Notification Thresholds</h1>

            <form method="post" action="{{ route('settings.save') }}">
                @csrf

                <div class="space-y-4">
                    @foreach ($sensors as $sensor)
                        <div class="flex items-center justify-between">
                            <span>{{ $sensor->lab_room_name }}</span>

                            <div class="flex items-center gap-2">
                                <input type="number" step="0.01" name="rooms[{{ $sensor->sensor_id }}][temperature]"
                                    value="{{ $sensor->temp_threshold }}" class="border rounded px-1 py-1 w-16" />
                                <span>°C</span>

                                <input type="number" step="0.1" name="rooms[{{ $sensor->sensor_id }}][humidity]"
                                    value="{{ $sensor->humidity_threshold }}" class="border rounded px-1 py-1 w-16" />
                                <span>RH</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <span class="flex items-center gap-4">
                    <div class="mt-6">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>

                    @if (session('status') === 'Settings saved successfully!')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-green-600 mt-4">
                            {{ session('status') }}
                        </p>
                    @endif
                </span>
            </form>

            <div class="mt-8 mb-4 flex justify-center items-center">
                <a href="{{ route('profile.edit') }}" class="text-blue-800 inline-flex gap-2">
                    <i data-feather="user"></i>
                    <span>Profile Settings</span>
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
        </div>
    </main>

    <script>
        feather.replace();
    </script>
</x-app-layout>
