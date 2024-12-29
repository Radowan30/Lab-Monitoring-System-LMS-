<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased ">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-300 to-blue-600">
        <div class="w-5/6 sm:max-w-md mt-6 px-6 py-4 bg-white text-gray-900 shadow-md overflow-hidden rounded-lg">
            <!-- Logo Section -->
            <div class="flex justify-center mb-4">
                <a href="/">
                    <img src="{{ asset('images/Logo/Logo.png') }}" alt="Lab Monitoring System Logo" class="w-20 h-20">
                </a>
            </div>
            <!-- Content Section -->
            {{ $slot }}
        </div>
    </div>
</body>

</html>
