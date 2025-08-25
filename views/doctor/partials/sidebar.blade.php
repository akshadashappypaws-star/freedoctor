
<div class="sidebar-container">
    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <!-- Doctor Profile Section -->
        <div class="nav-section">
            <div class="doctor-profile-header">
                <div class="doctor-avatar-container">
                    <div class="doctor-avatar">
                        {{ substr(auth('doctor')->user()->doctor_name ?? 'D', 0, 1) }}
                    </div>
                    <div class="status-indicator online"></div>
                </div>
                <div class="doctor-info">
                    <h3 class="doctor-name">
                        Dr. {{ auth('doctor')->user()->doctor_name ?? 'Doctor' }}
                    </h3>
                    @if(auth('doctor')->user()->specialty)
                        <div class="doctor-specialty">
                            <i class="fas fa-stethoscope"></i>
                            {{ auth('doctor')->user()->specialty->name ?? 'Medical Specialist' }}
                        </div>
                    @else
                        <div class="doctor-specialty">
                            <i class="fas fa-user-md"></i>
                            Medical Specialist
                        </div>
                    @endif
                    <div class="doctor-id">
                        <i class="fas fa-id-badge"></i>
                        ID: #{{ str_pad(auth('doctor')->user()->id ?? '000', 3, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="quick-actions">
                <a href="{{ route('doctor.profile') }}" class="quick-action-btn profile-btn">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>
                <button class="quick-action-btn notifications-btn" onclick="openNotifications()">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </button>
            </div>

        </div>

        <!-- Main Navigation -->
        <div class="nav-section">
            <h3 class="nav-section-title">Main Menu</h3>
            
            @php
                $active = request()->segment(2);
                $menuItems = [
                    [
                        'route' => 'dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'tachometer-alt',
                        'color' => 'from-purple-500 to-purple-600'
                    ],
                    [
                        'route' => 'campaigns',
                        'label' => 'My Campaigns',
                        'icon' => 'calendar-plus',
                        'color' => 'from-green-500 to-green-600'
                    ],
                    [
                        'route' => 'patients',
                        'label' => 'Patient Registrations',
                        'icon' => 'users',
                        'color' => 'from-blue-500 to-blue-600'
                    ],
                    [
                        'route' => 'sponsors',
                        'label' => 'Sponsorships',
                        'icon' => 'handshake',
                        'color' => 'from-yellow-500 to-orange-500',
                        'description' => 'Manage sponsors & funding'
                    ],
                    [
                        'route' => 'business-reach-out',
                        'label' => 'Business Opportunities',
                        'icon' => 'briefcase',
                        'color' => 'from-indigo-500 to-purple-600',
                        'description' => 'Organization requests & camps'
                    ],
                    [
                        'route' => 'profit',
                        'label' => 'Earnings & Reports',
                        'icon' => 'chart-line',
                        'color' => 'from-emerald-500 to-teal-600'
                    ],
                    [
                        'route' => 'wallet',
                        'label' => 'Wallet',
                        'icon' => 'wallet',
                        'color' => 'from-green-500 to-emerald-600',
                        'description' => 'Earnings & Withdrawals'
                    ],
                    [
                        'route' => 'profile',
                        'label' => 'Profile Settings',
                        'icon' => 'user-cog',
                        'color' => 'from-gray-500 to-gray-600'
                    ],
                    [
                        'route' => 'notifications',
                        'label' => 'Notifications',
                        'icon' => 'bell',
                        'color' => 'from-red-500 to-pink-600'
                    ],
                ];
            @endphp

            @foreach($menuItems as $item)
                <a href="{{ route('doctor.' . $item['route']) }}"
                   class="nav-item {{ $active === $item['route'] ? 'active' : '' }}">
                    <div class="nav-icon {{ $active === $item['route'] ? 'active-icon' : '' }}">
                        <i class="fas fa-{{ $item['icon'] }}"></i>
                    </div>
                    <div class="nav-content">
                        <span class="nav-label">{{ $item['label'] }}</span>
                        @if(isset($item['description']))
                            <span class="nav-description">{{ $item['description'] }}</span>
                        @endif
                    </div>
                    @if($active === $item['route'])
                        <div class="active-indicator"></div>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Quick Stats -->
        <div class="nav-section performance-section">
            <h3 class="nav-section-title">
                <i class="fas fa-chart-line me-2"></i>Performance Overview
            </h3>
            <div class="performance-grid">
                <div class="performance-item">
                    <div class="performance-header">
                        <div class="performance-icon campaigns-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <div class="performance-trend up">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="performance-value">{{ $totalCampaigns ?? '0' }}</div>
                    <div class="performance-label">Active Campaigns</div>
                    <div class="performance-progress">
                        <div class="progress-bar" style="width: {{ min(($totalCampaigns ?? 0) * 10, 100) }}%"></div>
                    </div>
                </div>
                
                <div class="performance-item">
                    <div class="performance-header">
                        <div class="performance-icon sponsors-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="performance-trend up">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="performance-value">{{ $totalSponsors ?? '0' }}</div>
                    <div class="performance-label">Sponsors</div>
                    <div class="performance-progress">
                        <div class="progress-bar" style="width: {{ min(($totalSponsors ?? 0) * 15, 100) }}%"></div>
                    </div>
                </div>
                
                <div class="performance-item">
                    <div class="performance-header">
                        <div class="performance-icon patients-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="performance-trend up">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="performance-value">{{ $totalPatients ?? '0' }}</div>
                    <div class="performance-label">Patients Served</div>
                    <div class="performance-progress">
                        <div class="progress-bar" style="width: {{ min(($totalPatients ?? 0) / 2, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="nav-section logout-section">
            <form action="{{ route('doctor.logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to logout?')">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>

<!-- Doctor Sidebar Styles -->
<style>
.sidebar-container {
    background: var(--primary-color);
    color: white;
    height: 100%;
    overflow-y: auto;
    padding: 0;
    width: 100%;
}

.sidebar-nav {
    padding: 1rem 0;
}

.nav-section {
    margin-bottom: 1.5rem;
    padding: 0 1rem;
}

.nav-section-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.6);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.75rem;
    padding: 0 0.5rem;
}

/* Doctor Profile Section */
.doctor-profile-header {
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

.doctor-profile-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
    border-radius: 2px;
}

.doctor-avatar-container {
    position: relative;
    flex-shrink: 0;
}

.doctor-avatar {
    width: 4rem;
    height: 4rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.5rem;
    box-shadow: 
        0 8px 32px rgba(102, 126, 234, 0.3),
        0 0 0 3px rgba(255, 255, 255, 0.1);
    position: relative;
    overflow: hidden;
}

.doctor-avatar::before {
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
    background: #6c757d;
    box-shadow: 0 0 8px rgba(108, 117, 125, 0.6);
}

.status-indicator.online {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 0 8px rgba(108, 117, 125, 0.6); }
    50% { box-shadow: 0 0 16px rgba(108, 117, 125, 0.8), 0 0 24px rgba(108, 117, 125, 0.4); }
}

.doctor-info {
    flex: 1;
    min-width: 0;
}

.doctor-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: white;
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.doctor-specialty {
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
}

.doctor-specialty i {
    font-size: 0.75rem;
}

.doctor-id {
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
}

.quick-action-btn i {
    font-size: 1rem;
    margin-bottom: 0.5rem;
}

.profile-btn {
    border-color: rgba(59, 130, 246, 0.3);
}

.profile-btn:hover {
    background: rgba(59, 130, 246, 0.15);
    border-color: rgba(59, 130, 246, 0.5);
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
    background: rgba(59, 130, 246, 0.2);
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem auto;
}

.stat-mini-icon i {
    font-size: 0.75rem;
    color: #60a5fa;
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

/* Navigation Items */
.nav-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
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
    background: rgba(50, 56, 62, 0.8);
    color: white;
    border-color: rgba(90, 98, 106, 0.5);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.nav-item.active {
    background: linear-gradient(135deg, #5a626a 0%, #6c757d 100%);
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 16px rgba(90, 98, 106, 0.4);
}

.nav-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: rgba(50, 56, 62, 0.6);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    transition: all 0.3s ease;
    border: 1px solid rgba(90, 98, 106, 0.4);
}

.nav-icon.active-icon {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.nav-item:hover .nav-icon {
    background: rgba(90, 98, 106, 0.4);
    border-color: rgba(90, 98, 106, 0.6);
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

.nav-content {
    flex: 1;
}

.nav-label {
    font-size: 0.875rem;
    font-weight: 500;
    display: block;
    line-height: 1.2;
}

.nav-description {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 0.25rem;
    display: block;
}

.nav-item.active .nav-description {
    color: rgba(255, 255, 255, 0.8);
}

.active-indicator {
    width: 0.5rem;
    height: 0.5rem;
    background: white;
    border-radius: 50%;
    box-shadow: 0 0 8px rgba(255, 255, 255, 0.6);
}

/* Performance Section */
.performance-section {
    background: rgba(255, 255, 255, 0.04);
    border-radius: 16px;
    padding: 1.5rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.performance-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.performance-item {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.performance-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.performance-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.performance-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.campaigns-icon {
    background: linear-gradient(135deg, #5a626a, #6c757d);
    box-shadow: 0 4px 12px rgba(90, 98, 106, 0.3);
}

.sponsors-icon {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.patients-icon {
    background: linear-gradient(135deg, #7c3aed, #8b5cf6);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

.performance-icon i {
    color: white;
    font-size: 0.875rem;
    z-index: 2;
}

.performance-trend {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
}

.performance-trend.up {
    background: rgba(108, 117, 125, 0.2);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.3);
}

.performance-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 0.25rem;
}

.performance-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 0.75rem;
}

.performance-progress {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    border-radius: 2px;
    transition: width 1s ease;
    position: relative;
}

.progress-bar::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: progressShine 2s infinite;
}

@keyframes progressShine {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Logout Section */
.logout-section {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(148, 163, 184, 0.2);
}

.logout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 0.875rem;
    font-weight: 600;
    transition: all 0.3s ease;
    gap: 0.5rem;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

.logout-btn:hover {
    background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
}

.logout-btn:active {
    transform: translateY(0);
}

/* Scrollbar Styling */
.sidebar-container::-webkit-scrollbar {
    width: 4px;
}

.sidebar-container::-webkit-scrollbar-track {
    background: rgba(15, 23, 42, 0.5);
}

.sidebar-container::-webkit-scrollbar-thumb {
    background: rgba(148, 163, 184, 0.3);
    border-radius: 2px;
}

.sidebar-container::-webkit-scrollbar-thumb:hover {
    background: rgba(148, 163, 184, 0.5);
}

/* Responsive Design */
@media (max-width: 768px) {
    .quick-info-grid {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 0.25rem;
    }
    
    .doctor-profile-header {
        flex-direction: column;
        text-align: center;
    }
    
    .nav-item {
        padding: 0.5rem 0.75rem;
    }
    
    .nav-icon {
        width: 2rem;
        height: 2rem;
        margin-right: 0.5rem;
    }
    
    .nav-icon i {
        font-size: 0.875rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .quick-stats-mini {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .performance-grid {
        gap: 0.75rem;
    }
}

/* JavaScript Functions */
function openNotifications() {
    window.location.href = '{{ route("doctor.notifications") }}';
}
</style>

<script>
// Add interactive animations for sidebar elements
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect to navigation items
    const navItems = document.querySelectorAll('.nav-item, .quick-action-btn');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                z-index: 1;
            `;
            
            this.style.position = 'relative';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Animate progress bars on load
    const progressBars = document.querySelectorAll('.progress-bar');
    setTimeout(() => {
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 500);
});

// Add ripple animation CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>