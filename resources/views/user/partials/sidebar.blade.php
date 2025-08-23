
<div class="sidebar-container">
    @auth('user')
    <!-- User Profile Section -->
    <div class="nav-section" style="margin:0!important;">
        <div class="user-profile-header">
            <div class="user-avatar-container">
                <div class="user-avatar">
                    {{ substr(auth('user')->user()->username ?? 'U', 0, 1) }}
                </div>
                <div class="status-indicator online"></div>
            </div>
            <div class="user-info">
                <h3 class="user-name">
                    {{ auth('user')->user()->username ?? 'User' }}
                </h3>
                
                <div class="user-id">
                    <i class="fas fa-id-badge"></i>
                    ID: #{{ str_pad(auth('user')->user()->id ?? '000', 3, '0', STR_PAD_LEFT) }}
                </div>
               
            </div>
             <div class="user-email">
                    <i class="fas fa-envelope"></i>
                    {{ auth('user')->user()->email ?? 'user@example.com' }}
                </div>
        </div>
        

    </div>
    
    @else
    <!-- Sidebar Header for Guests -->
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor" class="w-8 h-8" >
            </div>
            <div class="logo-text">
                <h1>FreeDoctor</h1>
                <p>Medical Camps </p>
            </div>
        </div>
    </div>
    @endauth

    <!-- Navigation Menu -->
    <nav class="sidebar-nav" style="padding:15px 10px;">
        <div class="nav-section">
            <h3 class="nav-section-title">Main Menu</h3>
            
            <a href="{{ route('user.dashboard') }}" 
               class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span>Dashboard</span>
            </a>
            
            <a href="{{ route('user.campaigns') }}" 
               class="nav-item {{ request()->routeIs('user.campaigns') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-notes-medical"></i>
                </div>
                <span>Health Campaigns</span>
            </a>
            
            <!-- <a href="{{ route('user.sponsors') }}" 
               class="nav-item {{ request()->routeIs('user.sponsors') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <span>Sponsor Programs</span>
            </a>
             -->
            <a href="{{ route('user.organization-camp-request') }}" 
               class="nav-item {{ request()->routeIs('user.organization-camp-request') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-building"></i>
                </div>
                <span>Organization Requests</span>
            </a>
            
            <a href="{{ route('user.our-business-proposal') }}" 
               class="nav-item {{ request()->routeIs('user.our-business-proposal') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <span>Lead generation (for doctors)</span>
            </a>
        </div>

        @auth('user')
        <!-- User Account Section -->
        <div class="nav-section">
            <h3 class="nav-section-title">My Account</h3>
            
            <a href="{{ route('user.profile') }}" 
               class="nav-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-user"></i>
                </div>
                <span>My Profile</span>
            </a>
            
            <a href="{{ route('user.my-registrations') }}" 
               class="nav-item {{ request()->routeIs('user.my-registrations') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <span>My Registrations</span>
            </a>
            
            <a href="{{ route('user.referral-dashboard') }}" 
               class="nav-item {{ request()->routeIs('user.referral-dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <span>Referral Earnings</span> &nbsp;&nbsp;
                <div class="earnings-badge">
                    <i class="fas fa-coins"></i>
                </div>
            </a>
            
        
            
            <a href="{{ route('user.notifications') }}" 
               class="nav-item {{ request()->routeIs('user.notifications') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <span>Notifications</span>
                @if(isset($unreadNotifications) && $unreadNotifications > 0)
                    <div class="notification-badge">
                        {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                    </div>
                @endif
            </a>
        </div>

        <!-- Logout Section -->
        <div class="nav-section">
            <form action="{{ route('user.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item logout-btn">
                    <div class="nav-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <span>Logout</span>
                </button>
            </form>
        </div>
        @else
        <!-- Guest User Section -->
<div class="nav-section">
    <h3 class="nav-section-title">Get Started</h3>

    <a href="{{ route('user.login') }}" class="nav-item login-btn" style="background-color: #28a745;">
        <i class="fas fa-sign-in-alt"></i>&nbsp;
        <span>Login</span>
    </a>

    <a href="{{ route('user.register') }}" class="nav-item login-btn" style="background-color: #17a2b8;">
        <i class="fas fa-user-plus"></i>&nbsp;
        <span>Register</span>
    </a>

    <a href="{{ route('doctor.login') }}" class="nav-item doctor-login-btn" style="background-color: #ffc107;">
        <i class="fas fa-user-md"></i>&nbsp;
        <span>Doctor Portal</span>
    </a>
</div>




        @endauth
    </nav>
</div>

<!-- Enhanced User Sidebar Styles -->
<style>
/* User Profile Section */
.user-profile-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.user-profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #E7A51B, #0d2141ff, #0e022bff);
    border-radius: 2px;
}

.user-avatar-container {
    position: relative;
    flex-shrink: 0;
}

.user-avatar {
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, #5a626a 0%, #6c757d 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    box-shadow: 
        0 8px 32px rgba(16, 185, 129, 0.3),
        0 0 0 3px rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}

.user-avatar::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

.status-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    background: #10b981;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
}

.status-indicator.online {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 8px rgba(16, 185, 129, 0.6); }
    50% { box-shadow: 0 0 16px rgba(16, 185, 129, 0.8), 0 0 24px rgba(16, 185, 129, 0.4); }
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.user-email {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.9);
    background: rgba(59, 130, 246, 0.2);
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: 1px solid rgba(59, 130, 246, 0.3);
    margin-bottom: 0.5rem;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    margin:auto;
}

.user-email i {
    font-size: 0.75rem;
    flex-shrink: 0;
}

.user-id {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Quick Action Buttons */
.quick-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem 0.5rem;
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-action-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.profile-btn {
    border-color: rgba(16, 185, 129, 0.3);
}

.profile-btn:hover {
    background: rgba(16, 185, 129, 0.15);
    border-color: rgba(16, 185, 129, 0.5);
}

.notifications-btn {
    border-color: rgba(239, 68, 68, 0.3);
    position: relative;
}

.notifications-btn:hover {
    background: rgba(239, 68, 68, 0.15);
    border-color: rgba(239, 68, 68, 0.5);
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

/* Quick Stats Mini Cards */
.quick-stats-mini {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.stat-mini {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    padding: 0.75rem 0.5rem;
    text-align: center;
    transition: all 0.3s ease;
}

.stat-mini:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
}

.stat-mini-icon {
    width: 24px;
    height: 24px;
    background: rgba(16, 185, 129, 0.2);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem auto;
}

.stat-mini-icon i {
    font-size: 0.75rem;
    color: #10b981;
}

.stat-mini-value {
    font-size: 0.875rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.25rem;
}

.stat-mini-label {
    font-size: 0.625rem;
    color: rgba(255, 255, 255, 0.7);
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

/* Enhanced Navigation Items */
.nav-item {
    display: flex;
    align-items: center;
    padding: 0.875rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    margin-bottom: 0.5rem;
    border: 1px solid transparent;
    position: relative;
    overflow: hidden;
}

.nav-item:hover {
    background: rgba(255, 255, 255, 0.08);
    color: white;
    border-color: rgba(255, 255, 255, 0.1);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}

.nav-item.active {
    background: linear-gradient(135deg, #5a626a 0%, #6c757d 100%);
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 16px rgba(108, 117, 125, 0.4);
}

.nav-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: rgba(255, 255, 255, 0.06);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-item:hover .nav-icon {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.2);
}

.nav-item.active .nav-icon {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.nav-icon i {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.8);
    transition: color 0.3s ease;
}

.nav-item:hover .nav-icon i,
.nav-item.active .nav-icon i {
    color: white;
}

/* Logout Button */
.logout-btn {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.logout-btn:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
}

/* Guest Login Buttons */
.login-btn,
.doctor-login-btn {
    justify-content: center;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.login-btn:hover,
.doctor-login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .user-profile-header {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .quick-stats-mini {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
    
    .user-email {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }
}
</style>
