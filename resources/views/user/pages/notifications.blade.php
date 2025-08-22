@extends('user.master')

@section('title', 'Notifications - FreeDoctor')

@push('styles')
<style>
    :root {
        --primary-color: #ffc107;
        --primary-dark: #e6ac00;
        --secondary-color: #343a40;
        --accent-color: #F8F9FA;
        --text-primary: #343a40;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --shadow-light: 0 2px 8px rgba(52, 58, 64, 0.08);
        --shadow-medium: 0 4px 20px rgba(52, 58, 64, 0.12);
        --shadow-heavy: 0 8px 30px rgba(52, 58, 64, 0.16);
        --gradient-primary: linear-gradient(135deg, #ffc107 0%, #e6ac00 100%);
        --gradient-secondary: linear-gradient(135deg, #343a40 0%, #495057 100%);
        --success-color: #28a745;
        --warning-color: #fd7e14;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: linear-gradient(135deg, #f8f9fa 0%, #fff3cd 20%, #f8f9fa 100%);
    }

    .notifications-container {
        min-height: 100vh;
        padding: 2rem 0;
    }

    .notifications-header {
        background: var(--gradient-secondary);
        color: white;
        padding: 3rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-medium);
    }

    .notifications-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .notifications-header .content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .notifications-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .notifications-header p {
        font-size: 1.25rem;
        opacity: 0.9;
        margin: 0;
    }

    .notifications-card {
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 2rem;
    }

    .notifications-card:hover {
        box-shadow: var(--shadow-heavy);
        transform: translateY(-4px);
    }

    .section-header {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 1.5rem 2rem;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 3px solid var(--primary-dark);
    }

    .notification-item {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification-item:hover {
        transform: translateX(8px);
        box-shadow: var(--shadow-light);
        border-color: var(--primary-color);
    }

    .notification-item:hover::before {
        opacity: 1;
    }

    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--secondary-color);
        font-size: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .notification-body {
        flex: 1;
    }

    .notification-message {
        color: var(--text-primary);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .notification-meta {
        color: var(--text-secondary);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .notification-time {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-status {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-unread {
        background: rgba(255, 193, 7, 0.1);
        color: #856404;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .status-read {
        background: rgba(40, 167, 69, 0.1);
        color: #155724;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: var(--text-secondary);
        font-size: 2rem;
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        font-size: 1rem;
        margin: 0;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .btn-small {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-small {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
    }

    .btn-primary-small:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        text-decoration: none;
        color: var(--secondary-color);
    }

    .btn-secondary-small {
        background: var(--accent-color);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary-small:hover {
        background: #e9ecef;
        color: var(--text-primary);
        text-decoration: none;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
        .notifications-container {
            padding: 1rem;
        }
        
        .notifications-header {
            padding: 2rem 1rem;
        }
        
        .notifications-header h1 {
            font-size: 2rem;
        }
        
        .notification-item {
            padding: 1rem;
        }
        
        .notification-content {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .notification-meta {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
@auth('user')
<div class="notifications-container">
    <div class="container " style="padding: 0 !important;">
        <!-- Professional Notifications Header -->
        <div class="notifications-header fade-in">
            <div class="content">
                <h1>
                    <i class="fas fa-bell"></i>
                    My Notifications
                </h1>
                <p>Stay updated with your healthcare messages and important alerts</p>
            </div>
        </div>

        <!-- Enhanced Message Notifications -->
        <div class="notifications-card fade-in">
            <div class="section-header">
                <i class="fas fa-comments"></i>
                Messages & Communications
            </div>
            
            <div class="p-6">
                @forelse($messages as $msg)
                    <div class="notification-item fade-in">
                        <div class="notification-content">
                            <div class="notification-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="notification-body">
                                <div class="notification-message">
                                    {{ $msg->message }}
                                </div>
                                <div class="notification-meta">
                                    <div class="notification-time">
                                        <i class="fas fa-clock"></i>
                                        Received {{ $msg->created_at->diffForHumans() }}
                                    </div>
                                    <div class="notification-status status-unread">
                                        New Message
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <button class="btn-small btn-primary-small" onclick="markAsRead({{ $msg->id }}, this)">
                                        <i class="fas fa-check"></i>Mark as Read
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h3>No Messages Yet</h3>
                        <p>You don't have any messages or notifications at the moment. We'll notify you when something important happens!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Additional Notification Types -->
        <div class="notifications-card fade-in">
            <div class="section-header">
                <i class="fas fa-bell-exclamation"></i>
                Healthcare Alerts & Updates
            </div>
            
            <div class="p-6">
                <!-- Sample healthcare notifications -->
                <div class="notification-item fade-in">
                    <div class="notification-content">
                        <div class="notification-icon" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="notification-body">
                            <div class="notification-message">
                                Welcome to FreeDoctor! Your account has been successfully created and verified.
                            </div>
                            <div class="notification-meta">
                                <div class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    {{ auth()->user()->created_at->diffForHumans() }}
                                </div>
                                <div class="notification-status status-read">
                                    Welcome Message
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="notification-item fade-in">
                    <div class="notification-content">
                        <div class="notification-icon" style="background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="notification-body">
                            <div class="notification-message">
                                Your health data is protected under HIPAA compliance. We ensure your privacy and security at all times.
                            </div>
                            <div class="notification-meta">
                                <div class="notification-time">
                                    <i class="fas fa-clock"></i>
                                    2 days ago
                                </div>
                                <div class="notification-status status-read">
                                    Privacy Notice
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- Not authenticated - redirect to login -->
<div class="min-h-screen flex items-center justify-center">
    <div class="notifications-card rounded-2xl p-8 text-center max-w-md">
        <div class="empty-state-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h3>Access Restricted</h3>
        <p>Please log in to view your notifications.</p>
        <a href="{{ route('user.login') }}" class="btn-small btn-primary-small mt-4">
            <i class="fas fa-sign-in-alt"></i>Login
        </a>
    </div>
</div>
@endauth
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Enhanced notification functionality
function markAsRead(messageId, buttonElement) {
    // Show loading state
    const originalText = buttonElement.innerHTML;
    buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Marking...';
    buttonElement.disabled = true;
    
    // Make AJAX request to mark as read
    fetch(`/user/notifications/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            message_id: messageId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the notification item with animation
            const notificationItem = buttonElement.closest('.notification-item');
            notificationItem.style.transition = 'all 0.3s ease';
            notificationItem.style.opacity = '0';
            notificationItem.style.transform = 'translateX(-100%)';
            
            setTimeout(() => {
                notificationItem.remove();
                
                // Check if there are any notifications left
                const remainingNotifications = document.querySelectorAll('.notification-item');
                if (remainingNotifications.length === 0) {
                    // Show empty state
                    const notificationContainer = document.querySelector('.notifications-card .p-6');
                    notificationContainer.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h3>No Messages Yet</h3>
                            <p>You don't have any messages or notifications at the moment. We'll notify you when something important happens!</p>
                        </div>
                    `;
                }
            }, 300);
            
            // Show success message
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Message marked as read',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            // Reset button state on error
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
            
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to mark message as read',
                icon: 'error',
                confirmButtonColor: '#ffc107'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Reset button state on error
        buttonElement.innerHTML = originalText;
        buttonElement.disabled = false;
        
        Swal.fire({
            title: 'Error!',
            text: 'Network error. Please try again.',
            icon: 'error',
            confirmButtonColor: '#ffc107'
        });
    });
}

// Add fade-in animation to elements as they come into view
document.addEventListener('DOMContentLoaded', function() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all notification cards
    document.querySelectorAll('.notifications-card, .notification-item').forEach(card => {
        observer.observe(card);
    });
});

// Real-time notifications (if Echo is available)
@if(config('app.env') !== 'local' || isset($enableWebsockets))
try {
    Echo.private('user.{{ auth('user')->id() }}')
        .listen('.message.received', (e) => {
            // Create new notification element
            const notificationContainer = document.querySelector('.notifications-card .p-6');
            const newNotification = document.createElement('div');
            newNotification.className = 'notification-item fade-in';
            newNotification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="notification-body">
                        <div class="notification-message">${e.message}</div>
                        <div class="notification-meta">
                            <div class="notification-time">
                                <i class="fas fa-clock"></i>
                                Just now
                            </div>
                            <div class="notification-status status-unread">
                                New Message
                            </div>
                        </div>
                        <div class="notification-actions">
                            <button class="btn-small btn-primary-small" onclick="markAsRead(${e.id}, this)">
                                <i class="fas fa-check"></i>Mark as Read
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove empty state if it exists
            const emptyState = document.querySelector('.empty-state');
            if (emptyState) {
                emptyState.remove();
            }
            
            // Prepend new notification
            notificationContainer.insertBefore(newNotification, notificationContainer.firstChild);
            
            // Show toast notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'New message received',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
} catch (error) {
    console.log('WebSocket connection not available');
}
@endif
</script>
@endpush
