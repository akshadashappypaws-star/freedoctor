<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WhatsApp Management')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        /* Sidebar Styles */
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
        }
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Bootstrap + Tailwind Compatibility */
        .btn {
            border-radius: 0.375rem;
        }
        .card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .table {
            margin-bottom: 0;
        }
        
        /* Custom gradient backgrounds */
        .bg-gradient-1 { background: linear-gradient(45deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-2 { background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%); }
        .bg-gradient-3 { background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%); }
        .bg-gradient-4 { background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%); }
        .bg-gradient-5 { background: linear-gradient(45deg, #fa709a 0%, #fee140 100%); }
        
        /* Badge styles */
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .bg-success-subtle { background-color: rgba(25, 135, 84, 0.1); }
        .text-success { color: #198754; }
        
        /* Additional styles for compatibility */
        .text-xs { font-size: 0.75rem; }
        .text-sm { font-size: 0.875rem; }
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-4 > * + * { margin-left: 1rem; }
        
        /* Form and input compatibility */
        .form-select, .form-control {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
        }
        .form-select:focus, .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-lg border-b border-gray-200 fixed top-0 left-0 right-0 z-40">
        <div class="mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Menu button and Title -->
                <div class="flex items-center space-x-4">
                    <button id="menuButton" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fab fa-whatsapp text-white"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold text-gray-900">WhatsApp Management</h1>
                        </div>
                    </div>
                </div>
                
                <!-- Right side - Status and User -->
                <div class="flex items-center space-x-4">
                    <!-- WhatsApp Status -->
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full {{ session('whatsapp_connected') ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <span class="text-sm font-medium text-gray-700">
                            {{ session('whatsapp_connected') ? 'Connected' : 'Not Connected' }}
                        </span>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="text-sm font-medium">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar fixed top-16 left-0 bottom-0 w-80 bg-white shadow-xl z-30 overflow-y-auto">
        <div class="p-6">
            <!-- WhatsApp Manager Navigation -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">WhatsApp Manager</h3>
                <nav class="space-y-2">
                    <a href="{{ route('admin.whatsapp.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.dashboard') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-tachometer-alt mr-3 text-lg"></i>
                        <span>Dashboard</span>
                        @if(Request::routeIs('admin.whatsapp.dashboard'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.workflows') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.workflows*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-project-diagram mr-3 text-lg"></i>
                        <span>Scenario Workflows</span>
                        <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">AI</span>
                        @if(Request::routeIs('admin.whatsapp.workflows*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.machines') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.machines*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cogs mr-3 text-lg"></i>
                        <span>Machine Configs</span>
                        @if(Request::routeIs('admin.whatsapp.machines*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.conversations') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.conversations*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-comments mr-3 text-lg"></i>
                        <span>Live Conversations</span>
                        <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">LIVE</span>
                        @if(Request::routeIs('admin.whatsapp.conversations*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.analytics') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.analytics*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-line mr-3 text-lg"></i>
                        <span>Analytics & Reports</span>
                        @if(Request::routeIs('admin.whatsapp.analytics*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.automation') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.automation*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-robot mr-3 text-lg"></i>
                        <span>Automation Rules</span>
                        @if(Request::routeIs('admin.whatsapp.automation*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.settings') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.settings*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-sliders-h mr-3 text-lg"></i>
                        <span>Bot Settings</span>
                        @if(Request::routeIs('admin.whatsapp.settings*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                </nav>
            </div>
            
            <!-- Legacy Features -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Legacy Features</h3>
                <nav class="space-y-2">
                    <a href="{{ route('admin.whatsapp.bulk-messages') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.bulk-messages*') ? 'bg-gray-50 text-gray-700 border-r-4 border-gray-400' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-paper-plane mr-3 text-lg"></i>
                        <span>Bulk Messages</span>
                        @if(Request::routeIs('admin.whatsapp.bulk-messages*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.templates') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.templates*') ? 'bg-gray-50 text-gray-700 border-r-4 border-gray-400' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-file-alt mr-3 text-lg"></i>
                        <span>Templates</span>
                        @if(Request::routeIs('admin.whatsapp.templates*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.auto-replies') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.auto-replies*') ? 'bg-gray-50 text-gray-700 border-r-4 border-gray-400' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-reply-all mr-3 text-lg"></i>
                        <span>Auto Reply</span>
                        @if(Request::routeIs('admin.whatsapp.auto-replies*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.chatgpt') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.chatgpt*') ? 'bg-gray-50 text-gray-700 border-r-4 border-gray-400' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-brain mr-3 text-lg"></i>
                        <span>ChatGPT</span>
                        @if(Request::routeIs('admin.whatsapp.chatgpt*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                </nav>
            </div>
            
            <!-- Quick Stats -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-green-700">Active Machines</span>
                            <span class="text-lg font-bold text-green-600">{{ session('active_machines', 5) }}</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-700">Workflows Today</span>
                            <span class="text-lg font-bold text-blue-600">{{ session('workflows_today', 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back to Admin -->
            <div class="border-t pt-6">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-arrow-left mr-3"></i>
                    <span>Back to Admin Dashboard</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Overlay -->
    <div id="overlay" class="overlay fixed inset-0 bg-black bg-opacity-50 z-20"></div>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        <div class="px-6 py-8 max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">
                        @hasSection('page_title')
                            @yield('page_title')
                        @else
                            @yield('title', 'WhatsApp Management')
                        @endif
                    </h2>
                    <p class="text-gray-600 mt-2">
                        @hasSection('page_subtitle')
                            @yield('page_subtitle')
                        @else
                            @yield('subtitle', 'Manage your WhatsApp bot system')
                        @endif
                    </p>
                </div>
                <div class="flex space-x-3">
                    @yield('page_actions')
                </div>
            </div>

            <!-- Main Content -->
            @yield('whatsapp_content')
            @yield('content')
        </div>
    </main>

    <!-- Success/Error Alerts -->
    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-lg rounded-lg max-w-md z-50" 
             id="success-alert">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-lg rounded-lg max-w-md z-50" 
             id="error-alert">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('whatsapp_error'))
        <div class="fixed bottom-4 left-4 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 shadow-lg rounded-lg max-w-md z-50" 
             id="whatsapp-error-alert">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">WhatsApp API Issue</p>
                    <p class="text-xs">{{ session('whatsapp_error') }}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                            class="inline-flex rounded-md p-1.5 text-orange-500 hover:bg-orange-100 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- JavaScript -->
    <script>
        // Sidebar toggle functionality
        const menuButton = document.getElementById('menuButton');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        menuButton.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const successAlert = document.getElementById('success-alert');
            const errorAlert = document.getElementById('error-alert');
            const whatsappErrorAlert = document.getElementById('whatsapp-error-alert');
            if (successAlert) successAlert.remove();
            if (errorAlert) errorAlert.remove();
            if (whatsappErrorAlert) whatsappErrorAlert.remove();
        }, 5000);

        // Global utility functions for WhatsApp pages
        function showLoading(message = 'Loading...') {
            const loader = document.createElement('div');
            loader.id = 'global-loader';
            loader.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            loader.innerHTML = '<div class="bg-white rounded-lg p-6 flex items-center space-x-4 shadow-xl">' +
                '<svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">' +
                    '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                    '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>' +
                '</svg>' +
                '<span class="text-gray-700 font-medium">' + message + '</span>' +
            '</div>';
            document.body.appendChild(loader);
        }

        function hideLoading() {
            const loader = document.getElementById('global-loader');
            if (loader) loader.remove();
        }

        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-100 text-green-700 border-green-500' : 'bg-red-100 text-red-700 border-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            alert.className = 'fixed bottom-4 right-4 p-4 rounded-lg shadow-lg border-l-4 ' + bgColor + ' max-w-md z-50';
            alert.innerHTML = '<div class="flex">' +
                '<div class="py-1">' +
                    '<i class="fas ' + icon + '"></i>' +
                '</div>' +
                '<div class="ml-3">' +
                    '<p class="text-sm font-medium">' + message + '</p>' +
                '</div>' +
                '<div class="ml-auto pl-3">' +
                    '<button onclick="this.parentElement.parentElement.parentElement.remove()" ' +
                            'class="inline-flex rounded-md p-1.5 hover:bg-opacity-20 focus:outline-none">' +
                        '<i class="fas fa-times"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 5000);
        }

        // Confirm dialog
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }

        // Mobile responsive - auto close sidebar on resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) { // lg breakpoint
                closeSidebar();
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page-specific scripts -->
    @yield('scripts')
</body>
</html>

