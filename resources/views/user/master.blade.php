{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-id" content="{{ auth('user')->id() }}">
  
  <!-- SEO Meta Tags -->
  <title>@yield('title', 'FreeDoctor - Free Medical Camps & Healthcare Platform')</title>
  <meta name="description" content="@yield('description', 'FreeDoctor provides free medical camps and healthcare services worldwide. Join our platform for free health checkups, medical consultations, and community health initiatives.')">
  <meta name="keywords" content="Free, Free Doctor, Free Doctor World, medical camps, free healthcare, health checkups, medical consultations, community health, doctors, patients, healthcare platform, medical services, health initiatives">
  <meta name="author" content="FreeDoctor">
  <meta name="robots" content="index, follow">
  <meta name="language" content="English">
  <meta name="revisit-after" content="7 days">
  <meta name="distribution" content="global">
  <meta name="rating" content="general">
  
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="@yield('og_title', 'FreeDoctor - Free Medical Camps & Healthcare Platform')">
  <meta property="og:description" content="@yield('og_description', 'Join FreeDoctor for free medical camps, health checkups, and healthcare services worldwide. Connecting patients with doctors for better community health.')">
  <meta property="og:image" content="{{ asset('storage/PngVectordeisgn.png') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="FreeDoctor">
  <meta property="og:locale" content="en_US">
  
  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('twitter_title', 'FreeDoctor - Free Medical Camps & Healthcare Platform')">
  <meta name="twitter:description" content="@yield('twitter_description', 'Join FreeDoctor for free medical camps, health checkups, and healthcare services worldwide.')">
  <meta name="twitter:image" content="{{ asset('storage/PngVectordeisgn.png') }}">
  <meta name="twitter:creator" content="@freedoctor">
  <meta name="twitter:site" content="@freedoctor">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('storage/PngVectordeisgn.png') }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('storage/PngVectordeisgn.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('storage/PngVectordeisgn.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('storage/PngVectordeisgn.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('storage/PngVectordeisgn.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('storage/PngVectordeisgn.png') }}">
  
  <!-- Additional Meta Tags -->
  <meta name="theme-color" content="#3B82F6">
  <meta name="msapplication-navbutton-color" content="#3B82F6">
  <meta name="apple-mobile-web-app-status-bar-style" content="#3B82F6">
  <meta name="msapplication-TileColor" content="#3B82F6">
  <meta name="msapplication-TileImage" content="{{ asset('storage/PngVectordeisgn.png') }}">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="{{ url()->current() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  
  <!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>



  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <!-- Notification System CSS -->
  <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
  
 
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  
  <!-- Modern Glass Effect Styles -->
  <style>
    /* Global Border Dimming - Smart Override */
    * {
      --border-dim: rgba(148, 163, 184, 0.2) !important;
      --border-dim-hover: rgba(148, 163, 184, 0.4) !important;
      --border-dim-focus: rgba(59, 130, 246, 0.5) !important;
    }
    
    /* Global Text Decoration Removal */
    a, button, .btn, .nav-link, .dropdown-item, .logo-container, .sidebar-nav-item,
    .material-btn, .btn-modern, .language-btn, .mobile-menu-btn, .notification-btn {
      text-decoration: none !important;
    }
    
    a:hover, a:focus, a:active,
    button:hover, button:focus, button:active,
    .btn:hover, .btn:focus, .btn:active,
    .nav-link:hover, .nav-link:focus, .nav-link:active,
    .dropdown-item:hover, .dropdown-item:focus, .dropdown-item:active,
    .logo-container:hover, .logo-container:focus, .logo-container:active,
    .sidebar-nav-item:hover, .sidebar-nav-item:focus, .sidebar-nav-item:active,
    .material-btn:hover, .material-btn:focus, .material-btn:active,
    .btn-modern:hover, .btn-modern:focus, .btn-modern:active,
    .language-btn:hover, .language-btn:focus, .language-btn:active,
    .mobile-menu-btn:hover, .mobile-menu-btn:focus, .mobile-menu-btn:active,
    .notification-btn:hover, .notification-btn:focus, .notification-btn:active {
      text-decoration: none !important;
    }
    
    /* Notification Icon Styles */
    .notification-selector {
      position: relative;
      margin-right: 0.2rem;
    }
    
    .notification-btn {
      background: var(--surface-color);
      border: 1px solid rgba(56, 63, 69, 0.2);
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-primary);
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
      position: relative;
    }
    
    .notification-btn:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px var(--shadow-color);
    }
    
    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: #ef4444;
      color: white;
      font-size: 0.625rem;
      padding: 0.125rem 0.375rem;
      border-radius: 10px;
      min-width: 18px;
      text-align: center;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
    }
    
    /* Smart border overrides */
    .border, [class*="border-"], 
    .form-control, .form-select, .btn-outline,
    .table-bordered, .table-bordered td, .table-bordered th,
    .card, .modal-content, .dropdown-menu,
    .nav-tabs .nav-link, .pagination .page-link {
      border-color: var(--border-dim) !important;
    }
    
    .border:hover, [class*="border-"]:hover,
    .form-control:hover, .form-select:hover, .btn-outline:hover,
    .card:hover, .nav-tabs .nav-link:hover {
      border-color: var(--border-dim-hover) !important;
    }
    
    .form-control:focus, .form-select:focus,
    .nav-tabs .nav-link.active {
      border-color: var(--border-dim-focus) !important;
    }
    
    /* Modern Glass Effects */
    .glass-effect {
      background: rgba(15, 23, 42, 0.15);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid var(--border-dim) !important;
      box-shadow: 
        0 25px 45px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
      border-radius: 16px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-effect:hover {
      transform: translateY(-2px);
      border-color: var(--border-dim-hover) !important;
      box-shadow: 
        0 35px 60px rgba(0, 0, 0, 0.15),
        0 0 0 1px rgba(255, 255, 255, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    
    /* Modern Card Hover Effects */
    .card-hover {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    
    .card-hover::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transition: left 0.6s ease;
    }
    
    .card-hover:hover::before {
      left: 100%;
    }
    
    .card-hover:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 
        0 20px 40px rgba(59, 130, 246, 0.3),
        0 0 0 1px rgba(59, 130, 246, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }
    
    /* Enhanced Sidebar Navigation */
    .sidebar-nav-item {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-nav-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 3px;
      height: 100%;
      background: linear-gradient(135deg, #60a5fa, #a78bfa);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    
    .sidebar-nav-item:hover::before {
      transform: scaleY(1);
    }
    
    .sidebar-nav-item:hover {
      transform: translateX(6px);
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(15px);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .sidebar-nav-icon {
      transition: all 0.3s ease;
    }
    
    .sidebar-nav-item:hover .sidebar-nav-icon {
      transform: scale(1.1);
      color: #60a5fa;
    }
    
    /* Modern Text Transitions */
    .sidebar-text-transition {
      transition: all 0.3s ease;
    }
    
    /* Background Gradient */
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
      min-height: 100vh;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    /* Modern Button Styles */
    .btn-modern {
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
      border: none;
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      color: white;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    .btn-modern::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, #60a5fa, #a78bfa);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .btn-modern:hover::before {
      opacity: 1;
    }
    
    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    
    /* Animated Gradients */
    .gradient-text {
      background: linear-gradient(135deg, #60a5fa, #a78bfa, #ec4899);
      background-size: 200% 200%;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: gradient-shift 3s ease infinite;
    }
    
    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    
    /* Floating Animation */
    .floating {
      animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    /* Modern Input Styles */
    .input-modern {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 12px;
      padding: 0.75rem 1rem;
      color: white;
      transition: all 0.3s ease;
    }
    
    .input-modern:focus {
      outline: none;
      border-color: #60a5fa;
      box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
    }
    
    .input-modern::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.3);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, #60a5fa, #a78bfa);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
      .glass-effect {
        margin: 0.5rem;
        border-radius: 12px;
      }
      
      .card-hover:hover {
        transform: translateY(-2px) scale(1.01);
      }
    }
    
    .sidebar-nav-item:hover .sidebar-nav-icon {
      transform: scale(1.1);
      background: rgba(255, 255, 255, 0.25);
    }
    
    /* Smooth text color transitions */
    .sidebar-text-transition {
      transition: color 0.3s ease;
    }
    
    /* Active state enhancements */
    .sidebar-nav-active {
      position: relative;
      overflow: hidden;
    }
    
    .sidebar-nav-active::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
      pointer-events: none;
    }
  </style>
  
  @stack('styles')
  @stack('css')


<style>
  .mobile-toggle-btn {
    position: fixed;
    z-index: 1001;
    top: 1rem;
    left: 1rem;
    background: linear-gradient(135deg, #8b5cf6, #6366f1);
    padding: 0.75rem;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 16px rgba(139, 92, 246, 0.3);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: none;
  }
  
  .mobile-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
  }
  
  @media (max-width: 768px) {
    .mobile-toggle-btn {
      display: block;
    }
  }
  
  .main-layout {
    display: flex;
    min-height: 100vh;
    position: relative;
  }
  
  .main-content {
    flex: 1;
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
    position: relative;
    overflow-x: auto;
  }
  
  .main-content::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
      radial-gradient(circle at 20% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(139, 92, 246, 0.1) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(16, 185, 129, 0.05) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
  }
  
  .content-wrapper {
    position: relative;
    z-index: 1;
    padding: 2rem;
    min-height: 100vh;
  }
  
  @media (max-width: 768px) {
    .content-wrapper {
      padding: 1rem;
      margin-left: 0;
    }
    
    .main-content {
      margin-left: 0;
    }
  }
</style>

    <link rel="stylesheet" href="{{ asset('css/user-theme.css') }}">
    <!-- Google Translate -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,bn,te,ta,gu,kn,ml,mr,or,pa,ur',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    @stack('styles')
    </style>
</head>

<body class="min-h-screen user-portal">






    <!-- Sticky Header -->
    <header class="header-sticky">
        <div class="header-container">
            <!-- Logo Section -->
               <!-- Mobile Menu Button -->
             

            <div class="logo-container">
                   <button class="mobile-menu-btn" id="mobileMenuBtn" style="margin-right:20px;">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('user.dashboard') }}" class="logo-container">
                    <div class="logo-icon">
                        <img src="{{ asset('storage/PngVectordeisgn.png') }}" class="w-8 h-8" alt="FreeDoctor Logo">
                    </div>
                    <div class="logo-text">
                        <h1>FreeDoctor</h1>
                        <p>Medical Camps </p>
                    </div>
                </a>
            </div>

            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Wallet Icon (Always Visible) -->
                <div class="notification-selector">
                    @auth('user')
                        <a href="{{ route('user.referral-dashboard') }}" class="notification-btn" id="userNotificationBtn">
                            <i class="fas fa-wallet"></i>
                            <!-- <span class="wallet-balance" id="userWalletBalance">0</span> -->
                        </a>
                    @else
                        <button class="notification-btn" id="userWalletLoginBtn" onclick="showWalletLoginPrompt('user')">
                            <i class="fas fa-wallet"></i>
                        </button>
                    @endauth
                </div>
                
                <!-- Language Selector -->
                <div class="language-selector">
                    <button class="language-btn" id="languageBtn">
                        <i class="fas fa-globe"></i>
   
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="language-dropdown" id="languageDropdown" style="display: none;">
                        <div id="google_translate_element"></div>
                    </div>
                </div>

              
               
            </div>
        </div>
    </header>

    <div class="app-layout">
        <!-- Mobile Sidebar Overlay -->
        

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            @include('user.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Scripts section removed -->
    <!-- Scripts -->
  <script>


        document.addEventListener('DOMContentLoaded', function() {
            // Mobile/Desktop Sidebar Toggle
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const desktopMenuBtn = document.getElementById('desktopMenuBtn');

            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-open');
                sidebarOverlay.classList.toggle('active');
                
                if (window.innerWidth <= 768) {
                    // Mobile behavior
                    document.body.style.overflow = sidebar.classList.contains('sidebar-open') ? 'hidden' : '';
                } else {
                    // Desktop behavior - adjust main content margin
                    const mainContent = document.querySelector('.main-content');
                    if (sidebar.classList.contains('sidebar-open')) {
                        mainContent.style.marginLeft = '280px';
                    } else {
                        mainContent.style.marginLeft = '0';
                    }
                }
            }

            mobileMenuBtn?.addEventListener('click', toggleSidebar);
            desktopMenuBtn?.addEventListener('click', toggleSidebar);
            
            sidebarOverlay?.addEventListener('click', function() {
                sidebar.classList.remove('sidebar-open');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close sidebar on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('sidebar-open');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                    sidebar.style.transform = 'translateX(0)';
                    document.querySelector('.main-content').style.marginLeft = '280px';
                }
            });

            // Language Selector Toggle
            const languageBtn = document.getElementById('languageBtn');
            const languageDropdown = document.getElementById('languageDropdown');

            languageBtn?.addEventListener('click', function(e) {
                e.stopPropagation();
                const isVisible = languageDropdown.style.display === 'block';
                languageDropdown.style.display = isVisible ? 'none' : 'block';
            });

            document.addEventListener('click', function() {
                if (languageDropdown) {
                    languageDropdown.style.display = 'none';
                }
            });

            // Google Translate Enhancement
            function checkGoogleTranslateReady() {
                const translateElement = document.querySelector('#google_translate_element select');
                if (translateElement) {
                    translateElement.addEventListener('change', function() {
                        const selectedText = this.options[this.selectedIndex].text;
                        const currentLangElement = document.getElementById('currentLang');
                        if (currentLangElement) {
                            currentLangElement.textContent = selectedText;
                        }
                        if (languageDropdown) {
                            languageDropdown.style.display = 'none';
                        }
                    });
                } else {
                    setTimeout(checkGoogleTranslateReady, 500);
                }
            }
            checkGoogleTranslateReady();
        });

        // Expose user authentication status
        window.isUserLoggedIn = {{ auth('user')->check() ? 'true' : 'false' }};
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Cross Authentication Modal Script -->
    @if(session('cross_auth_error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const crossAuthData = @json(session('cross_auth_error'));
            
            Swal.fire({
                title: 'Already Logged In!',
                html: `
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🔒</div>
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                            You are currently logged in as <strong>${crossAuthData.current_user_name}</strong> 
                            in the <strong>${crossAuthData.current_portal} Portal</strong>.
                        </p>
                        <p style="color: #666; margin-bottom: 1.5rem;">
                            To access the <strong>${crossAuthData.intended_portal} Portal</strong>, 
                            please logout from your current session first.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🚪 Logout & Continue',
                cancelButtonText: '↩️ Stay Here',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to logout
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = crossAuthData.logout_route;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrfToken);
                    document.body.appendChild(form);
                    form.submit();
                }
                // If cancelled, user stays on current page
            });
        });
    </script>
    @endif

    <!-- Echo Configuration for Real-time Notifications -->
    <script>
        // Initialize Echo for real-time notifications
        @if(config('broadcasting.default') !== 'log')
        function initializeEcho() {
            try {
                // Check if all dependencies are loaded
                if (typeof window.Echo !== 'undefined' && typeof window.Pusher !== 'undefined') {
                    // Check if Echo is already an instance with channel method
                    if (typeof window.Echo.channel === 'function') {
                        console.log('✅ Echo instance already available from CDN for user');
                        return true;
                    } else {
                        // Echo is the constructor, create instance
                        console.log('🔧 Creating Echo instance from CDN constructor for user...');
                        
                        window.Echo = new window.Echo({
                            broadcaster: 'pusher',
                            key: '{{ config("broadcasting.connections.pusher.key") }}',
                            cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                            forceTLS: true,
                            encrypted: true
                        });
                        
                        console.log('✅ Echo instance created successfully for user');
                        return true;
                    }
                } else if (typeof window.Pusher !== 'undefined') {
                    // Create new Echo instance using Pusher
                    console.log('🔧 Creating new Echo instance for user...');
                    
                    // Set up Pusher first
                    window.Pusher.logToConsole = false; // Disable pusher logs
                    
                    // Create Echo instance
                    const echoInstance = {
                        pusher: new window.Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
                            cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
                            forceTLS: true,
                            encrypted: true
                        }),
                        channel: function(channelName) {
                            const channel = this.pusher.subscribe(channelName);
                            return {
                                listen: function(eventName, callback) {
                                    // Remove the dot prefix if present for Pusher
                                    const cleanEventName = eventName.startsWith('.') ? eventName.substring(1) : eventName;
                                    channel.bind(cleanEventName, callback);
                                    return this;
                                }
                            };
                        }
                    };
                    
                    window.Echo = echoInstance;
                    console.log('✅ Custom Echo instance created successfully for user');
                    return true;
                } else {
                    console.log('⚠️ Pusher not yet available, retrying...');
                    return false;
                }
            } catch (error) {
                console.log('❌ Echo initialization failed:', error);
                window.Echo = null;
                return false;
            }
        }

        // Wait for all scripts to load
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (!initializeEcho()) {
                    setTimeout(function() {
                        if (!initializeEcho()) {
                            setTimeout(function() {
                                if (!initializeEcho()) {
                                    console.log('🔄 All Echo initialization attempts failed for user, using polling fallback');
                                    window.Echo = null;
                                }
                            }, 3000);
                        }
                    }, 2000);
                }
            }, 1000);
        });
        @else
        console.log('📡 Broadcasting disabled in .env - Echo not initialized');
        window.Echo = null;
        @endif
    </script>

    <!-- Real-time Notification System for Users -->
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    
    @auth('web')
    <!-- User metadata for notification system -->
    <meta name="user-authenticated" content="true">
    <meta name="user-id" content="{{ auth()->id() }}">
    <script>
        // Initialize user notification system manually if auto-init didn't work
        $(document).ready(function() {
            if (!window.userNotificationSystem) {
                console.log('🔔 Manually initializing user notification system...');
                window.userNotificationSystem = new UserNotificationSystem();
            }
            
            // Make user ID available globally for the notification system
            window.userId = {{ auth()->id() }};
            window.auth = {
                user: {
                    id: {{ auth()->id() }},
                    name: '{{ auth()->user()->name ?? "User" }}'
                }
            };
        });
    </script>
    @endauth

    <!-- Referral Tracking System -->
    <script>
        // Referral Tracking System
        class ReferralTracker {
            constructor() {
                this.storageKey = 'fd_referral_code';
                this.init();
            }

            init() {
                // Check for referral parameters on page load
                this.checkForReferralParams();
                
                // Make referral functions globally available
                window.getReferralCode = () => this.getReferralCode();
                window.clearReferralCode = () => this.clearReferralCode();
                window.hasReferralCode = () => this.hasReferralCode();
            }

            checkForReferralParams() {
                const urlParams = new URLSearchParams(window.location.search);
                let referralCode = null;

                // Check for both 'ref' and 's' (encoded) parameters
                if (urlParams.has('ref')) {
                    referralCode = urlParams.get('ref');
                    console.log('🔗 Direct referral code found:', referralCode);
                } else if (urlParams.has('s')) {
                    // Decode the 's' parameter
                    try {
                        const encodedRef = urlParams.get('s');
                        const paddedRef = encodedRef.padEnd(Math.ceil(encodedRef.length / 4) * 4, '=');
                        referralCode = atob(paddedRef);
                        console.log('🔗 Decoded referral code found:', referralCode);
                    } catch (error) {
                        console.warn('❌ Failed to decode referral parameter:', error);
                    }
                }

                // Store referral code if found and user is not authenticated
                if (referralCode && !this.isUserAuthenticated()) {
                    this.storeReferralCode(referralCode);
                    console.log('💾 Stored referral code for future registration:', referralCode);
                    
                    // Show subtle notification that referral was captured
                    this.showReferralCapturedNotification();
                } else if (referralCode && this.isUserAuthenticated()) {
                    console.log('ℹ️ User already authenticated, referral not stored');
                }
            }

            storeReferralCode(code) {
                try {
                    const referralData = {
                        code: code,
                        timestamp: Date.now(),
                        url: window.location.href,
                        expires: Date.now() + (7 * 24 * 60 * 60 * 1000) // 7 days
                    };
                    
                    localStorage.setItem(this.storageKey, JSON.stringify(referralData));
                    console.log('✅ Referral code stored successfully');
                } catch (error) {
                    console.error('❌ Failed to store referral code:', error);
                }
            }

            getReferralCode() {
                try {
                    const storedData = localStorage.getItem(this.storageKey);
                    if (!storedData) return null;

                    const referralData = JSON.parse(storedData);
                    
                    // Check if expired
                    if (Date.now() > referralData.expires) {
                        this.clearReferralCode();
                        console.log('⏰ Referral code expired and cleared');
                        return null;
                    }

                    return referralData.code;
                } catch (error) {
                    console.error('❌ Failed to retrieve referral code:', error);
                    return null;
                }
            }

            clearReferralCode() {
                try {
                    localStorage.removeItem(this.storageKey);
                    console.log('🗑️ Referral code cleared');
                } catch (error) {
                    console.error('❌ Failed to clear referral code:', error);
                }
            }

            hasReferralCode() {
                return this.getReferralCode() !== null;
            }

            isUserAuthenticated() {
                // Check if user is authenticated (multiple methods for reliability)
                return document.querySelector('meta[name="user-authenticated"]') !== null ||
                       document.querySelector('meta[name="user-id"]')?.content !== '' ||
                       window.userId !== undefined;
            }

            showReferralCapturedNotification() {
                // Only show if SweetAlert2 is available
                if (typeof Swal !== 'undefined') {
                    // Small toast notification
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Referral Link Detected',
                        text: 'You\'ll get special benefits when you register!',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#e3f2fd',
                        iconColor: '#1976d2'
                    });
                } else {
                    // Fallback: Simple console message
                    console.log('🎉 Referral benefits will be applied when you register!');
                }
            }
        }

        // Initialize referral tracker when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            window.referralTracker = new ReferralTracker();
            console.log('🚀 Referral tracking system initialized');
        });

        // Backup initialization for older browsers
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                if (!window.referralTracker) {
                    window.referralTracker = new ReferralTracker();
                }
            });
        } else {
            window.referralTracker = new ReferralTracker();
        }

        // Wallet Login Prompt Function
        function showWalletLoginPrompt(portalType) {
            Swal.fire({
                title: 'Wallet Access',
                html: `
                    <div style="text-align: center;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">💰</div>
                        <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                            Access your <strong>Wallet & Earnings</strong> to manage payments and rewards.
                        </p>
                        <p style="color: #666; margin-bottom: 1.5rem;">
                            Please login to view your wallet balance, transaction history, and earnings.
                        </p>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '🔐 Login Now',
                cancelButtonText: '↩️ Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to appropriate login page
                    if (portalType === 'user') {
                        window.location.href = '{{ route("user.login") }}';
                    } else if (portalType === 'doctor') {
                        window.location.href = '{{ route("doctor.login") }}';
                    }
                }
            });
        }
    </script>

    <!-- Core JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- FreeDoctor Global Libraries -->
    <script src="{{ asset('js/freedoctor-global.js') }}"></script>
    <script src="{{ asset('js/campaign-functions.js') }}"></script>
    <script src="{{ asset('js/admin-functions.js') }}"></script>
    <script src="{{ asset('js/complete-fix.js') }}"></script>

    @stack('scripts')
</body>
</html>