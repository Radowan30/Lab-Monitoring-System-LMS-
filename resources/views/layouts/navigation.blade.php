{{-- <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> --}}


<!-- Sidebar -->
<aside id="sidebar"
    class="fixed right-0 top-0 bg-white w-64 h-full shadow-lg z-50 sidebar-hidden lg:sidebar-visible lg:left-0 lg:right-auto">
    <div class="p-4">
        <!-- Logo -->
        <div class="logo-container mb-8">
            <img src={{ asset('images/Logo/Logo.png') }} alt="Logo" class="rounded-full bg-[#3B82F6]">
        </div>

        <!-- Navigation Menu -->
        <nav class="space-y-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-2 bg-[#EEF2FF] rounded-lg text-[#3B82F6]">
                <i data-feather="home"></i>
                <span>Dashboard</span>
            </a>
            <div class="dropdown-menu">
                <button class="dropdown-button" onclick="toggleDropdown()">
                    <div class="flex items-center space-x-3">
                        <i data-feather="layout"></i>
                        <span>Lab Rooms</span>
                    </div>
                    <i data-feather="chevron-down" class="dropdown-icon"></i>
                </button>
                <div class="dropdown-content" id="labRoomsDropdown">
                    <a href="{{ route('lab_rooms.prep-lab') }}" class="dropdown-item">
                        <span>Preparation Lab</span>
                    </a>
                    <a href="{{ route('lab_rooms.fetem-room') }}" class="dropdown-item">
                        <span>FETEM Room</span>
                    </a>
                    <a href="{{ route('lab_rooms.fesem-room') }}" class="dropdown-item">
                        <span>FESEM Room</span>
                    </a>
                </div>
            </div>
            <a href="#" class="flex items-center space-x-3 px-4 py-2 text-gray-600">
                <i data-feather="file-text"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('lab.analytics') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-600">
                <i data-feather="users"></i>
                <span>Customer Analytics</span>
            </a>
            <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-2 text-gray-600">
                <i data-feather="settings"></i>
                <span>Settings</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Section -->
    <div class="absolute bottom-0 w-full p-4 border-t">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="route('logout')"
                onclick="event.preventDefault();
                    this.closest('form').submit();"
                class="flex items-center space-x-3 px-4 py-2 text-gray-600">
                <i data-feather="log-out"></i>
                <span>Logout</span>
            </a>
        </form>
        <a href="{{ route('profile.edit') }}">
            <div class="flex items-center space-x-3 px-4 py-2 mt-2">
                <div class="w-8 h-8 rounded-full bg-[#3B82F6] flex items-center justify-center">
                    <i data-feather="user" class="text-white"></i>
                </div>
                <div>
                    <div class="text-sm text-gray-600">Username</div>
                    <div class="text-xs text-gray-400">Lab Technician</div>
                </div>
            </div>
        </a>
    </div>
</aside>

<script>
    feather.replace();

    // Sidebar Toggle Function
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        if (sidebar.classList.contains('sidebar-hidden')) {
            sidebar.classList.remove('sidebar-hidden');
            sidebar.classList.add('sidebar-visible');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.remove('sidebar-visible');
            sidebar.classList.add('sidebar-hidden');
            overlay.classList.add('hidden');
        }
    }

    function toggleDropdown() {
        const dropdown = document.getElementById('labRoomsDropdown');
        const icon = document.querySelector('.dropdown-icon');
        dropdown.classList.toggle('show');
        icon.style.transform = dropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
    }

    // Update the existing toggleSidebar function to handle dropdown state
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const dropdown = document.getElementById('labRoomsDropdown');

        if (sidebar.classList.contains('sidebar-hidden')) {
            sidebar.classList.remove('sidebar-hidden');
            sidebar.classList.add('sidebar-visible');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.remove('sidebar-visible');
            sidebar.classList.add('sidebar-hidden');
            overlay.classList.add('hidden');
            // Reset dropdown when sidebar closes
            dropdown.classList.remove('show');
            document.querySelector('.dropdown-icon').style.transform = 'rotate(0)';
        }
    }
</script>
