{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="admin-id" content="{{ auth('admin')->id() }}">
  <title>@yield('title', 'FreeDoctor')</title>
  <meta name="description" content="@yield('description', 'Free medical camps platform')">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <!-- Notification System CSS -->
  <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
  
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .glass-effect {
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(10px);
      border-radius: 0.5rem;
      border: 1px solid rgba(139, 92, 246, 0.3);
      transition: box-shadow 0.3s ease;
    }
    .border {
  border: none !important;
box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
  border-radius: 8px; /* optional: rounded corners */
 /* optional: spacing inside */

}
/* small,span{
 color:rgba(223, 225, 228, 0.6)!important;
} */

    .card-hover:hover {
      box-shadow: 0 0 15px rgba(139, 92, 246, 0.7);
    }
  </style>
</head>
<body class="bg-slate-900 text-white min-h-screen font-sans">

<!-- Mobile Toggle Button -->
<button onclick="toggleSidebar()" class="fixed z-50 top-4 left-4 md:hidden bg-purple-700 p-2 rounded text-white shadow-lg">
  <i class="fas fa-bars text-lg"></i>
</button>

<!-- Layout Container -->
<div class="flex flex-col md:flex-row min-h-screen">
    @include('admin.partials.sidebar')
    <main class="flex-1 p-4 sm:p-8" >
      @yield('content')
    </main>
  </div>
  <script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
  }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>
<script>
    // Initialize Echo for real-time notifications
    @if(config('broadcasting.default') !== 'log')
    function initializeEcho() {
        try {
            // Check if all dependencies are loaded
            if (typeof window.Echo !== 'undefined' && typeof window.Pusher !== 'undefined') {
                // Use the global Echo that's already loaded from CDN
                console.log('✅ Echo already available from CDN for admin');
                return true;
            } else if (typeof window.Pusher !== 'undefined') {
                // Create new Echo instance using Pusher
                console.log('🔧 Creating new Echo instance for admin...');
                
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
                console.log('✅ Custom Echo instance created successfully for admin');
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
                                console.log('🔄 All Echo initialization attempts failed for admin');
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

<!-- Real-time Notification System -->
<script src="{{ asset('js/notifications.js') }}"></script>

<!-- Test Notification Button -->
<script>
// Add test notification functionality for admin
window.addEventListener('load', function() {
    // Add test button to admin dashboard if not exists
    if (!document.getElementById('test-notification-btn')) {
        const testBtn = document.createElement('button');
        testBtn.id = 'test-notification-btn';
        testBtn.className = 'btn btn-sm btn-info position-fixed';
        testBtn.style.cssText = `
            bottom: 20px;
            left: 20px;
            z-index: 999;
            padding: 8px 12px;
            border-radius: 6px;
            background: #17a2b8;
            color: white;
            border: none;
            font-size: 12px;
        `;
        testBtn.innerHTML = '<i class="fas fa-bell"></i> Test Notifications';
        testBtn.onclick = function() {
            if (window.fdNotifications) {
                // Test multiple notifications to see queue system
                window.fdNotifications.testNotification('business', 'New business request received from ABC Medical Center');
                setTimeout(() => window.fdNotifications.testNotification('proposal', 'Dr. Smith submitted a new campaign proposal'), 800);
                setTimeout(() => window.fdNotifications.testNotification('success', 'Campaign "Eye Care Drive" has been approved successfully'), 1600);
                setTimeout(() => window.fdNotifications.testNotification('error', 'Payment verification failed for Campaign ID: 12345'), 2400);
                setTimeout(() => window.fdNotifications.testNotification('info', 'System maintenance scheduled for tonight at 2 AM'), 3200);
            } else {
                alert('Notification system not initialized yet. Please wait a moment and try again.');
            }
        };
        document.body.appendChild(testBtn);
    }
});
</script>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
