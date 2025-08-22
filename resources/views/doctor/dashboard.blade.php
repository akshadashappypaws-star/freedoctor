{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'FreeDoctor')</title>
  <meta name="description" content="@yield('description', 'Free medical camps platform')">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  
  <!-- Modern Glass Effect Styles -->
  <style>
    /* Global Border Dimming - Smart Override */
    * {
      --border-dim: rgba(148, 163, 184, 0.2) !important;
      --border-dim-hover: rgba(148, 163, 184, 0.4) !important;
      --border-dim-focus: rgba(59, 130, 246, 0.5) !important;
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
</head>
<body class="min-h-screen font-sans">

<!-- Mobile Toggle Button -->
<button onclick="toggleSidebar()" class="mobile-toggle-btn">
  <i class="fas fa-bars text-lg"></i>
</button>

<!-- Layout Container -->
<div class="main-layout">
    @include('doctor.partials.sidebar')
    <main class="main-content">
      <div class="content-wrapper">
        @yield('content')
      </div>
    </main>
</div>

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

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
      sidebar.classList.toggle('-translate-x-full');
      sidebar.classList.toggle('sidebar-open');
    }
  }
  
  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.mobile-toggle-btn');
    
    if (window.innerWidth <= 768 && sidebar && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
      sidebar.classList.add('-translate-x-full');
      sidebar.classList.remove('sidebar-open');
    }
  });
  
  // Enhanced animations on load
  document.addEventListener('DOMContentLoaded', function() {
    // Add entrance animations to glass effect elements
    const glassElements = document.querySelectorAll('.glass-effect');
    glassElements.forEach((element, index) => {
      element.style.animationDelay = `${index * 0.1}s`;
      element.classList.add('animate-fade-in-up');
    });
  });
</script>

<!-- Bootstrap and DataTables JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Real-time Notification System -->
<script>
// Use Echo from master.blade.php if available, otherwise fallback to polling
@auth('doctor')
function setupDoctorNotifications() {
    if (typeof window.Echo !== 'undefined' && window.Echo !== null) {
        try {
            // Listen for doctor-specific notifications using properly initialized Echo
            window.Echo.channel('doctor.{{ auth("doctor")->id() }}')
                .listen('doctor-message.sent', function(data) {
                    console.log('🔔 Doctor notification received via Echo:', data);
                    
                    // Show toast notification
                    showInstantToast(data.message.message, getDoctorNotificationType(data.message.type));
                    
                    // Update badge count if exists
                    updateDoctorNotificationBadge();
                });
                
            console.log('✅ Echo channel subscribed for doctor {{ auth("doctor")->id() }}');
            return true;
        } catch (error) {
            console.log('❌ Echo subscription failed:', error);
            return false;
        }
    } else {
        console.log('⚠️ Echo not available for doctor notifications');
        return false;
    }
}

// Initialize notifications after a short delay to ensure Echo is ready
setTimeout(function() {
    if (!setupDoctorNotifications()) {
        console.log('🔄 Starting polling fallback for doctor notifications');
        startDoctorNotificationPolling();
    }
}, 2000);

// Also try on DOM ready in case the timeout wasn't enough
$(document).ready(function() {
    setTimeout(function() {
        if (typeof window.Echo === 'undefined' || window.Echo === null) {
            console.log('🔄 Echo still not available after DOM ready, using polling');
            startDoctorNotificationPolling();
        }
    }, 3000);
});
@endauth

function startDoctorNotificationPolling() {
    // Fallback: Poll for new notifications every 15 seconds
    setInterval(function() {
        checkForNewDoctorNotifications();
    }, 15000);
    console.log('📡 Doctor notification polling started (15 second intervals)');
}
</script>

<!-- Global Toast Notification Function for Doctors -->
<script>
function showInstantToast(message, type = 'info') {
    // Remove existing toasts
    $('.doctor-toast').remove();
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-yellow-500' : 
                   type === 'business_request' ? 'bg-blue-500' : 
                   'bg-blue-500';
    
    const icon = type === 'success' ? 'check' : 
                type === 'error' ? 'times' : 
                type === 'warning' ? 'exclamation' : 
                type === 'business_request' ? 'briefcase' : 
                'bell';
    
    const title = type === 'business_request' ? 'New Business Opportunity!' : 
                 type === 'success' ? 'Success!' : 
                 type === 'error' ? 'Alert!' : 
                 'Notification';
    
    const toast = $(`
        <div class="doctor-toast fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl z-50 transform translate-x-full transition-all duration-500 max-w-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-${icon} text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <div class="font-bold text-sm">${title}</div>
                    <div class="text-xs mt-1 opacity-90 line-clamp-3">${message}</div>
                    <div class="text-xs mt-2 opacity-75">Just now</div>
                </div>
                <button class="ml-2 text-white hover:text-gray-200 text-lg" onclick="$(this).closest('.doctor-toast').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Animate in
    setTimeout(() => toast.removeClass('translate-x-full'), 100);
    
    // Auto remove after 8 seconds
    setTimeout(() => {
        toast.addClass('translate-x-full');
        setTimeout(() => toast.remove(), 500);
    }, 8000);
    
    // Play notification sound if available
    try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCGH0+/WgCkEJoHO8daJOAgRaLvt555NEAxPqOHwtmMdBjiS2O/OeyoFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCKG0+/VgCkGJoLM8daJOQgRaL3t4Z5MEAxPpuHxtmQcBjiS2O/OeysFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwiBCGG0+/VfyoII4TL8taCOQkPbbzs4Z9OEAxOpuHytmQcBjaSWG1/PJGhSr4FH/4%3D');
        audio.volume = 0.3;
        audio.play().catch(() => {});
    } catch (e) {}
}

function getDoctorNotificationType(type) {
    switch(type) {
        case 'business_request': return 'business_request';
        case 'approval': return 'success';
        case 'rejection': return 'error';
        case 'payment': return 'success';
        default: return 'info';
    }
}

function updateDoctorNotificationBadge() {
    // Update any notification badges in the interface
    const badges = $('.notification-count, .badge-notification');
    badges.each(function() {
        const current = parseInt($(this).text()) || 0;
        $(this).text(current + 1).show();
    });
}

// Fallback polling system for notifications (works without WebSocket)
@auth('doctor')
let lastNotificationCheck = new Date();

function checkForNewDoctorNotifications() {
    $.get('/doctor/notifications/check-new', function(data) {
        console.log('📡 Polling response:', data); // Debug log
        
        if (data.notifications && data.notifications.length > 0) {
            console.log('🔔 Found ' + data.notifications.length + ' new notifications via polling');
            
            data.notifications.forEach(function(notification) {
                console.log('📨 Showing toast for notification:', notification);
                showInstantToast(notification.message, getDoctorNotificationType(notification.type));
                updateDoctorNotificationBadge();
            });
            lastNotificationCheck = new Date();
        } else {
            console.log('👁️ No new notifications found via polling');
        }
    }).fail(function(xhr, status, error) {
        console.log('❌ Polling failed:', error);
    });
}

// Start polling every 15 seconds for new notifications
setInterval(checkForNewDoctorNotifications, 15000);
console.log('📡 Doctor notification polling started (15-second intervals)');

// Check immediately when page loads
$(document).ready(function() {
    setTimeout(function() {
        console.log('🚀 Initial notification check...');
        checkForNewDoctorNotifications();
    }, 3000); // Wait 3 seconds for page to fully load
});
@endauth
</script>

@stack('js')

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
