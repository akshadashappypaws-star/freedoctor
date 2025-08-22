/**
 * FreeDoctor User Notification System
 * Real-time notifications with Echo/WebSocket and polling fallback
 */

class UserNotificationSystem {
    constructor() {
        this.userId = null;
        this.pollingInterval = 15000; // 15 seconds
        this.pollingTimer = null;
        this.isPolling = false;
        this.lastCheckTime = null;
        this.init();
    }

    init() {
        // Get user ID from meta tag or global variable
        this.userId = this.getUserId();
        if (!this.userId) {
            console.log('❌ User ID not found, notification system disabled');
            return;
        }

        console.log(`🔔 Initializing user notification system for user ID: ${this.userId}`);
        
        this.setupToastContainer();
        
        // Delay Echo setup to allow master template initialization
        setTimeout(() => {
            this.setupEchoNotifications();
        }, 2000);
        
        this.startPollingFallback();
    }

    getUserId() {
        // Try to get user ID from meta tag
        const userIdMeta = document.querySelector('meta[name="user-id"]');
        if (userIdMeta) {
            return userIdMeta.getAttribute('content');
        }

        // Try to get from global variable
        if (typeof window.userId !== 'undefined') {
            return window.userId;
        }

        // Try to get from auth object
        if (typeof window.auth !== 'undefined' && window.auth.user) {
            return window.auth.user.id;
        }

        return null;
    }

    setupToastContainer() {
        if (!document.getElementById('user-notification-container')) {
            const container = document.createElement('div');
            container.id = 'user-notification-container';
            container.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 999999;
                max-width: 400px;
                pointer-events: none;
            `;
            document.body.appendChild(container);
            console.log('✅ User notification container created');
        }
    }

    setupEchoNotifications() {
        // Wait for Echo to be properly initialized by the master template
        const maxAttempts = 10;
        let attempts = 0;
        
        const trySetupEcho = () => {
            attempts++;
            
            // Check if Echo instance is available with channel method
            if (typeof window.Echo !== 'undefined' && 
                window.Echo !== null &&
                typeof window.Echo.channel === 'function') {
                
                try {
                    console.log('🔌 Setting up Echo notifications for user...');
                    console.log('Echo instance available:', !!window.Echo.channel);
                    
                    window.Echo.channel(`user.${this.userId}`)
                        .listen('.message.received', (data) => {
                            console.log('🔔 Real-time user notification received:', data);
                            this.showToast({
                                message: data.message,
                                type: 'referral_earning'
                            });
                            this.playNotificationSound();
                            this.updateNotificationBadge();
                        });

                    console.log('✅ Echo notifications setup successfully for user');
                    return true;
                } catch (error) {
                    console.log('❌ Echo setup failed for user:', error);
                    return false;
                }
            } else if (attempts < maxAttempts) {
                console.log(`⏳ Waiting for Echo initialization... (attempt ${attempts}/${maxAttempts})`);
                setTimeout(trySetupEcho, 1000); // Try again in 1 second
                return false;
            } else {
                console.log('⚠️ Echo not available after maximum attempts, using polling only');
                return false;
            }
        };
        
        // Start trying to setup Echo
        return trySetupEcho();
    }

    startPollingFallback() {
        if (this.isPolling) {
            return;
        }

        console.log('🔄 Starting user notification polling (15 second intervals)');
        this.isPolling = true;
        this.lastCheckTime = new Date();

        // Initial check
        this.checkNotifications();

        // Set up interval
        this.pollingTimer = setInterval(() => {
            this.checkNotifications();
        }, this.pollingInterval);
    }

    stopPolling() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
            this.pollingTimer = null;
            this.isPolling = false;
            console.log('⏹️ Stopped user notification polling');
        }
    }

    async checkNotifications() {
        try {
            console.log('📡 Checking user notifications...');
            
            const response = await fetch('/user/notifications/check-new', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                if (response.status === 401 || response.status === 403) {
                    console.log('🔐 User not authenticated, stopping notification polling');
                    this.stopPolling();
                    return;
                } else if (response.status === 404) {
                    console.log('🔍 Notification endpoint not found, check route configuration');
                    return;
                }
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            console.log('📡 User polling response:', data);

            if (data.notifications && data.notifications.length > 0) {
                console.log(`🔔 Found ${data.notifications.length} new user notifications`);
                
                data.notifications.forEach(notification => {
                    this.showToast(notification);
                    this.updateNotificationBadge();
                });

                // Auto-mark as read after showing
                setTimeout(() => {
                    this.markAsRead(data.notifications.map(n => n.id));
                }, 5000);
            }

        } catch (error) {
            console.error('❌ Error checking user notifications:', error);
            
            // If we get repeated errors, slow down polling
            if (error.message.includes('404')) {
                console.log('🔄 Slowing down polling due to 404 errors');
                this.pollingInterval = 60000; // Slow down to 1 minute
            }
        }
    }

    showToast(notification) {
        const message = notification.message || notification.content || 'New notification';
        const type = notification.type || 'info';
        
        console.log(`🍞 Showing user toast: ${message}`);

        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.user-notification-toast');
        existingToasts.forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'user-notification-toast';
        
        const config = this.getToastConfig(type);
        
        toast.style.cssText = `
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            margin-bottom: 12px;
            border-radius: 12px;
            background: ${config.background};
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            pointer-events: auto;
            transform: translateX(100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid ${config.borderColor};
            backdrop-filter: blur(10px);
            min-width: 300px;
            max-width: 400px;
            color: white;
        `;

        toast.innerHTML = `
            <div class="notification-icon" style="
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: ${config.iconBackground};
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            ">
                <i class="${config.icon}" style="color: white; font-size: 18px;"></i>
            </div>
            <div class="notification-content" style="flex: 1; min-width: 0;">
                <div class="notification-title" style="
                    font-weight: 600;
                    color: white;
                    font-size: 14px;
                    margin-bottom: 4px;
                    line-height: 1.3;
                ">${config.title}</div>
                <div class="notification-message" style="
                    color: rgba(255, 255, 255, 0.9);
                    font-size: 13px;
                    line-height: 1.4;
                    word-wrap: break-word;
                ">${message}</div>
                <div class="notification-time" style="
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 11px;
                    margin-top: 4px;
                ">Just now</div>
            </div>
            <button class="notification-close" style="
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.8);
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            " onclick="this.closest('.user-notification-toast').remove()">
                <i class="fas fa-times"></i>
            </button>
        `;

        const container = document.getElementById('user-notification-container');
        container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Auto remove after 8 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 8000);
    }

    getToastConfig(type) {
        const configs = {
            'success': {
                background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                borderColor: '#10b981',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-check-circle',
                title: 'Success!'
            },
            'error': {
                background: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                borderColor: '#ef4444',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-times-circle',
                title: 'Error!'
            },
            'warning': {
                background: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
                borderColor: '#f59e0b',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-exclamation-triangle',
                title: 'Warning!'
            },
            'camp_proposal_approved': {
                background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                borderColor: '#10b981',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-check-circle',
                title: 'Proposal Approved!'
            },
            'camp_proposal_rejected': {
                background: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
                borderColor: '#ef4444',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-times-circle',
                title: 'Proposal Update'
            },
            'referral_earning': {
                background: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                borderColor: '#10b981',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-coins',
                title: 'Referral Earnings!'
            },
            'registration_confirmed': {
                background: 'linear-gradient(135deg, #3b82f6 0%, #1e40af 100%)',
                borderColor: '#3b82f6',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-check-circle',
                title: 'Registration Confirmed!'
            },
            'info': {
                background: 'linear-gradient(135deg, #3b82f6 0%, #1e40af 100%)',
                borderColor: '#3b82f6',
                iconBackground: 'rgba(255, 255, 255, 0.2)',
                icon: 'fas fa-info-circle',
                title: 'Notification'
            }
        };

        return configs[type] || configs['info'];
    }

    async markAsRead(notificationIds) {
        if (!notificationIds || notificationIds.length === 0) {
            return;
        }

        try {
            const response = await fetch('/user/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    notification_ids: notificationIds
                })
            });

            if (response.ok) {
                console.log(`✅ Marked ${notificationIds.length} user notifications as read`);
            }
        } catch (error) {
            console.error('❌ Error marking user notifications as read:', error);
        }
    }

    playNotificationSound() {
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCGH0+/WgCkEJoHO8daJOAgRaLvt555NEAxPqOHwtmMdBjiS2O/OeyoFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCKG0+/VgCkGJoLM8daJOQgRaL3t4Z5MEAxPpuHxtmQcBjiS2O/OeysFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwiBCGG0+/VfyoII4TL8taCOQkPbbzs4Z9OEAxOpuHytmQcBjaSWG1/PJGhSr4FH/4%3D');
            audio.volume = 0.3;
            audio.play().catch(() => {});
        } catch (e) {
            // Ignore audio errors
        }
    }

    updateNotificationBadge() {
        // Update user notification badge if it exists
        const badge = document.querySelector('.user-notification-badge');
        if (badge) {
            const current = parseInt(badge.textContent) || 0;
            badge.textContent = current + 1;
            badge.style.display = 'inline-block';
        }
    }

    // Public methods for external use
    createTestNotification() {
        this.showToast({
            message: 'This is a test notification for the user portal',
            type: 'info'
        });
    }

    destroy() {
        this.stopPolling();
        const container = document.getElementById('user-notification-container');
        if (container) {
            container.remove();
        }
        console.log('🗑️ User notification system destroyed');
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're in the user portal (check for user auth)
    if (document.querySelector('meta[name="user-authenticated"]') || 
        document.querySelector('body').classList.contains('user-portal') ||
        window.location.pathname.startsWith('/user/') ||
        document.querySelector('[data-user-id]')) {
        
        console.log('🔔 Auto-initializing user notification system...');
        
        // Delay initialization to ensure authentication and Echo are ready
        setTimeout(() => {
            window.userNotificationSystem = new UserNotificationSystem();
        }, 3000); // 3 second delay
    }
});

// Also try to initialize when window loads (backup)
window.addEventListener('load', function() {
    if (!window.userNotificationSystem && 
        document.querySelector('meta[name="user-authenticated"]')) {
        console.log('🔔 Backup initialization of user notification system...');
        setTimeout(() => {
            window.userNotificationSystem = new UserNotificationSystem();
        }, 2000);
    }
});

// Export for manual initialization
window.UserNotificationSystem = UserNotificationSystem;
