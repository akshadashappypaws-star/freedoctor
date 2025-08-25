<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-id" content="{{ auth('admin')->id() }}">
    <title>@yield('title', 'FreeDoctor Admin')</title>
    <meta name="description" content="@yield('description', 'Free medical camps admin platform')">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .sidebar-transition { transition: all 0.3s ease; }
        .content-shift { margin-left: 250px; }
        .content-full { margin-left: 0; }
        @media (max-width: 768px) {
            .content-shift { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Toggle -->
    <button id="mobile-menu-toggle" class="fixed top-4 left-4 z-50 md:hidden bg-blue-600 text-white p-2 rounded-lg shadow-lg">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div id="sidebar" class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg transform -translate-x-full md:translate-x-0 transition-transform z-40">
        @include('admin.partials.sidebar')
    </div>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden md:hidden"></div>

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300 md:ml-64">
      

        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking overlay
        document.getElementById('mobile-overlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });

        // Admin dropdown toggle
        document.getElementById('admin-menu-toggle').addEventListener('click', function() {
            document.getElementById('admin-dropdown').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('admin-dropdown');
            const toggle = document.getElementById('admin-menu-toggle');
            
            if (!toggle.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
