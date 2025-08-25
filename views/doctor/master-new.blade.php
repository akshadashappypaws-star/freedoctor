<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- SEO Meta Tags -->
  <title>@yield('title', 'Doctor Dashboard - FreeDoctor | Free Medical Camps & Healthcare Platform')</title>
  <meta name="description" content="@yield('description', 'FreeDoctor Doctor Dashboard - Manage your medical services, participate in health camps, and serve communities worldwide.')">
  <meta name="keywords" content="Free, Free Doctor, Free Doctor World, doctor dashboard, medical professionals, free medical services, health camps, medical volunteers, healthcare platform, community service, medical consultation, doctors portal">
  <meta name="author" content="FreeDoctor">
  <meta name="robots" content="index, follow">
  <meta name="language" content="English">
  <meta name="revisit-after" content="7 days">
  <meta name="distribution" content="global">
  <meta name="rating" content="general">
  
  <!-- Open Graph Meta Tags -->
  <meta property="og:title" content="@yield('og_title', 'Doctor Dashboard - FreeDoctor | Free Medical Camps & Healthcare Platform')">
  <meta property="og:description" content="@yield('og_description', 'FreeDoctor Doctor Dashboard - Manage your medical services and participate in community health initiatives worldwide.')">
  <meta property="og:image" content="{{ asset('storage/PngVectordeisgn.png') }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="FreeDoctor">
  <meta property="og:locale" content="en_US">
  
  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('twitter_title', 'Doctor Dashboard - FreeDoctor | Free Medical Camps')">
  <meta name="twitter:description" content="@yield('twitter_description', 'FreeDoctor Doctor Dashboard - Manage your medical services and serve communities worldwide.')">
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

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    .glass-effect {
      background: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(10px);
      border-radius: 0.5rem;
      border: 1px solid rgba(59, 130, 246, 0.3);
      transition: box-shadow 0.3s ease;
    }
    .card-hover:hover {
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.7);
    }
  </style>

  <!-- Additional CSS -->
  @stack('css')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen font-sans">

<!-- Mobile Toggle Button -->
<button onclick="toggleSidebar()" class="fixed z-50 top-4 left-4 md:hidden bg-blue-600 p-2 rounded text-white shadow-lg">
  <i class="fas fa-bars text-lg"></i>
</button>

<!-- Layout Container -->
<div class="flex flex-col md:flex-row min-h-screen">
    @include('doctor.partials.sidebar')
    <main class="flex-1 p-4 sm:p-8 bg-gray-50" >
      @yield('content')
    </main>
  </div>
  <script>
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
  }
</script>

<!-- Additional JS -->
@stack('js')

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
