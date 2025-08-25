<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title', 'WhatsApp Bot')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    @stack('styles')
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.conversation-history') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.conversation-history') }}">
                            <i class="fas fa-history"></i>
                            Conversation History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.flow-data*') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.flow-data') }}">
                            <i class="fas fa-project-diagram"></i>
                            Flow Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.bulk-messages*') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.bulk-messages') }}">
                            <i class="fas fa-paper-plane"></i>
                            Bulk Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.templates*') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.templates') }}">
                            <i class="fas fa-file-alt"></i>
                            Templates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.auto-replies*') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.auto-replies') }}">
                            <i class="fas fa-reply-all"></i>
                            Auto Replies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.whatsapp.chatgpt*') ? 'active' : '' }}" 
                           href="{{ route('admin.whatsapp.chatgpt') }}">
                            <i class="fas fa-robot"></i>
                            ChatGPT
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>
