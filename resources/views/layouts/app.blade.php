<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('Tech Forum', 'Tech Forum') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script>
            // Simple dark mode implementation without Alpine dependencies
            function initDarkMode() {
                // Check if dark mode preference is stored
                const isDarkMode = localStorage.getItem('darkMode') === 'true';
                
                // Apply dark mode class to document
                if (isDarkMode) {
                    document.documentElement.classList.add('dark');
                }
            }
            
            // Toggle dark mode
            function toggleDarkMode() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                if (isDarkMode) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'false');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'true');
                }
            }
            
            // Initialize dark mode on page load
            document.addEventListener('DOMContentLoaded', function() {
                initDarkMode();
            });
        </script>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <div class="min-h-screen flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-700">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow">
                @yield('content')
            </main>

            <!-- Toast Container -->
            @include('components.toast-container')

            <!-- Footer -->
            @include('components.footer')
        </div>
    </body>
</html>
