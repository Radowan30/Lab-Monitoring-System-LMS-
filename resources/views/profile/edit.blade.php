<x-app-layout>
    <!-- Main Content -->
    <main class="flex-1 lg:ml-64 min-h-screen">
        <!-- Mobile Header -->
        <header class="lg:hidden dashboard-header flex justify-between items-center">
            <div class="w-full text-center">
                <h1 class="text-xl font-semibold">Profile</h1>
            </div>
            <button onclick="toggleSidebar()" class="absolute right-4 p-2">
                <i data-feather="more-vertical"></i>
            </button>
        </header>

        <!-- Desktop Header -->
        <header class="hidden lg:block dashboard-header">
            <h1 class="text-xl font-semibold">Profile</h1>
        </header>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div> --}}
            </div>
        </div>

        </div>
    </main>
    <script>
        feather.replace();
    </script>
</x-app-layout>
