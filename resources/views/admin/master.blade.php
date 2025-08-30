<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-id" content="{{ auth('admin')->id() }}">
    <title>@yield('title', 'FreeDoctor Admin')</title>
    <meta name="description" content="@yield('description', 'Free medical camps admin platform')">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .sidebar-transition { transition: all 0.3s ease; }
        .content-shift { margin-left: 250px; }
        .content-full { margin-left: 0; }
        @media (max-width: 768px) {
            .content-shift { margin-left: 0; }
        }
        
        /* Fix sidebar positioning and scrolling */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Ensure main content has proper margin for sidebar */
        #main-content {
            margin-left: 280px;
            min-height: 100vh;
            width: calc(100% - 280px);
        }
        
        /* Modal z-index fixes - Ensure modals appear above sidebar */
        .swal2-container {
            z-index: 10000 !important;
        }
        
        .swal2-overlay {
            z-index: 9999 !important;
        }
        
        /* Fix for Bootstrap modals */
        .modal {
            z-index: 9998 !important;
        }
        
        .modal-backdrop {
            z-index: 9997 !important;
        }
        
        /* Ensure dropdowns appear correctly */
        .dropdown-menu {
            z-index: 1050;
        }
        
        /* Fix for any custom modals */
        .fixed.inset-0 {
            z-index: 9996 !important;
        }
        
        @media (max-width: 768px) {
            #main-content {
                margin-left: 0;
                width: 100%;
            }
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            #sidebar.open {
                transform: translateX(0);
            }
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
    <div id="sidebar" class="bg-white shadow-lg">
        @include('admin.partials.sidebar')
    </div>

    <!-- Mobile Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black opacity-50 z-30 hidden md:hidden"></div>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Page Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobile-menu-toggle');
            const sidebar = document.querySelector('.sidebar-modern');
            const overlay = document.getElementById('mobile-overlay');
            
            if (mobileToggle && sidebar) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                    if (overlay) {
                        overlay.classList.toggle('hidden');
                    }
                });
            }

            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    if (sidebar) {
                        sidebar.classList.remove('open');
                    }
                    overlay.classList.add('hidden');
                });
            }

            // Admin dropdown toggle (if exists)
            const adminToggle = document.getElementById('admin-menu-toggle');
            const adminDropdown = document.getElementById('admin-dropdown');
            
            if (adminToggle && adminDropdown) {
                adminToggle.addEventListener('click', function() {
                    adminDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!adminToggle.contains(event.target)) {
                        adminDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <!-- Laravel Echo -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

    <script>
        // Initialize Echo for real-time notifications
        window.Pusher = Pusher;
        
        try {
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ config("broadcasting.connections.pusher.key") }}',
                cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                forceTLS: true,
                encrypted: true
            });
            
            console.log('✅ Echo initialized successfully');
        } catch (error) {
            console.warn('⚠️ Echo initialization failed:', error);
            // Create a fallback Echo object to prevent errors
            window.Echo = {
                channel: function(channelName) {
                    console.warn('Echo fallback: channel method called for', channelName);
                    return {
                        listen: function(event, callback) {
                            console.warn('Echo fallback: listen method called for', event);
                            return this;
                        }
                    };
                },
                private: function(channelName) {
                    console.warn('Echo fallback: private method called for', channelName);
                    return {
                        listen: function(event, callback) {
                            console.warn('Echo fallback: listen method called for', event);
                            return this;
                        }
                    };
                }
            };
        }
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
