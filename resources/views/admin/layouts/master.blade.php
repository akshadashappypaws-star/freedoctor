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
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
            transition: all 0.3s ease;
        }
        
        .sidebar-item.active {
            background: rgba(255, 255, 255, 0.2);
            border-right: 4px solid #ffffff;
        }
        
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
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 sidebar-gradient shadow-xl flex-shrink-0">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 bg-black bg-opacity-20">
                    <h1 class="text-white text-xl font-bold">
                        <i class="fas fa-user-md mr-2"></i>FreeDoctor Admin
                    </h1>
                </div>
                
                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <!-- Main Admin Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5 mr-3"></i>
                        <span>Main Dashboard</span>
                    </a>
                    
                    <!-- WhatsApp Manager Section -->
                    <div class="pt-4">
                        <h3 class="px-4 text-xs font-semibold text-gray-300 uppercase tracking-wider mb-3">
                            WhatsApp Manager
                        </h3>
                        
                        <a href="{{ route('admin.whatsapp.dashboard') }}" 
                           class="sidebar-item flex items-center px-4 py-3 text-white rounded-lg {{ request()->routeIs('admin.whatsapp.*') ? 'active' : '' }}">
                            <i class="fab fa-whatsapp w-5 mr-3"></i>
                            <span>WhatsApp Agent Manager</span>
                            @if(request()->routeIs('admin.whatsapp.*'))
                                <i class="fas fa-chevron-right ml-auto"></i>
                            @endif
                        </a>
                    </div>
                </nav>
                
                <!-- User Info -->
                <div class="px-4 py-4 bg-black bg-opacity-20">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-white text-sm font-medium">{{ auth('admin')->user()->name ?? 'Admin' }}</p>
                            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-300 text-xs hover:text-white">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                        @if(isset($breadcrumbs))
                            <nav class="text-sm text-gray-500 mt-1">
                                @foreach($breadcrumbs as $breadcrumb)
                                    @if(!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="hover:text-gray-700">{{ $breadcrumb['title'] }}</a>
                                        <span class="mx-2">/</span>
                                    @else
                                        <span class="text-gray-900">{{ $breadcrumb['title'] }}</span>
                                    @endif
                                @endforeach
                            </nav>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- Settings -->
                        <button class="p-2 text-gray-400 hover:text-gray-600">
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
    
    @stack('scripts')
</body>
</html>
