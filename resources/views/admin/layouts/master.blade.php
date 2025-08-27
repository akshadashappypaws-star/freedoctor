<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - FreeDoctor</title>
    
    <!-- Vite Assets (includes Tailwind CSS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        /* Professional Admin Sidebar */
        .admin-sidebar {
            background: linear-gradient(180deg, #383f45 0%, #2c3136 50%, #1f2428 100%);
            border-right: 1px solid rgba(56, 63, 69, 0.3);
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.15);
            width: 280px;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.3);
        }
        
        .brand-link {
            color: #ffffff;
            font-size: 1.375rem;
            font-weight: 800;
            text-decoration: none;
            display: flex;
            align-items: center;
            letter-spacing: -0.025em;
        }
        
        .brand-link:hover {
            color: #888f96;
            text-decoration: none;
        }
        
        .brand-icon {
            background: #383f45;
            color: white;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.875rem;
            font-size: 1.125rem;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-navigation {
            padding: 1.25rem 0;
            height: calc(100vh - 90px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .nav-item {
            display: block;
            margin: 0.125rem 1rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-link:hover::before {
            left: 100%;
        }
        
        .nav-link:hover {
            background: rgba(136, 143, 150, 0.15);
            color: #ffffff;
            text-decoration: none;
            transform: translateX(3px);
        }
        
        .nav-link.active {
            background: rgba(136, 143, 150, 0.25);
            color: #ffffff;
            border-left: 3px solid #888f96;
        }
        
        .nav-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 1rem;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        
        .nav-text {
            flex: 1;
            white-space: nowrap;
        }
        
        .section-header {
            padding: 1.5rem 1.75rem 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
        }
        
        .section-icon {
            margin-right: 0.625rem;
            opacity: 0.8;
            font-size: 0.875rem;
        }
        
        .nav-submenu {
            margin-left: 1rem;
            padding-left: 1.5rem;
            border-left: 2px solid rgba(136, 143, 150, 0.2);
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .nav-submenu .nav-item {
            margin: 0.0625rem 0;
        }
        
        .nav-submenu .nav-link {
            padding: 0.625rem 1rem;
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.75);
        }
        
        .nav-submenu .nav-icon {
            width: 1rem;
            margin-right: 0.875rem;
            font-size: 0.875rem;
        }
        
        /* Scrollbar Styling */
        .sidebar-navigation::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-navigation::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .sidebar-navigation::-webkit-scrollbar-thumb {
            background: rgba(136, 143, 150, 0.3);
            border-radius: 3px;
        }
        
        .sidebar-navigation::-webkit-scrollbar-thumb:hover {
            background: rgba(136, 143, 150, 0.5);
        }
        
        /* Main content adjustment */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            background: #f8fafc;
        }
        
        /* Modern UI Enhancements */
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tango, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            line-height: 1.6;
        }
        
        /* Card improvements */
        .card, .whatsapp-card {
            border: none !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06) !important;
            border-radius: 0.5rem !important;
            transition: all 0.2s ease !important;
        }
        
        .card:hover, .whatsapp-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 2px 4px rgba(0, 0, 0, 0.06) !important;
            transform: translateY(-1px);
        }
        
        /* Button improvements */
        .btn, button[class*="bg-"] {
            border-radius: 0.375rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            border: none !important;
            cursor: pointer;
        }
        
        .btn:hover, button[class*="bg-"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }
        
        /* Table improvements */
        .table, table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border-radius: 0.5rem !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .table thead th, table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 1rem !important;
            border: none !important;
        }
        
        .table tbody tr, table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover, table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        
        /* Form improvements */
        .form-control, input[type="text"], input[type="email"], input[type="password"], 
        textarea, select {
            border-radius: 0.375rem !important;
            border: 1.5px solid #d1d5db !important;
            transition: all 0.2s ease !important;
            padding: 0.625rem 0.875rem !important;
        }
        
        .form-control:focus, input:focus, textarea:focus, select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none !important;
        }
        
        /* Modal improvements */
        .modal-content {
            border-radius: 0.75rem !important;
            border: none !important;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Alert improvements */
        .alert {
            border-radius: 0.5rem !important;
            border: none !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Utility classes */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Animation and scroll fixes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        /* Header Styling */
        .main-header {
            background: linear-gradient(135deg, #383f45 0%, #2c3136 100%);
            border-bottom: 1px solid rgba(56, 63, 69, 0.3);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .main-header h2 {
            color: #ffffff !important;
            margin: 0;
        }
        
        .main-header .breadcrumb a {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .main-header .breadcrumb a:hover {
            color: #ffffff !important;
        }
        
        .main-header .breadcrumb span {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        
        .header-actions button {
            color: rgba(255, 255, 255, 0.7) !important;
            transition: all 0.2s ease;
        }
        
        .header-actions button:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        .mobile-menu-btn {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        /* Fix main content scrolling and positioning */
        .main-content {
            height: 100vh;
            overflow-y: auto;
            background: #f8fafc;
        }
        
        .content-wrapper {
            min-height: calc(100vh - 80px);
            padding: 1.5rem;
        }
        
        /* Mobile menu backdrop */
        .mobile-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .mobile-backdrop.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1001;
            }
            
            .admin-sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        
        /* Scrollbar styling for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile backdrop -->
        <div class="mobile-backdrop" id="mobile-backdrop"></div>
        
        <!-- Professional Admin Sidebar -->
        <aside class="admin-sidebar">
            <!-- Brand Header -->
            <div class="sidebar-brand">
                <a href="{{ route('admin.dashboard') }}" class="brand-link">
                    <div class="brand-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <span>FreeDoctor Admin</span>
                </a>
            </div>
            
            <!-- Navigation Menu -->
            <nav class="sidebar-navigation">
                <!-- Main Dashboard -->
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home nav-icon"></i>
                        <span class="nav-text">Main Dashboard</span>
                    </a>
                </div>
                
                <!-- WhatsApp Manager Section -->
                <div class="section-header">
                    <i class="fab fa-whatsapp section-icon"></i>
                    <span>WhatsApp Manager</span>
                </div>
                
                <!-- WhatsApp Dashboard -->
                <div class="nav-item">
                    <a href="{{ route('admin.whatsapp.dashboard') }}" 
                       class="nav-link {{ (request()->routeIs('admin.whatsapp.dashboard') || (request()->is('admin/whatsapp') && !request()->is('admin/whatsapp/*'))) ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">WhatsApp Dashboard</span>
                    </a>
                </div>
                
                <!-- WhatsApp Subsections (only show when in WhatsApp area) -->
                @if(request()->routeIs('admin.whatsapp.*'))
                <div class="nav-submenu">
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.conversations') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.conversations*') ? 'active' : '' }}">
                            <i class="fas fa-comments nav-icon"></i>
                            <span class="nav-text">Live Conversations</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.automation') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.automation') && !request()->routeIs('admin.whatsapp.automation.*') ? 'active' : '' }}">
                            <i class="fas fa-robot nav-icon"></i>
                            <span class="nav-text">Automation Hub</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.workflows') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.workflows*') ? 'active' : '' }}">
                            <i class="fas fa-project-diagram nav-icon"></i>
                            <span class="nav-text">Workflows</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.templates') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.templates*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i>
                            <span class="nav-text">Templates</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.bulk-messages') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.bulk-messages*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane nav-icon"></i>
                            <span class="nav-text">Bulk Messages</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.analytics') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.analytics*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <span class="nav-text">Analytics</span>
                        </a>
                    </div>
                    
                    <!-- Settings Subsection -->
                    <div class="section-header" style="padding-top: 1rem; font-size: 0.625rem;">
                        <i class="fas fa-cog section-icon"></i>
                        <span>Settings</span>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.settings.ai-engine') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.settings.ai-engine') ? 'active' : '' }}">
                            <i class="fas fa-brain nav-icon"></i>
                            <span class="nav-text">AI Engine</span>
                        </a>
                    </div>
                    
                    <div class="nav-item">
                        <a href="{{ route('admin.whatsapp.settings') }}" 
                           class="nav-link {{ request()->routeIs('admin.whatsapp.settings') && !request()->routeIs('admin.whatsapp.settings.*') ? 'active' : '' }}">
                            <i class="fas fa-cog nav-icon"></i>
                            <span class="nav-text">General Settings</span>
                        </a>
                    </div>
                </div>
                @endif
                
                <!-- Back to Main Dashboard -->
                @if(request()->routeIs('admin.whatsapp.*'))
                <div class="section-header" style="padding-top: 1.5rem;">
                    <i class="fas fa-arrow-left section-icon"></i>
                    <span>Navigation</span>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="nav-link">
                        <i class="fas fa-arrow-left nav-icon"></i>
                        <span class="nav-text">Back to Dashboard</span>
                    </a>
                </div>
                @endif
                
                <!-- User Info -->
                <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 1.25rem; background: rgba(31, 36, 40, 0.7); border-top: 1px solid rgba(136, 143, 150, 0.2);">
                    <div style="display: flex; align-items: center;">
                        <div style="width: 2rem; height: 2rem; background: rgba(136, 143, 150, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="color: #888f96; font-size: 0.875rem;"></i>
                        </div>
                        <div style="margin-left: 0.75rem; flex: 1;">
                            <p style="color: #ffffff; font-size: 0.875rem; font-weight: 500; margin: 0;">{{ auth('admin')->user()->name ?? 'Admin' }}</p>
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline" style="margin: 0;">
                                @csrf
                                <button type="submit" style="color: #94a3b8; font-size: 0.75rem; background: none; border: none; cursor: pointer; padding: 0;" 
                                        onmouseover="this.style.color='#888f96'" onmouseout="this.style.color='#94a3b8'">
                                    <i class="fas fa-sign-out-alt" style="margin-right: 0.25rem;"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="main-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <!-- Mobile menu toggle -->
                        <button id="mobile-menu-toggle" class="lg:hidden p-2 mobile-menu-btn mr-3 rounded-lg">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        
                        <div>
                            <h2 class="text-2xl font-bold">@yield('page-title', 'Dashboard')</h2>
                            @if(isset($breadcrumbs))
                                <nav class="text-sm breadcrumb mt-1">
                                    @foreach($breadcrumbs as $breadcrumb)
                                        @if(!$loop->last)
                                            <a href="{{ $breadcrumb['url'] }}" class="hover:text-white">{{ $breadcrumb['title'] }}</a>
                                            <span class="mx-2">/</span>
                                        @else
                                            <span class="text-white">{{ $breadcrumb['title'] }}</span>
                                        @endif
                                    @endforeach
                                </nav>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 header-actions">
                        <!-- Notifications -->
                        <button class="p-2 relative rounded-lg">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Settings -->
                        <button class="p-2 rounded-lg">
                            <i class="fas fa-cog text-lg"></i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <div class="fade-in">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-6 mt-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Global CSRF token setup for AJAX requests
        if (typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert, [role="alert"]');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- FreeDoctor Global Library -->
    <script src="{{ asset('js/freedoctor-global.js') }}"></script>
    <script src="{{ asset('js/campaign-functions.js') }}"></script>
    <script src="{{ asset('js/whatsapp-functions.js') }}"></script>
    <script src="{{ asset('js/admin-functions.js') }}"></script>
    <script src="{{ asset('js/complete-fix.js') }}"></script>
    
    <!-- Modern Admin Scripts -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.querySelector('.admin-sidebar');
            const backdrop = document.getElementById('mobile-backdrop');
            
            function toggleMobileMenu() {
                if (sidebar && backdrop) {
                    sidebar.classList.toggle('mobile-open');
                    backdrop.classList.toggle('active');
                }
            }
            
            function closeMobileMenu() {
                if (sidebar && backdrop) {
                    sidebar.classList.remove('mobile-open');
                    backdrop.classList.remove('active');
                }
            }
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', toggleMobileMenu);
            }
            
            if (backdrop) {
                backdrop.addEventListener('click', closeMobileMenu);
            }
            
            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeMobileMenu();
                    }
                });
            });
        });
        
        // Enhanced animations
        function animateOnScroll() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        }
        
        window.addEventListener('scroll', animateOnScroll);
        
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            const fadeElements = document.querySelectorAll('.fade-in');
            fadeElements.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'all 0.5s ease';
            });
            
            setTimeout(() => {
                animateOnScroll();
            }, 100);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
