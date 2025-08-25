<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WhatsApp Management')</title>
    
    <!-- Vite CSS & JS (includes all local dependencies) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap CSS (critical for UI) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <!-- Font Awesome 6.5.0 - Latest Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous">
    
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    
    <!-- Google Fonts - Professional Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    
    <!-- Custom Enhanced Styles -->
    <style>
        /* ============================
           ENHANCED UI FRAMEWORK STYLES
           Modern & Professional Design
           ============================ */
        
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #6b7280;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            --gradient-warning: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --gradient-danger: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* GLOBAL TYPOGRAPHY */
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #1e293b;
        }

        code, pre {
            font-family: 'JetBrains Mono', monospace;
        }

        /* ENHANCED SIDEBAR STYLES */
        .sidebar {
            background: linear-gradient(180deg, #1e293b 0%, #334155 100%);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transform: translateX(-100%);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: var(--shadow-xl);
        }
        
        .sidebar.open {
            transform: translateX(0);
        }
        
        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0.25rem 0.5rem;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }
        
        .sidebar-item.active {
            background: var(--gradient-primary);
            box-shadow: var(--shadow-lg);
        }

        /* OVERLAY EFFECTS */
        .overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease-in-out;
            backdrop-filter: blur(4px);
            background: rgba(0, 0, 0, 0.3);
        }
        
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ENHANCED CARD STYLES */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }
        
        .card-header {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 1.5rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }

        /* BUTTON ENHANCEMENTS */
        .btn {
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn:hover::before {
            left: 100%;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .btn-success {
            background: var(--gradient-success);
            border: none;
            color: #065f46;
        }
        
        .btn-warning {
            background: var(--gradient-warning);
            border: none;
            color: #92400e;
        }
        
        .btn-danger {
            background: var(--gradient-danger);
            border: none;
            color: #991b1b;
        }

        /* TABLE ENHANCEMENTS */
        .table {
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-bottom: 0;
        }
        
        .table thead th {
            background: var(--gradient-primary);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
        }
        
        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }
        
        .table tbody tr:hover {
            background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        /* FORM ENHANCEMENTS */
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: white;
            font-size: 0.875rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        /* BADGE ENHANCEMENTS */
        .badge {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* GRADIENT BACKGROUNDS */
        .bg-gradient-primary { background: var(--gradient-primary); }
        .bg-gradient-success { background: var(--gradient-success); }
        .bg-gradient-warning { background: var(--gradient-warning); }
        .bg-gradient-danger { background: var(--gradient-danger); }
        
        /* ANIMATION CLASSES */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        
        .animate-bounce-in {
            animation: bounceIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* UTILITY CLASSES */
        .text-xs { font-size: 0.75rem; line-height: 1rem; }
        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
        .text-base { font-size: 1rem; line-height: 1.5rem; }
        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
        .text-xl { font-size: 1.25rem; line-height: 1.75rem; }
        
        .space-y-2 > * + * { margin-top: 0.5rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .space-y-6 > * + * { margin-top: 1.5rem; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .space-x-4 > * + * { margin-left: 1rem; }
        
        .shadow-soft { box-shadow: var(--shadow); }
        .shadow-medium { box-shadow: var(--shadow-md); }
        .shadow-strong { box-shadow: var(--shadow-lg); }
        .shadow-epic { box-shadow: var(--shadow-xl); }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .card-body { padding: 1rem; }
            .btn { padding: 0.5rem 1rem; font-size: 0.875rem; }
            .table thead th, .table tbody td { padding: 0.75rem 0.5rem; }
        }

        /* SCROLLBAR STYLING */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* LOADING ANIMATIONS */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* GLASSMORPHISM EFFECTS */
        .glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .glass-dark {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
                    
                    <a href="{{ route('admin.whatsapp.automation') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.automation*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-robot mr-3 text-lg"></i>
                        <span>Automation</span>
                        <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">AI</span>
                        @if(Request::routeIs('admin.whatsapp.automation*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.automation.rules') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.automation.rules*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cogs mr-3 text-lg"></i>
                        <span>Automation Rules</span>
                        @if(Request::routeIs('admin.whatsapp.automation.rules*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.conversations') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.conversations*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-comments mr-3 text-lg"></i>
                        <span>Live Conversations</span>
                        <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">LIVE</span>
                        @if(Request::routeIs('admin.whatsapp.conversations*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.settings') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.settings*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-sliders-h mr-3 text-lg"></i>
                        <span>Settings</span>
                        @if(Request::routeIs('admin.whatsapp.settings*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                </nav>
            </div>
            
            <!-- Message Management -->
            <div class="mb-8">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Message Management</h3>
                <nav class="space-y-2">
                    <a href="{{ route('admin.whatsapp.templates') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.templates*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-file-alt mr-3 text-lg"></i>
                        <span>Templates</span>
                        @if(Request::routeIs('admin.whatsapp.templates*'))
                            <i class="fas fa-chevron-right ml-auto"></i>
                        @endif
                    </a>
                    
                    <a href="{{ route('admin.whatsapp.bulk-messages') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::routeIs('admin.whatsapp.bulk-messages*') ? 'bg-blue-50 text-blue-700 border-r-4 border-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-paper-plane mr-3 text-lg"></i>
                        <span>Bulk Messages</span>
                        @if(Request::routeIs('admin.whatsapp.bulk-messages*'))
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

    <!-- All JavaScript libraries now loaded via Vite from app.js -->
    <!-- This includes: jQuery, Bootstrap, SweetAlert2, Notyf, AOS, Chart.js, SortableJS, Typed.js, CountUp.js -->
    
    <!-- Enhanced Global JavaScript -->
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false
                });
            }
            
            // Initialize Notyf notifications with error handling
            let notyf;
            try {
                if (typeof Notyf !== 'undefined') {
                    notyf = new Notyf({
                        duration: 4000,
                        position: {
                            x: 'right',
                            y: 'top',
                        },
                        types: [
                            {
                                type: 'success',
                                background: 'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)',
                                icon: {
                                    className: 'fas fa-check-circle',
                                    tagName: 'i',
                                    color: 'white'
                                }
                            },
                            {
                                type: 'error',
                                background: 'linear-gradient(135deg, #ff6b6b 0%, #ffa8a8 100%)',
                                icon: {
                                    className: 'fas fa-times-circle',
                                    tagName: 'i',
                                    color: 'white'
                                }
                            },
                            {
                                type: 'warning',
                                background: 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
                                icon: {
                                    className: 'fas fa-exclamation-triangle',
                                    tagName: 'i',
                                    color: '#8b5a00'
                                }
                            },
                            {
                                type: 'info',
                                background: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
                                icon: {
                                    className: 'fas fa-info-circle',
                                    tagName: 'i',
                                    color: '#0369a1'
                                }
                            }
                        ]
                    });
                    
                    // Make notyf globally available
                    window.notyf = notyf;
                }
            } catch (error) {
                console.warn('Notyf initialization failed:', error);
                // Fallback to simple notifications
                window.notyf = {
                    success: function(msg) { alert('Success: ' + msg); },
                    error: function(msg) { alert('Error: ' + msg); },
                    warning: function(msg) { alert('Warning: ' + msg); },
                    info: function(msg) { alert('Info: ' + msg); },
                    open: function(options) { alert(options.message || 'Notification'); }
                };
            }
            
            // Add smooth scrolling to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add loading states to all buttons
            document.querySelectorAll('button[type="submit"], .btn-loading').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!this.disabled) {
                        this.innerHTML = '<i class="loading-spinner me-2"></i>Processing...';
                        this.disabled = true;
                        
                        // Re-enable after 3 seconds (fallback)
                        setTimeout(() => {
                            if (this.disabled) {
                                this.disabled = false;
                                this.innerHTML = this.getAttribute('data-original-text') || 'Submit';
                            }
                        }, 3000);
                    }
                });
            });
            
            // Enhanced form validation
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;
                    
                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!isValid) {
                        e.preventDefault();
                        notyf.error('Please fill in all required fields');
                        return false;
                    }
                });
            });
            
            // Auto-hide alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('alert-success') || alert.classList.contains('alert-info')) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);
        });
        
        // Enhanced notification functions
        function showSuccess(message, title = 'Success!') {
            notyf.success(message);
            
            // Also show SweetAlert for important success messages
            if (title !== 'Success!') {
                Swal.fire({
                    icon: 'success',
                    title: title,
                    text: message,
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        }
        
        function showError(message, title = 'Error!') {
            notyf.error(message);
            
            Swal.fire({
                icon: 'error',
                title: title,
                text: message,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        }
        
        function showWarning(message, title = 'Warning!') {
            notyf.open({
                type: 'warning',
                message: message
            });
        }
        
        function showInfo(message, title = 'Info') {
            notyf.open({
                type: 'info',
                message: message
            });
        }
        
        // Enhanced confirm dialog
        function confirmAction(message, callback, title = 'Are you sure?') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback();
                }
            });
        }
        
        // Animated counter function
        function animateCounter(element, finalValue, duration = 2000) {
            const countUp = new CountUp(element, finalValue, {
                duration: duration / 1000,
                useEasing: true,
                useGrouping: true,
                separator: ',',
                decimal: '.'
            });
            
            if (!countUp.error) {
                countUp.start();
            }
        }
        
        // Initialize counters on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-counter]').forEach(el => {
                const value = parseInt(el.getAttribute('data-counter')) || parseInt(el.textContent);
                if (value) {
                    el.textContent = '0';
                    animateCounter(el, value);
                }
            });
        });
        
        // Add ripple effect to buttons
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn, button')) {
                const btn = e.target;
                const rect = btn.getBoundingClientRect();
                const ripple = document.createElement('span');
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.5);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                // Add ripple animation CSS if not already added
                if (!document.querySelector('#ripple-styles')) {
                    const style = document.createElement('style');
                    style.id = 'ripple-styles';
                    style.textContent = `
                        @keyframes ripple {
                            to {
                                transform: scale(4);
                                opacity: 0;
                            }
                        }
                        .btn, button {
                            position: relative;
                            overflow: hidden;
                        }
                    `;
                    document.head.appendChild(style);
                }
                
                btn.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            }
        });
        
        // Global AJAX setup with enhanced error handling
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                // Show loading indicator
                if (!document.querySelector('.global-loading')) {
                    const loader = document.createElement('div');
                    loader.className = 'global-loading';
                    loader.innerHTML = `
                        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #3b82f6, #8b5cf6); z-index: 9999; animation: loading 1.5s ease-in-out infinite;"></div>
                    `;
                    document.body.appendChild(loader);
                    
                    // Add loading animation CSS
                    if (!document.querySelector('#loading-styles')) {
                        const style = document.createElement('style');
                        style.id = 'loading-styles';
                        style.textContent = `
                            @keyframes loading {
                                0% { transform: translateX(-100%); }
                                50% { transform: translateX(100%); }
                                100% { transform: translateX(-100%); }
                            }
                        `;
                        document.head.appendChild(style);
                    }
                }
            },
            complete: function() {
                // Hide loading indicator
                const loader = document.querySelector('.global-loading');
                if (loader) {
                    setTimeout(() => loader.remove(), 300);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                
                if (xhr.status === 419) {
                    showError('Session expired. Please refresh the page.', 'Session Expired');
                } else if (xhr.status === 403) {
                    showError('You do not have permission to perform this action.', 'Access Denied');
                } else if (xhr.status === 404) {
                    showError('The requested resource was not found.', 'Not Found');
                } else if (xhr.status >= 500) {
                    showError('A server error occurred. Please try again later.', 'Server Error');
                } else {
                    showError('An unexpected error occurred. Please try again.', 'Error');
                }
            }
        });
    </script>
    
    <!-- Enhanced jQuery initialization -->
    <script>
        // Enhanced jQuery initialization when available
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function($) {
                console.log('✅ jQuery enhanced features initialized');
                
                // Enhanced tooltips with error handling
                try {
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger: 'hover',
                        delay: { show: 300, hide: 100 },
                        html: true,
                        placement: 'auto'
                    });
                } catch (e) {
                    console.warn('Tooltip initialization failed:', e);
                }
                
                // Enhanced popovers with error handling
                try {
                    $('[data-bs-toggle="popover"]').popover({
                        trigger: 'click',
                        placement: 'auto',
                        html: true,
                        sanitize: false
                    });
                } catch (e) {
                    console.warn('Popover initialization failed:', e);
                }
                
                // Auto-dismiss alerts with fade effect
                $('.alert').each(function() {
                    const $alert = $(this);
                    if ($alert.data('auto-dismiss')) {
                        setTimeout(() => {
                            $alert.fadeOut(500, function() {
                                $(this).remove();
                            });
                        }, parseInt($alert.data('auto-dismiss')) || 5000);
                    }
                });
                
                // Enhanced form validation feedback
                $('form').on('submit', function() {
                    const $form = $(this);
                    const $submitBtn = $form.find('button[type="submit"]');
                    
                    if ($submitBtn.length) {
                        $submitBtn.prop('disabled', true).addClass('btn-loading');
                        const originalText = $submitBtn.text();
                        $submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
                        
                        // Re-enable after 10 seconds as fallback
                        setTimeout(() => {
                            $submitBtn.prop('disabled', false).removeClass('btn-loading');
                            $submitBtn.text(originalText);
                        }, 10000);
                    }
                });
                
                // Enhanced smooth scrolling for anchor links
                $('a[href^="#"]').on('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#') return; // Skip empty anchors
                    
                    const target = $(href);
                    if (target.length) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 800, 'swing');
                    }
                });
                
                // Enhanced loading states for AJAX requests
                $(document).ajaxStart(function() {
                    $('body').addClass('ajax-loading');
                    
                    // Add loading overlay if not exists
                    if (!$('.ajax-overlay').length) {
                        $('body').append(`
                            <div class="ajax-overlay" style="
                                position: fixed;
                                top: 0;
                                left: 0;
                                width: 100%;
                                height: 100%;
                                background: rgba(0,0,0,0.1);
                                z-index: 9998;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        `);
                    }
                }).ajaxStop(function() {
                    $('body').removeClass('ajax-loading');
                    $('.ajax-overlay').fadeOut(300, function() {
                        $(this).remove();
                    });
                }).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                    console.error('AJAX Error:', thrownError);
                    
                    // Handle specific error codes
                    if (jqXHR.status === 419) {
                        if (window.showError) {
                            window.showError('Your session has expired. Please refresh the page.', 'Session Expired');
                        }
                    } else if (jqXHR.status === 403) {
                        if (window.showError) {
                            window.showError('You do not have permission to perform this action.', 'Access Denied');
                        }
                    } else if (jqXHR.status >= 500) {
                        if (window.showError) {
                            window.showError('A server error occurred. Please try again later.', 'Server Error');
                        }
                    }
                });
                
                // Enhanced form field validation
                $('input, textarea, select').on('blur', function() {
                    const $field = $(this);
                    if ($field.prop('required') && !$field.val().trim()) {
                        $field.addClass('is-invalid');
                    } else {
                        $field.removeClass('is-invalid');
                    }
                });
                
                // Auto-grow textareas
                $('textarea[data-auto-grow]').each(function() {
                    const $textarea = $(this);
                    $textarea.on('input', function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    });
                });
                
                // Enhanced table features
                $('.table').each(function() {
                    const $table = $(this);
                    
                    // Add hover effects
                    $table.find('tbody tr').hover(
                        function() {
                            $(this).addClass('table-hover-effect');
                        },
                        function() {
                            $(this).removeClass('table-hover-effect');
                        }
                    );
                    
                    // Add click effects for action buttons
                    $table.find('.btn').on('click', function() {
                        const $btn = $(this);
                        $btn.addClass('btn-clicked');
                        setTimeout(() => $btn.removeClass('btn-clicked'), 150);
                    });
                });
                
                // Copy to clipboard functionality
                $('[data-clipboard]').on('click', function() {
                    const text = $(this).data('clipboard') || $(this).text();
                    navigator.clipboard.writeText(text).then(() => {
                        if (window.notyf) {
                            window.notyf.success('Copied to clipboard!');
                        }
                        
                        // Visual feedback
                        const $btn = $(this);
                        const originalText = $btn.html();
                        $btn.html('<i class="fas fa-check text-success"></i> Copied!');
                        setTimeout(() => $btn.html(originalText), 2000);
                    }).catch(() => {
                        if (window.notyf) {
                            window.notyf.error('Failed to copy to clipboard');
                        }
                    });
                });
                
                console.log('✅ All jQuery enhancements loaded successfully');
            });
        } else {
            console.warn('⚠️ jQuery not available - some features may be limited');
        }
    
    <!-- Page-specific scripts -->
    @yield('scripts')

    <!-- Page-specific scripts -->
    @yield('scripts')
</body>
</html>

