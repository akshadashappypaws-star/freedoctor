{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="doctor-id" content="{{ auth('doctor')->id() }}">
  
  <!-- SEO Meta Tags -->
  <title>@yield('title', 'FreeDoctor - Doctor Portal | Free Medical Camps & Healthcare Platform')</title>
  <meta name="description" content="@yield('description', 'FreeDoctor Doctor Portal - Join our platform to provide free medical services, participate in health camps, and serve communities worldwide.')">
  <meta name="keywords" content="Free, Free Doctor, Free Doctor World, doctor portal, medical professionals, free medical services, health camps, medical volunteers, healthcare platform, community service, medical consultation, doctors registration">
  <meta name="author" content="FreeDoctor">
  <meta name="robots" content="index, follow">
  <meta name="language" content="English">
  <meta name="revisit-after" content="7 days">
  <meta name="distribution" content="global">
  <meta name="rating" content="general">
  
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="@yield('og_title', 'FreeDoctor - Doctor Portal | Free Medical Camps & Healthcare Platform')">
  <meta property="og:description" content="@yield('og_description', 'Join FreeDoctor as a medical professional to provide free healthcare services and participate in community health initiatives worldwide.')">
  <meta property="og:image" content="{{ asset('storage/PngVectordeisgn.png') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="FreeDoctor">
  <meta property="og:locale" content="en_US">
  
  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('twitter_title', 'FreeDoctor - Doctor Portal | Free Medical Camps & Healthcare')">
  <meta name="twitter:description" content="@yield('twitter_description', 'Join FreeDoctor as a medical professional to provide free healthcare services worldwide.')">
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
  <meta name="theme-color" content="#383F45">
  <meta name="msapplication-navbutton-color" content="#383F45">
  <meta name="apple-mobile-web-app-status-bar-style" content="#383F45">
  <meta name="msapplication-TileColor" content="#383F45">
  <meta name="msapplication-TileImage" content="{{ asset('storage/PngVectordeisgn.png') }}">
  
  <!-- Canonical URL -->
  <link rel="canonical" href="{{ url()->current() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  
  <!-- Notification System CSS -->
  <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
  
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> -->
   <link rel="stylesheet" href="{{ asset('css/user-theme.css') }}">
   <link rel="stylesheet" href="{{ asset('css/doctor-theme.css') }}">
  <!-- Modern Glass Effect Styles -->
  <style>
    /* CSS Variables */
    :root {
      --header-height: 80px !important;
      --primary-color: #383F45 !important;
      --secondary-color: #E7A51B !important;
      --background-color: #f5f5f5 !important;
      --surface-color: #ffffff !important;
      --text-primary: #212121 !important;
      --text-secondary: #757575 !important;
      --shadow-color: rgba(0, 0, 0, 0.12) !important;
      --accent-color: #F7C873 !important;
      --success-color: #4CAF50 !important;
      --danger-color: #E53935 !important;
      --border-radius: 16px !important;
      --sidebar-width: 260px !important;
    }
    
    /* Global Border Dimming - Smart Override */
    * {
      --border-dim: rgba(148, 163, 184, 0.2) !important;
      --border-dim-hover: rgba(148, 163, 184, 0.4) !important;
      --border-dim-focus: rgba(59, 130, 246, 0.5) !important;
    }
    
    /* Global Text Decoration Removal */
    a, button, .btn, .nav-link, .dropdown-item, .logo-container, .sidebar-nav-item, .notification-btn {
      text-decoration: none !important;
    }
    
    a:hover, a:focus, a:active,
    button:hover, button:focus, button:active,
    .btn:hover, .btn:focus, .btn:active,
    .nav-link:hover, .nav-link:focus, .nav-link:active,
    .dropdown-item:hover, .dropdown-item:focus, .dropdown-item:active,
    .logo-container:hover, .logo-container:focus, .logo-container:active,
    .sidebar-nav-item:hover, .sidebar-nav-item:focus, .sidebar-nav-item:active,
    .notification-btn:hover, .notification-btn:focus, .notification-btn:active {
      text-decoration: none !important;
    }
    
    /* Notification Icon Styles */
    .notification-selector {
      position: relative;
      
    }
    
    .notification-btn {
      background: var(--surface-color);
      border: 1px solid rgba(56, 63, 69, 0.2);
      border-radius: 50%;
      width: 30px;
      height: 30px;
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
    .glass-effect, .glass-card {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(56, 63, 69, 0.1);
      box-shadow: 
        0 8px 32px var(--shadow-color),
        0 0 0 1px rgba(56, 63, 69, 0.05),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
      border-radius: var(--border-radius);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .glass-effect:hover, .glass-card:hover {
      transform: translateY(-2px);
      border-color: rgba(231, 165, 27, 0.3);
      box-shadow: 
        0 16px 48px var(--shadow-color),
        0 0 0 1px rgba(231, 165, 27, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.9);
    }
    
    /* Card styling for consistency */
    .card {
      background: var(--surface-color) !important;
      border: 1px solid rgba(56, 63, 69, 0.1);
      border-radius: var(--border-radius);
      box-shadow: 0 4px 16px var(--shadow-color);
      transition: all 0.3s ease;
      color: var(--text-primary) !important;
    }
    
    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 32px var(--shadow-color);
      border-color: rgba(231, 165, 27, 0.2);
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
      background: linear-gradient(90deg, transparent, rgba(231, 165, 27, 0.1), transparent);
      transition: left 0.6s ease;
    }
    
    .card-hover:hover::before {
      left: 100%;
    }
    
    .card-hover:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 
        0 20px 40px var(--shadow-color),
        0 0 0 1px rgba(231, 165, 27, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
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
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
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
      color: var(--accent-color);
    }
    
    /* Modern Text Transitions */
    .sidebar-text-transition {
      transition: all 0.3s ease;
    }
    
    /* Background Gradient */
    body {
      background: var(--background-color) !important;
      min-height: 100vh;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text-primary) !important;
    }
    
    /* Modern Button Styles */
    .btn-modern {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
      border: none;
      border-radius: 12px;
      padding: 0.75rem 1.5rem;
      color: white !important;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 16px var(--shadow-color);
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
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .btn-modern:hover::before {
      opacity: 1;
    }
    
    .btn-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px var(--shadow-color);
    }
    
    /* Animated Gradients */
    .gradient-text {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color), var(--accent-color)) !important;
      background-size: 200% 200% !important;
      -webkit-background-clip: text !important;
      -webkit-text-fill-color: transparent !important;
      background-clip: text !important;
      animation: gradient-shift 3s ease infinite !important;
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
      background: var(--surface-color) !important;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(56, 63, 69, 0.2);
      border-radius: 12px;
      padding: 0.75rem 1rem;
      color: var(--text-primary) !important;
      transition: all 0.3s ease;
    }
    
    .input-modern:focus {
      outline: none;
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }
    
    .input-modern::placeholder {
      color: var(--text-secondary) !important;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }
    
    ::-webkit-scrollbar-track {
      background: rgba(56, 63, 69, 0.1);
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
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
      
      /* Enhanced Mobile Responsive Fixes */
      .p-6 {
        padding: 1rem !important;
      }
      
      .space-y-6 > * + * {
        margin-top: 1rem !important;
      }
      
      .space-y-8 > * + * {
        margin-top: 1.5rem !important;
      }
      
      /* Mobile Button Container Fixes */
      .flex.space-x-3 {
        flex-direction: column !important;
        gap: 0.75rem !important;
        width: 100% !important;
      }
      
      .flex.space-x-3 > * {
        width: 100% !important;
        text-align: center !important;
      }
      
      /* Mobile Header Button Fixes */
      .flex.flex-col.md\:flex-row {
        flex-direction: column !important;
        gap: 1rem !important;
      }
      
      .flex.flex-col.md\:flex-row.justify-between {
        text-align: center !important;
      }
      
      /* Mobile Text Sizing */
      .text-3xl {
        font-size: 1.75rem !important;
      }
      
      .text-2xl {
        font-size: 1.5rem !important;
      }
      
      /* Mobile Notification Cards */
      .bg-gradient-to-r {
        padding: 1rem !important;
        border-radius: 8px !important;
        margin-bottom: 1rem !important;
      }
      
      .bg-gradient-to-r .text-3xl {
        font-size: 1.75rem !important;
      }
      
      .bg-gradient-to-r .text-sm {
        font-size: 0.8rem !important;
      }
      
      /* Mobile Grid Fixes */
      .grid.grid-cols-1.md\:grid-cols-2 {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
      }
      
      /* Mobile Table Container Fixes */
      .table-container .d-flex.justify-content-between {
        flex-direction: column !important;
        gap: 1rem !important;
        text-align: center !important;
      }
      
      /* Mobile Refresh Button Specific Fix */
      button[onclick*="refresh"],
      #refreshNotifications,
      .btn[onclick*="refresh"] {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8rem !important;
        min-height: 40px !important;
        white-space: nowrap !important;
      }
      
      /* Mobile Alert Fixes */
      .alert,
      .alert-error {
        margin: 0.5rem 0 !important;
        padding: 0.75rem !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
      }
    }
    
    .sidebar-nav-item:hover .sidebar-nav-icon {
      transform: scale(1.1);
      color: var(--accent-color);
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
      background: linear-gradient(45deg, rgba(231, 165, 27, 0.1), rgba(247, 200, 115, 0.05));
      pointer-events: none;
    }
    
    /* Text Utility Classes */
    .text-slate-300 {
      color: rgb(184, 183, 180) !important;
    }
    
    /* Table Theme Styles */
    .table {
      background: var(--surface-color) !important;
      color: var(--text-primary) !important;
      border-radius: var(--border-radius) !important;
      overflow: hidden !important;
      box-shadow: 0 4px 16px var(--shadow-color) !important;
      border: 1px solid rgba(56, 63, 69, 0.1) !important;
    }
    
    .table thead {
      background: linear-gradient(135deg, var(--primary-color), rgba(56, 63, 69, 0.9)) !important;
    }
    
    .table thead th {
      background: transparent !important;
      color: white !important;
      border: none !important;
      padding: 1rem !important;
      font-weight: 600 !important;
      font-size: 0.875rem !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
      border-bottom: 2px solid var(--secondary-color) !important;
    }
    
    .table tbody tr {
      background: var(--surface-color) !important;
      transition: all 0.3s ease !important;
      border: none !important;
    }
    
    .table tbody tr:nth-child(even) {
      background: rgba(56, 63, 69, 0.02) !important;
    }
    
    .table tbody tr:hover {
      background: rgba(231, 165, 27, 0.08) !important;
      transform: translateY(-1px) !important;
      box-shadow: 0 4px 12px rgba(231, 165, 27, 0.2) !important;
    }
    
    .table tbody td {
      background: transparent !important;
      color: var(--text-primary) !important;
      border: none !important;
      padding: 0.875rem 1rem !important;
      border-bottom: 1px solid rgba(56, 63, 69, 0.08) !important;
      vertical-align: middle !important;
    }
    
    .table tbody tr:last-child td {
      border-bottom: none !important;
    }
    
    /* DataTables Specific Styling */
    .dataTables_wrapper {
      background: var(--surface-color) !important;
      border-radius: var(--border-radius) !important;
      padding: 1.5rem !important;
      box-shadow: 0 4px 20px var(--shadow-color) !important;
      border: 1px solid rgba(56, 63, 69, 0.1) !important;
    }
    
    .dataTables_length select,
    .dataTables_filter input {
      background: var(--surface-color) !important;
      color: var(--text-primary) !important;
      border: 1px solid rgba(56, 63, 69, 0.2) !important;
      border-radius: 8px !important;
      padding: 0.5rem !important;
    }
    
    .dataTables_length select:focus,
    .dataTables_filter input:focus {
      outline: none !important;
      border-color: var(--secondary-color) !important;
      box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1) !important;
    }
    
    .dataTables_info {
      color: var(--text-secondary) !important;
      font-size: 0.875rem !important;
    }
    
    .dataTables_paginate .paginate_button {
      background: var(--surface-color) !important;
      color: var(--text-primary) !important;
      border: 1px solid rgba(56, 63, 69, 0.2) !important;
      border-radius: 6px !important;
      margin: 0 2px !important;
      padding: 0.5rem 0.75rem !important;
      transition: all 0.3s ease !important;
    }
    
    .dataTables_paginate .paginate_button:hover {
      background: var(--secondary-color) !important;
      color: white !important;
      border-color: var(--secondary-color) !important;
      transform: translateY(-1px) !important;
    }
    
    .dataTables_paginate .paginate_button.current {
      background: var(--primary-color) !important;
      color: white !important;
      border-color: var(--primary-color) !important;
    }
    
    .dataTables_paginate .paginate_button.disabled {
      background: rgba(56, 63, 69, 0.1) !important;
      color: var(--text-secondary) !important;
      border-color: rgba(56, 63, 69, 0.1) !important;
      cursor: not-allowed !important;
    }
    
    /* Table Action Buttons */
    .table .btn {
      padding: 0.375rem 0.75rem !important;
      font-size: 0.75rem !important;
      border-radius: 6px !important;
      transition: all 0.3s ease !important;
      margin: 0 2px !important;
    }
    
    .table .btn-primary {
      background: var(--primary-color) !important;
      border-color: var(--primary-color) !important;
      color: white !important;
    }
    
    .table .btn-primary:hover {
      background: var(--secondary-color) !important;
      border-color: var(--secondary-color) !important;
      transform: translateY(-1px) !important;
    }
    
    .table .btn-success {
      background: var(--success-color) !important;
      border-color: var(--success-color) !important;
      color: white !important;
    }
    
    .table .btn-danger {
      background: var(--danger-color) !important;
      border-color: var(--danger-color) !important;
      color: white !important;
    }
    
    .table .btn-warning {
      background: var(--secondary-color) !important;
      border-color: var(--secondary-color) !important;
      color: white !important;
    }
    
    /* Badge/Status Styling */
    .badge {
      padding: 0.375rem 0.75rem !important;
      border-radius: 6px !important;
      font-size: 0.75rem !important;
      font-weight: 600 !important;
    }
    
    .badge-success {
      background: var(--success-color) !important;
      color: white !important;
    }
    
    .badge-warning {
      background: var(--secondary-color) !important;
      color: white !important;
    }
    
    .badge-danger {
      background: var(--danger-color) !important;
      color: white !important;
    }
    
    .badge-primary {
      background: var(--primary-color) !important;
      color: white !important;
    }
    
    .badge-secondary {
      background: var(--text-secondary) !important;
      color: white !important;
    }
    
    /* Table Responsive */
    .table-responsive {
      border-radius: var(--border-radius) !important;
      box-shadow: 0 4px 16px var(--shadow-color) !important;
      border: 1px solid rgba(56, 63, 69, 0.1) !important;
    }
    
    /* Table Striped Alternative */
    .table-striped tbody tr:nth-of-type(odd) {
      background: rgba(56, 63, 69, 0.02) !important;
    }
    
    /* Table Bordered */
    .table-bordered {
      border: 1px solid rgba(56, 63, 69, 0.1) !important;
    }
    
    .table-bordered th,
    .table-bordered td {
      border: 1px solid rgba(56, 63, 69, 0.08) !important;
    }
    
    /* Search and Filter Inputs */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
      margin-bottom: 1rem !important;
    }
    
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
      color: var(--text-primary) !important;
      font-weight: 500 !important;
    }

    /* Doctor Portal Notification Positioning Override */
    #notification-container {
      position: fixed !important;
      top: calc(var(--header-height) + 20px) !important; /* 80px + 20px = 100px from top */
      right: 20px !important;
      bottom: auto !important;
      z-index: 999999 !important;
      max-width: 400px !important;
      pointer-events: none !important;
    }

    /* Responsive notification positioning for doctor portal */
    @media (max-width: 480px) {
      #notification-container {
        left: 10px !important;
        right: 10px !important;
        top: calc(var(--header-height) + 10px) !important; /* 80px + 10px = 90px from top */
        max-width: none !important;
      }
    }
  </style>
  
  @stack('styles')
  @stack('css')


<style>
  .mobile-toggle-btn {
    position: fixed;
    z-index: 1101; /* Higher than header */
    top: 1rem;
    left: 1rem;
    background: var(--primary-color);
    padding: 0.75rem;
    border-radius: 12px;
    color: white;
    box-shadow: 0 4px 16px rgba(56, 63, 69, 0.4);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: none;
  }
  
  .mobile-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(231, 165, 27, 0.5);
    background: var(--secondary-color);
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
  
  .app-layout {
    display: flex;
    min-height: 100vh;
    position: relative;
  }
  
  .main-content {
    flex: 1;
    min-height: 100vh;
    background: var(--background-color) !important;
    position: relative;
    overflow-x: auto;
    margin-left: 0; /* No margin since sidebar is closed by default */
    z-index: 1;
    transition: margin-left 0.3s ease;
  }
  
  .main-content.sidebar-open {
    margin-left: 280px; /* Add margin when sidebar is open */
  }
  
  .main-content::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
      radial-gradient(circle at 20% 80%, rgba(231, 165, 27, 0.05) 0%, transparent 50%),
      radial-gradient(circle at 80% 20%, rgba(56, 63, 69, 0.08) 0%, transparent 50%),
      radial-gradient(circle at 40% 40%, rgba(247, 200, 115, 0.03) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
  }
  
  .content-wrapper {
    position: relative;
    z-index: 1;
    min-height: calc(100vh - var(--header-height));
    margin-top: var(--header-height); /* Push content below header */
    background: var(--surface-color) !important;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 20px var(--shadow-color);
    color: var(--text-primary) !important;
  }
  
  /* Header Styles */
  .header-sticky {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: var(--header-height);
    z-index: 1100; /* Higher than sidebar */
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(56, 63, 69, 0.1);
    box-shadow: 0 2px 20px var(--shadow-color);
    display: flex;
    align-items: center;
    z-index: 3;
  }
  
  .header-container {
    width: 100%;
    padding: 0 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 100%;
  }
  
  /* Logo Styling */
  .logo-container {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
  }
  
  .logo-container:hover {
    color: var(--primary-color);
    transform: translateY(-1px);
  }
  
  .logo-icon {
    width: 3rem;
    height: 3rem;
    background: transparent;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }
  
  .logo-icon:hover {
    transform: scale(1.05);
  }
  
  .logo-text h1 {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
    color: var(--primary-color);
  }
  
  .logo-text p {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 500;
  }
  
  /* Header Actions */
  .header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .language-selector {
    position: relative;
  }
  
  .language-btn {
    background: var(--surface-color);
    border: 1px solid rgba(56, 63, 69, 0.2);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
  }
  
  .language-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px var(--shadow-color);
  }
  
  .language-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: var(--surface-color);
    border: 1px solid rgba(56, 63, 69, 0.2);
    border-radius: 8px;
    box-shadow: 0 8px 32px var(--shadow-color);
    z-index: 1200;
    margin-top: 0.5rem;
    min-width: 200px;
  }
  
  .mobile-menu-btn {
    background: var(--primary-color) !important;
    border: none;
    color: white !important;
    padding: 0.75rem;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 1rem;
  }
  
  .mobile-menu-btn:hover {
    background: var(--secondary-color) !important;
    transform: scale(1.1);
  }
  
  /* Sidebar Styles */
  .sidebar {
    position: fixed;
    top: var(--header-height); /* Start below header */
    left: -280px; /* Hidden by default */
    width: 280px;
    height: calc(100vh - var(--header-height));
    z-index: 1050; /* Lower than header but higher than content */
    background: var(--primary-color) !important;
    transition: left 0.3s ease;
  }
  
  .sidebar.sidebar-open {
    left: 0; /* Slide in when open */
  }
  
  /* Sidebar Overlay */
  .sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(56, 63, 69, 0.7);
    z-index: 1049; /* Below sidebar but above content */
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
    pointer-events: none;
  }
  
  .sidebar-overlay.active {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
  }
  
  @media (max-width: 768px) {
    .sidebar {
      left: -280px; /* Keep hidden on mobile */
    }
    
    .sidebar.sidebar-open {
      left: 0;
    }
    
    .main-content {
      margin-left: 0 !important;
    }
    
    .content-wrapper {
      border-radius: 12px;
    }
    
    /* Mobile Logo Text Styling */
    .logo-text h1 {
      font-size: 1.1rem !important;
    }
    
    .logo-text p {
      font-size: 0.6rem !important;
    }
  }
</style>

   
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

<body class="min-h-screen">






    <!-- Sticky Header -->
    <header class="header-sticky">
        <div class="header-container">
            <!-- Logo Section -->
               <!-- Mobile Menu Button -->
             

         
            <div class="logo-container">
                   <button class="mobile-menu-btn" id="mobileMenuBtn" style="margin-right:20px!important;">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('doctor.dashboard') }}" class="logo-container">
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
                    @auth('doctor')
                        <a href="{{ route('doctor.wallet') }}" class="notification-btn" id="doctorNotificationBtn">
                            <i class="fas fa-wallet"></i>
                        </a>
                    @else
                        <button class="notification-btn" id="doctorWalletLoginBtn" onclick="showWalletLoginPrompt('doctor')">
                            <i class="fas fa-wallet"></i>
                        </button>
                    @endauth
                </div>
                
                <!-- Language Selector -->
                <div class="language-selector">
                    <button class="language-btn" id="languageBtn">
                        <i class="fas fa-globe"></i>
                        <!-- <span id="currentLang">English</span> -->
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
        <div id="sidebarOverlay" class="sidebar-overlay"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            @include('doctor.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
                @yield('content')
            </div>
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
                
                // Toggle overlay for mobile
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                if (sidebar.classList.contains('sidebar-open')) {
                    sidebarOverlay.classList.add('active');
                } else {
                    sidebarOverlay.classList.remove('active');
                }
                
                // Toggle main content margin for desktop
                const mainContent = document.querySelector('.main-content');
                if (window.innerWidth > 768) {
                    if (sidebar.classList.contains('sidebar-open')) {
                        mainContent.classList.add('sidebar-open');
                    } else {
                        mainContent.classList.remove('sidebar-open');
                    }
                }
                
                if (window.innerWidth <= 768) {
                    // Mobile behavior
                    document.body.style.overflow = sidebar.classList.contains('sidebar-open') ? 'hidden' : '';
                }
            }

            mobileMenuBtn?.addEventListener('click', toggleSidebar);
            desktopMenuBtn?.addEventListener('click', toggleSidebar);
            
            sidebarOverlay?.addEventListener('click', function() {
                sidebar.classList.remove('sidebar-open');
                this.classList.remove('active');
                document.querySelector('.main-content').classList.remove('sidebar-open');
                document.body.style.overflow = '';
            });

            // Close sidebar on window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('sidebar-open');
                    document.getElementById('sidebarOverlay').classList.remove('active');
                    document.querySelector('.main-content').classList.remove('sidebar-open');
                    document.body.style.overflow = '';
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
                    // Use the global Echo that's already loaded from CDN
                    console.log('✅ Echo already available from CDN for doctor');
                    return true;
                } else if (typeof window.Pusher !== 'undefined') {
                    // Create new Echo instance using Pusher
                    console.log('🔧 Creating new Echo instance for doctor...');
                    
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
                    console.log('✅ Custom Echo instance created successfully for doctor');
                    return true;
                } else {
                    console.log('⚠️ Pusher not yet available, retrying...');
                    return false;
                }
            } catch (error) {
                console.log('❌ Echo initialization failed for doctor:', error);
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
                                    console.log('🔄 All Echo initialization attempts failed for doctor, using polling fallback');
                                    window.Echo = null;
                                }
                            }, 3000);
                        }
                    }, 2000);
                }
            }, 1000);
        });
        @else
        console.log('📡 Broadcasting disabled in .env - Echo not initialized for doctor');
        window.Echo = null;
        @endif

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

    <!-- Real-time Notification System -->
    <script src="{{ asset('js/notifications.js') }}"></script>

    @stack('scripts')
</body>
</html>