@extends('doctor.master')

@push('styles')
<style>
    :root {
        --primary-color: #383F45;
        --secondary-color: #E7A51B;
        --background-color: #f5f5f5;
        --surface-color: #ffffff;
        --text-primary: #212121;
        --text-secondary: #757575;
        --shadow-color: rgba(0, 0, 0, 0.12);
        --accent-color: #F7C873;
        --success-color: #4CAF50;
        --danger-color: #E53935;
        --border-radius: 16px;
        --sidebar-width: 260px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--background-color);
        color: var(--text-primary);
    }

    .notifications-container {
        min-height: 100vh;
        padding: 2rem;
        margin-top: 70px;
        background: var(--background-color);
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
        padding: 2rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: var(--shadow-color) 0 4px 20px;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        color: var(--surface-color) !important;
    }

    .page-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.8) !important;
        margin-bottom: 2rem;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: var(--surface-color) !important;
        box-shadow: var(--shadow-color) 0 4px 15px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-color) 0 6px 20px;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #66BB6A 100%);
        color: var(--surface-color) !important;
        box-shadow: var(--shadow-color) 0 4px 15px;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-color) 0 6px 20px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-color) 0 2px 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .stat-card.total::before {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
    }

    .stat-card.unread::before {
        background: linear-gradient(135deg, var(--danger-color) 0%, #EF5350 100%);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-color) 0 8px 25px;
    }

    .stat-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-text h3 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary) !important;
        margin: 0;
        line-height: 1;
    }

    .stat-text p {
        font-size: 1rem;
        color: var(--text-secondary) !important;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: var(--surface-color);
    }

    .stat-icon.total {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
    }

    .stat-icon.unread {
        background: linear-gradient(135deg, var(--danger-color) 0%, #EF5350 100%);
    }

    .notifications-list {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-color) 0 2px 8px;
        overflow: hidden;
    }

    .list-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .list-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--surface-color) !important;
        margin: 0;
    }

    .notification-item {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e0e0e0;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-item:hover {
        background: rgba(231, 165, 27, 0.02);
    }

    .notification-item.unread {
        background: rgba(231, 165, 27, 0.05);
        border-left: 4px solid var(--secondary-color);
    }

    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: var(--surface-color);
        flex-shrink: 0;
    }

    .notification-icon.campaign {
        background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    }

    .notification-icon.patient {
        background: linear-gradient(135deg, var(--success-color) 0%, #388E3C 100%);
    }

    .notification-icon.approval {
        background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
    }

    .notification-icon.payment {
        background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
    }

    .notification-icon.business_request {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
    }

    .notification-icon.rejection {
        background: linear-gradient(135deg, var(--danger-color) 0%, #C62828 100%);
    }

    .notification-icon.default {
        background: linear-gradient(135deg, var(--text-secondary) 0%, #616161 100%);
    }

    .notification-body {
        flex: 1;
        min-width: 0;
    }

    .notification-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary) !important;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }

    .notification-message {
        font-size: 0.875rem;
        color: var(--text-secondary) !important;
        margin: 0 0 0.75rem 0;
        line-height: 1.5;
    }

    .notification-details {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 0.75rem;
    }

    .detail-item {
        font-size: 0.75rem;
        color: var(--text-secondary) !important;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .detail-item.campaign {
        color: #2196F3 !important;
    }

    .detail-item.user {
        color: var(--success-color) !important;
    }

    .detail-item.amount {
        color: #FF9800 !important;
    }

    .notification-time {
        font-size: 0.75rem;
        color: var(--text-secondary) !important;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .notification-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
    }

    .new-badge {
        background: var(--danger-color);
        color: var(--surface-color);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .action-btn:hover {
        background: rgba(0, 0, 0, 0.05);
        color: var(--text-primary);
    }

    .action-btn.mark-read:hover {
        background: rgba(76, 175, 80, 0.1);
        color: var(--success-color);
    }

    .action-btn.delete:hover {
        background: rgba(229, 57, 53, 0.1);
        color: var(--danger-color);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary) !important;
        margin: 0 0 0.5rem 0;
    }

    .empty-state p {
        color: var(--text-secondary) !important;
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-container {
            padding: 1rem;
            margin-top: 70px;
        }

        .page-header {
            padding: 1.5rem;
            text-align: center;
        }

        .page-title {
            font-size: 2rem;
        }

        .header-actions {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            max-width: 200px;
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-text h3 {
            font-size: 2rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .notification-item {
            padding: 1rem;
        }

        .notification-content {
            flex-direction: column;
            gap: 0.75rem;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .notification-actions {
            justify-content: space-between;
            width: 100%;
            margin-top: 0.75rem;
        }

        .notification-details {
            flex-direction: column;
            gap: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .notifications-container {
            padding: 0.5rem;
        }

        .page-header {
            padding: 1rem;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .list-header {
            padding: 1rem;
        }

        .list-title {
            font-size: 1.25rem;
        }

        .notification-item {
            padding: 0.75rem;
        }
    }

    /* Toast Notifications */
    .toast-notification {
        z-index: 9999;
        min-width: 300px;
    }

    @media (max-width: 480px) {
        .toast-notification {
            min-width: auto;
            left: 1rem;
            right: 1rem;
            transform: translateY(-100%);
        }
        
        .toast-notification.show {
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="notifications-container">
    <!-- Header -->
    <div class="page-header">
        <div class="text-center">
            <h1 class="page-title">
                <i class="fas fa-bell mr-3"></i>Notifications
            </h1>
            <p class="page-subtitle">Stay updated with your campaign activities</p>
            <div class="header-actions">
                <button id="markAllReadBtn" class="btn btn-primary">
                    <i class="fas fa-check-double"></i>Mark All Read
                </button>
                <button id="refreshNotifications" class="btn btn-success">
                    <i class="fas fa-sync"></i>Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Stats -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-content">
                <div class="stat-text">
                    <h3>{{ $notifications->count() }}</h3>
                    <p>Total Notifications</p>
                </div>
                <div class="stat-icon total">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card unread">
            <div class="stat-content">
                <div class="stat-text">
                    <h3>{{ $notifications->where('read', 0)->count() }}</h3>
                    <p>Unread Notifications</p>
                </div>
                <div class="stat-icon unread">
                    <i class="fas fa-bell-slash"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        <div class="list-header">
            <h2 class="list-title">Recent Notifications</h2>
        </div>
        
        <div class="notifications-container">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification->read ? '' : 'unread' }}" 
                     data-notification-id="{{ $notification->id }}">
                    <div class="notification-content">
                        <!-- Notification Icon -->
                        <div class="notification-icon {{ $notification->type }}">
                            @switch($notification->type)
                                @case('campaign')
                                    <i class="fas fa-calendar-plus"></i>
                                    @break
                                @case('patient')
                                    <i class="fas fa-user-plus"></i>
                                    @break
                                @case('approval')
                                    <i class="fas fa-check-circle"></i>
                                    @break
                                @case('payment')
                                    <i class="fas fa-rupee-sign"></i>
                                    @break
                                @case('business_request')
                                    <i class="fas fa-briefcase"></i>
                                    @break
                                @case('rejection')
                                    <i class="fas fa-times-circle"></i>
                                    @break
                                @default
                                    <i class="fas fa-bell"></i>
                            @endswitch
                        </div>
                        
                        <!-- Notification Content -->
                        <div class="notification-body">
                            <h3 class="notification-title">
                                {{ $notification->title }}
                            </h3>
                            <p class="notification-message">
                                {{ $notification->message }}
                            </p>
                            
                            <!-- Additional Details -->
                            <div class="notification-details">
                                @if($notification->campaign_name)
                                    <div class="detail-item campaign">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $notification->campaign_name }} - {{ $notification->campaign_location }}</span>
                                    </div>
                                @endif
                                
                                @if($notification->user_name)
                                    <div class="detail-item user">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $notification->user_name }} ({{ $notification->user_email }})</span>
                                    </div>
                                @endif
                                
                                @if($notification->amount)
                                    <div class="detail-item amount">
                                        <i class="fas fa-rupee-sign"></i>
                                        <span>Amount: ₹{{ number_format($notification->amount) }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="notification-time">
                                <i class="fas fa-clock"></i>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="notification-actions">
                            @if(!$notification->read)
                                <span class="new-badge">New</span>
                            @endif

                            <!-- Mark as Read Button -->
                            <button class="action-btn mark-read mark-read-btn" 
                                    data-id="{{ $notification->id }}"
                                    title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>

                            <!-- Delete Button -->
                            <button class="action-btn delete delete-notification-btn" 
                                    data-id="{{ $notification->id }}"
                                    title="Delete notification">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-bell-slash"></i>
                    <h3>No notifications yet</h3>
                    <p>You'll see campaign updates and patient registrations here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mark all as read
    $('#markAllReadBtn').on('click', function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("doctor.notifications.mark-all-read") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    // Remove all "New" badges and unread styling
                    $('.new-badge').remove();
                    $('.notification-item').removeClass('unread');
                    
                    // Update unread count
                    $('.stat-card.unread .stat-text h3').text('0');
                    
                    // Show success message
                    showNotification('All notifications marked as read', 'success');
                }
            },
            error: function() {
                showNotification('Error marking notifications as read', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Mark single notification as read
    $('.mark-read-btn').on('click', function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        const notification = button.closest('[data-notification-id]');
        
        $.ajax({
            url: `/doctor/notifications/${notificationId}/mark-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    notification.removeClass('unread');
                    notification.find('.new-badge').remove();
                    
                    // Update unread count
                    const currentUnread = parseInt($('.stat-card.unread .stat-text h3').text());
                    if(currentUnread > 0) {
                        $('.stat-card.unread .stat-text h3').text(currentUnread - 1);
                    }
                    
                    showNotification('Notification marked as read', 'success');
                }
            },
            error: function() {
                showNotification('Error marking notification as read', 'error');
            }
        });
    });

    // Delete notification
    $('.delete-notification-btn').on('click', function() {
        const notificationId = $(this).data('id');
        const notification = $(this).closest('[data-notification-id]');
        
        if(confirm('Are you sure you want to delete this notification?')) {
            $.ajax({
                url: `/doctor/notifications/${notificationId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        notification.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Update total count
                            const currentTotal = parseInt($('.stat-card.total .stat-text h3').text());
                            $('.stat-card.total .stat-text h3').text(Math.max(0, currentTotal - 1));
                            
                            // Check if no notifications left
                            if($('[data-notification-id]').length === 0) {
                                location.reload();
                            }
                        });
                        
                        showNotification('Notification deleted successfully', 'success');
                    }
                },
                error: function() {
                    showNotification('Error deleting notification', 'error');
                }
            });
        }
    });

    // Refresh notifications
    $('#refreshNotifications').on('click', function() {
        location.reload();
    });
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        $('#refreshNotifications').addClass('animate-pulse');
        setTimeout(function() {
            $('#refreshNotifications').removeClass('animate-pulse');
        }, 1000);
    }, 30000);
});

// Helper function to show notifications
function showNotification(message, type = 'info') {
    const bgColor = type === 'success' ? '#4CAF50' : 
                   type === 'error' ? '#E53935' : 
                   type === 'warning' ? '#E7A51B' : '#383F45';
    
    const icon = type === 'success' ? 'check' : 
                type === 'error' ? 'times' : 
                type === 'warning' ? 'exclamation' : 'info';
    
    const notification = $(`
        <div class="toast-notification fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform max-w-sm" 
             style="background: ${bgColor};">
            <div class="flex items-center">
                <i class="fas fa-${icon} mr-3"></i>
                <span>${message}</span>
                <button class="ml-3 text-white hover:text-gray-200" onclick="$(this).parent().parent().remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => notification.removeClass('translate-x-full'), 100);
    setTimeout(() => {
        notification.addClass('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Real-time notification listening
@auth('doctor')
try {
    // Check if Echo is available and properly initialized
    if (typeof window.Echo !== 'undefined' && window.Echo !== null) {
        // Listen for doctor-specific messages
        window.Echo.channel('doctor.{{ auth("doctor")->id() }}')
            .listen('doctor-message.sent', (e) => {
                console.log('Doctor message received:', e);
                
                // Show toast notification
                showToastNotification(e.message.message, getNotificationType(e.message.type));
                
                // Add notification to the list if we're on notifications page
                if (window.location.pathname.includes('notifications')) {
                    addNewNotificationToList(e.message);
                }
                
                // Update notification badge count
                updateNotificationBadge();
            });
    } else {
        console.log('Echo not available - WebSocket connection not configured');
        
        // Fallback: Poll for new notifications every 30 seconds
        setInterval(function() {
            checkForNewNotifications();
        }, 30000);
    }
} catch (error) {
    console.log('WebSocket connection error:', error);
    
    // Fallback: Poll for new notifications
    setInterval(function() {
        checkForNewNotifications();
    }, 30000);
}
@endauth

// Function to show toast notifications
function showToastNotification(message, type = 'info') {
    const bgColor = type === 'success' ? '#4CAF50' : 
                   type === 'error' ? '#E53935' : 
                   type === 'warning' ? '#E7A51B' : 
                   type === 'business_request' ? '#E7A51B' : '#383F45';
    
    const icon = type === 'success' ? 'check' : 
                type === 'error' ? 'times' : 
                type === 'warning' ? 'exclamation' : 
                type === 'business_request' ? 'briefcase' : 'bell';
    
    const toast = $(`
        <div class="toast-notification fixed top-4 right-4 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-all duration-300 max-w-sm"
             style="background: ${bgColor};">
            <div class="flex items-center">
                <i class="fas fa-${icon} mr-3 text-lg"></i>
                <div class="flex-1">
                    <div class="font-semibold text-sm">New Notification</div>
                    <div class="text-xs mt-1 opacity-90">${message}</div>
                </div>
                <button class="ml-2 text-white hover:text-gray-200" onclick="$(this).parent().parent().remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Animate in
    setTimeout(() => toast.removeClass('translate-x-full'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.addClass('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Function to get notification type for styling
function getNotificationType(type) {
    switch(type) {
        case 'business_request': return 'business_request';
        case 'approval': return 'success';
        case 'rejection': return 'error';
        case 'payment': return 'success';
        default: return 'info';
    }
}

// Function to add new notification to the list
function addNewNotificationToList(message) {
    const notificationHtml = createNotificationHtml(message);
    $('.notifications-container').prepend(notificationHtml);
    
    // Update total count
    const currentTotal = parseInt($('.stat-card.total .stat-text h3').text()) || 0;
    $('.stat-card.total .stat-text h3').text(currentTotal + 1);
    
    // Update unread count
    const currentUnread = parseInt($('.stat-card.unread .stat-text h3').text()) || 0;
    $('.stat-card.unread .stat-text h3').text(currentUnread + 1);
    
    // Remove empty state if exists
    $('.empty-state').remove();
}

// Function to create notification HTML
function createNotificationHtml(message) {
    const typeIcon = getNotificationIcon(message.type);
    const typeClass = message.type || 'default';
    const timeAgo = 'Just now';
    
    return `
        <div class="notification-item unread" data-notification-id="${message.id}">
            <div class="notification-content">
                <div class="notification-icon ${typeClass}">
                    <i class="fas fa-${typeIcon}"></i>
                </div>
                
                <div class="notification-body">
                    <h3 class="notification-title">
                        ${getNotificationTitle(message.type)}
                    </h3>
                    <p class="notification-message">
                        ${message.message}
                    </p>
                    
                    <div class="notification-time">
                        <i class="fas fa-clock"></i>
                        <span>${timeAgo}</span>
                    </div>
                </div>
                
                <div class="notification-actions">
                    <span class="new-badge">New</span>
                    
                    <button class="action-btn mark-read mark-read-btn" data-id="${message.id}" title="Mark as read">
                        <i class="fas fa-check"></i>
                    </button>
                    
                    <button class="action-btn delete delete-notification-btn" data-id="${message.id}" title="Delete notification">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Helper functions for notification display
function getNotificationIcon(type) {
    switch(type) {
        case 'business_request': return 'briefcase';
        case 'approval': return 'check-circle';
        case 'rejection': return 'times-circle';
        case 'payment': return 'rupee-sign';
        case 'campaign': return 'calendar-plus';
        case 'patient': return 'user-plus';
        default: return 'bell';
    }
}

function getNotificationTitle(type) {
    switch(type) {
        case 'business_request': return 'New Business Opportunity';
        case 'approval': return 'Proposal Approved';
        case 'rejection': return 'Proposal Rejected';
        case 'payment': return 'Payment Received';
        case 'campaign': return 'New Campaign';
        case 'patient': return 'Patient Registration';
        default: return 'Notification';
    }
}

// Function to update notification badge
function updateNotificationBadge() {
    // Update badge count in sidebar or header
    const badge = $('.notification-badge');
    if (badge.length) {
        const currentCount = parseInt(badge.text()) || 0;
        badge.text(currentCount + 1).show();
    }
}

// Fallback function to check for new notifications via AJAX
function checkForNewNotifications() {
    $.get('/doctor/notifications/check-new', function(data) {
        if (data.new_notifications && data.new_notifications.length > 0) {
            data.new_notifications.forEach(function(notification) {
                showToastNotification(notification.message, getNotificationType(notification.type));
                
                if (window.location.pathname.includes('notifications')) {
                    addNewNotificationToList(notification);
                }
            });
        }
    }).catch(function() {
        console.log('Could not check for new notifications');
    });
}
</script>
@endpush