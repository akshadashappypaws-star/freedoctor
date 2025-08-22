  <aside id="sidebar" class="sidebar-modern">
    <!-- Branding -->
    <div class="branding-section">
        <div class="brand-icon">
            
            <img src="{{ asset('storage/PngVectordeisgn.png') }}" class="w-10 h-10" alt="FreeDoctor Logo" />
        </div>
        <div class="brand-text">
            <h1 class="brand-title">FreeDoctor</h1>
            <p class="brand-subtitle">Medical Camps</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navigation-section">
        <ul class="nav-list">
            @php
                $active = request()->segment(2);
                $menuItems = [
                    [
                        'route' => 'dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'tachometer-alt',
                        'color' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'route' => 'specification',
                        'label' => 'Specification',
                        'icon' => 'list',
                        'color' => 'from-indigo-500 to-indigo-600'
                    ],
                    [
                        'route' => 'doctors',
                        'label' => 'Doctors',
                        'icon' => 'user-md',
                        'color' => 'from-emerald-500 to-emerald-600'
                    ],
                    [
                        'route' => 'campaigns',
                        'label' => 'Campaigns',
                        'icon' => 'bullhorn',
                        'color' => 'from-purple-500 to-purple-600'
                    ],
                    [
                        'route' => 'campaign-sponsors',
                        'label' => 'Campaign Sponsors',
                        'icon' => 'hand-holding-usd',
                        'color' => 'from-yellow-500 to-orange-500'
                    ],
                    [
                        'route' => 'business-organization',
                        'label' => 'Business Organization',
                        'icon' => 'building',
                        'color' => 'from-teal-500 to-teal-600'
                    ],
             
                    [
                        'route' => 'doctor-proposals',
                        'label' => 'Doctor Proposals',
                        'icon' => 'paper-plane',
                        'color' => 'from-purple-500 to-indigo-500',
                       
                    ],
                    [
                        'route' => 'patients',
                        'label' => 'Patients',
                        'icon' => 'procedures',
                        'color' => 'from-cyan-500 to-blue-500'
                    ],
                    [
                        'route' => 'doctor-payments',
                        'label' => 'Doctor Payments',
                        'icon' => 'credit-card',
                        'color' => 'from-green-500 to-emerald-500'
                    ],
                    [
                        'route' => 'patient-payouts',
                        'label' => 'Patient Payouts',
                        'icon' => 'money-bill-wave',
                        'color' => 'from-indigo-500 to-purple-500'
                    ],
                    [
                        'route' => 'doctor-payouts',
                        'label' => 'Doctor Payouts',
                        'icon' => 'hand-holding-usd',
                        'color' => 'from-emerald-500 to-teal-500'
                    ],
                    [
                        'route' => 'doctor-verification',
                        'label' => 'Doctor Verification',
                        'icon' => 'check-circle',
                        'color' => 'from-blue-500 to-indigo-500'
                    ],
                    [
                        'route' => 'settings',
                        'label' => 'Settings',
                        'icon' => 'cogs',
                        'color' => 'from-gray-500 to-gray-600'
                    ],
                    [
                        'route' => 'leads',
                        'label' => 'Leads',
                        'icon' => 'user-plus',
                        'color' => 'from-orange-500 to-red-500'
                    ],
                    [
                        'route' => 'notifications',
                        'label' => 'Notifications',
                        'icon' => 'bell',
                        'color' => 'from-red-500 to-pink-500',
                        'badge' => isset($unreadNotifications) && $unreadNotifications > 0 ? $unreadNotifications : null
                    ],
                
                ];
            @endphp

            @foreach($menuItems as $item)
                <li class="nav-item">
                    <a href="{{ url('admin/' . $item['route']) }}" 
                       class="nav-link {{ $active === $item['route'] ? 'nav-link-active' : '' }}"
                       data-gradient="{{ $item['color'] }}">
                        <div class="nav-icon {{ $active === $item['route'] ? 'nav-icon-active' : '' }}">
                            <i class="fas fa-{{ $item['icon'] }}"></i>
                        </div>
                        <span class="nav-text">{{ $item['label'] }}</span>
                        @if(isset($item['badge']) && $item['badge'])
                            <span class="badge badge-danger badge-pill ml-2">{{ $item['badge'] }}</span>
                        @endif
                        @if($active === $item['route'])
                            <div class="active-indicator"></div>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <!-- WhatsApp Agent Manager - Single Entry Point -->
        <div class="nav-section-header">
            <h3 class="nav-section-title">
                <i class="fab fa-whatsapp"></i>
                WhatsApp Management
            </h3>
        </div>
        
        <ul class="nav-list whatsapp-section">
            <li class="nav-item whatsapp-item">
                <a href="{{ route('admin.whatsapp.dashboard') }}" 
                   class="nav-link whatsapp-nav-link {{ request()->routeIs('admin.whatsapp.*') ? 'nav-link-active' : '' }}"
                   data-gradient="from-green-500 to-green-600"
                   title="Complete WhatsApp workflow management system">
                    <div class="nav-icon whatsapp-nav-icon {{ request()->routeIs('admin.whatsapp.*') ? 'nav-icon-active' : '' }}">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div class="nav-content">
                        <span class="nav-text">WhatsApp Agent Manager</span>
                        <span class="nav-description">AI-powered conversation workflow system</span>
                    </div>
                    <span class="nav-badge badge-ai">
                        NEW
                    </span>
                    @if(request()->routeIs('admin.whatsapp.*'))
                        <div class="active-indicator"></div>
                    @endif
                </a>
            </li>
        </ul>
    </nav>

    <!-- Logout Button -->
    <div class="logout-section">
        <form action="{{ route('admin.logout') }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to logout?')">
            @csrf
            <button type="submit" class="logout-button">
                <div class="logout-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <span class="logout-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

<style>
    .sidebar-modern {
        width: 280px;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        color: white;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1000;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 
            4px 0 20px rgba(0, 0, 0, 0.3),
            inset -1px 0 0 rgba(255, 255, 255, 0.1);
        border-right: none;
        transform: translateX(0); /* Show sidebar by default on desktop */
    }
    
    /* Mobile: Hide sidebar by default */
    @media (max-width: 767px) {
        .sidebar-modern {
            transform: translateX(-100%);
        }
        
        .sidebar-modern.-translate-x-full {
            transform: translateX(-100%);
        }
        
        .sidebar-modern:not(.-translate-x-full) {
            transform: translateX(0);
        }
    }
    
    /* Desktop: Always show sidebar */
    @media (min-width: 768px) {
        .sidebar-modern {
            transform: translateX(0) !important;
            position: relative;
            height: auto;
        }
    }
    
    .branding-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    
    .branding-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
        z-index: -1;
    }
    
    .brand-icon {
        flex-shrink: 0;
        padding: 0.5rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }
    
    .brand-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin: 0;
        background: linear-gradient(135deg, #60a5fa, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .brand-subtitle {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        margin: 0;
        font-weight: 500;
    }
    
    .navigation-section {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 0;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }
    
    .navigation-section::-webkit-scrollbar {
        width: 4px;
    }
    
    .navigation-section::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .navigation-section::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }
    
    .nav-list {
        list-style: none;
        margin: 0;
        padding: 0 1rem;
    }
    
    .nav-list .nav-item {
        margin-bottom: 0.25rem;
    }
    
    .nav-item {
        margin-bottom: 0.25rem;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.875rem 1rem;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        background: transparent;
        border: 1px solid transparent;
    }
    
    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.05);
        opacity: 0;
        transition: opacity 0.3s ease;
        border-radius: 12px;
    }
    
    .nav-link:hover::before {
        opacity: 1;
    }
    
    .nav-link:hover {
        color: white;
        transform: translateX(4px);
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .nav-link-active {
        background: rgba(59, 130, 246, 0.15) !important;
        color: white !important;
        border-color: rgba(59, 130, 246, 0.3) !important;
        box-shadow: 
            0 4px 16px rgba(59, 130, 246, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
        transform: translateX(4px);
    }
    
    .nav-icon {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
        flex-shrink: 0;
        font-size: 1rem;
    }
    
    .nav-link:hover .nav-icon {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.1);
    }
    
    .nav-icon-active {
        background: rgba(59, 130, 246, 0.3) !important;
        color: #60a5fa !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .nav-text {
        font-weight: 500;
        font-size: 0.875rem;
        flex: 1;
    }
    
    .active-indicator {
        width: 8px;
        height: 8px;
        background: #60a5fa;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(96, 165, 250, 0.6);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.5;
            transform: scale(1.2);
        }
    }
    
    .logout-section {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0, 0, 0, 0.1);
    }
    
    .logout-button {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.875rem 1rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 12px;
        color: #fca5a5;
        transition: all 0.3s ease;
        cursor: pointer;
        font-weight: 500;
    }
    
    .logout-button:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.4);
        color: #f87171;
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(239, 68, 68, 0.2);
    }
    
    .logout-icon {
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(239, 68, 68, 0.15);
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .logout-button:hover .logout-icon {
        background: rgba(239, 68, 68, 0.25);
        transform: scale(1.1);
    }
    
    .logout-text {
        font-size: 0.875rem;
        flex: 1;
    }
    
    /* WhatsApp Manager Bot Section Styles */
    .nav-section-header {
        padding: 1rem 1.5rem 0.5rem 1.5rem;
        border-bottom: 1px solid rgba(34, 197, 94, 0.2);
        margin-bottom: 0.5rem;
        background: rgba(34, 197, 94, 0.05);
        backdrop-filter: blur(10px);
    }
    
    .nav-section-title {
        color: #22c55e;
        font-size: 0.875rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .nav-section-title i {
        font-size: 1rem;
        color: #22c55e;
    }
    
    .whatsapp-section {
        background: rgba(34, 197, 94, 0.02);
        border-radius: 0 0 12px 12px;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
    }
    
    .whatsapp-item {
        margin-bottom: 0.375rem;
    }
    
    .whatsapp-nav-link {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(34, 197, 94, 0.1);
        padding: 1rem;
        border-radius: 10px;
        position: relative;
        overflow: hidden;
    }
    
    .whatsapp-nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, #22c55e, #16a34a);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .whatsapp-nav-link:hover::before,
    .whatsapp-nav-link.nav-link-active::before {
        opacity: 1;
    }
    
    .whatsapp-nav-link:hover {
        background: rgba(34, 197, 94, 0.1);
        border-color: rgba(34, 197, 94, 0.3);
        transform: translateX(6px);
    }
    
    .whatsapp-nav-link.nav-link-active {
        background: rgba(34, 197, 94, 0.15) !important;
        border-color: rgba(34, 197, 94, 0.4) !important;
        box-shadow: 
            0 4px 16px rgba(34, 197, 94, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1) !important;
    }
    
    .whatsapp-nav-icon {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.2);
        color: #22c55e;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 8px;
    }
    
    .whatsapp-nav-link:hover .whatsapp-nav-icon {
        background: rgba(34, 197, 94, 0.2);
        border-color: rgba(34, 197, 94, 0.4);
        color: #16a34a;
        transform: scale(1.05);
    }
    
    .whatsapp-nav-icon.nav-icon-active {
        background: rgba(34, 197, 94, 0.3) !important;
        border-color: rgba(34, 197, 94, 0.5) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    
    .nav-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.125rem;
    }
    
    .nav-description {
        font-size: 0.625rem;
        color: rgba(255, 255, 255, 0.5);
        font-weight: 400;
        line-height: 1.2;
    }
    
    .whatsapp-nav-link:hover .nav-description {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .nav-badge {
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.5rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-left: 0.5rem;
    }
    
    .badge-ai {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        animation: glow-ai 2s ease-in-out infinite alternate;
    }
    
    .badge-live {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        animation: glow-live 1.5s ease-in-out infinite alternate;
    }
    
    @keyframes glow-ai {
        0% { box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); }
        100% { box-shadow: 0 2px 16px rgba(59, 130, 246, 0.6), 0 0 24px rgba(59, 130, 246, 0.2); }
    }
    
    @keyframes glow-live {
        0% { box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3); }
        100% { box-shadow: 0 2px 16px rgba(239, 68, 68, 0.6), 0 0 24px rgba(239, 68, 68, 0.2); }
    }
    
    /* Mobile menu toggle */
    @media (max-width: 767px) {
        .sidebar-modern.open {
            transform: translateX(0);
        }
        
        .sidebar-modern::after {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 280px;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-modern.open::after {
            opacity: 1;
            visibility: visible;
        }
    }
</style>