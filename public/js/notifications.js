/**
 * Universal Real-time Notification System for FreeDoctor
 * Handles notifications for Users, Doctors, and Admins
 */

class FreeDoctorNotifications {
    constructor(userType, userId) {
        this.userType = userType; // 'user', 'doctor', 'admin'
        this.userId = userId;
        this.pollingInterval = 15000; // 15 seconds
        this.pollingTimer = null;
        this.isPolling = false;
        this.notificationCount = 0;
        this.notificationQueue = []; // Queue for notifications
        this.isShowingNotification = false; // Flag to prevent overlapping
        this.notificationDelay = 1500; // Delay between notifications (1.5 seconds)
        
        this.init();
    }

    init() {
        console.log(`🔔 Initializing ${this.userType} notifications for ID: ${this.userId}`);
        this.setupToastContainer();
        this.startPolling();
        this.setupEventListeners();
        this.setupRealtimeListening();
    }

    setupToastContainer() {
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
                pointer-events: none;
                display: flex;
                flex-direction: column;
                gap: 12px;
            `;
            document.body.appendChild(container);
        }
    }

    getNotificationEndpoint() {
        const endpoints = {
            'user': '/user/notifications/check-new',
            'doctor': '/doctor/notifications/check-new',
            'admin': '/admin/notifications/check-new'
        };
        return endpoints[this.userType];
    }

    getMarkAsReadEndpoint() {
        const endpoints = {
            'user': '/user/notifications/mark-read',
            'doctor': '/doctor/notifications/mark-read',
            'admin': '/admin/notifications/mark-read'
        };
        return endpoints[this.userType];
    }

    startPolling() {
        if (this.isPolling) return;
        
        console.log(`📡 Starting ${this.userType} notification polling (${this.pollingInterval/1000}s intervals)`);
        this.isPolling = true;
        
        // Initial check
        this.checkNotifications();
        
        // Start polling
        this.pollingTimer = setInterval(() => {
            this.checkNotifications();
        }, this.pollingInterval);
    }

    stopPolling() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer);
            this.pollingTimer = null;
            this.isPolling = false;
            console.log(`⏹️ Stopped ${this.userType} notification polling`);
        }
    }

    async checkNotifications() {
        try {
            const endpoint = this.getNotificationEndpoint();
            console.log(`📡 Checking ${this.userType} notifications: ${endpoint}`);
            
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const data = await response.json();
            console.log(`📡 ${this.userType} polling response:`, data);

            if (data.notifications && data.notifications.length > 0) {
                console.log(`🔔 Found ${data.notifications.length} new ${this.userType} notifications`);
                
                // Add all notifications to queue instead of showing immediately
                data.notifications.forEach(notification => {
                    this.addToQueue(notification);
                });

                // Start processing the queue
                this.processNotificationQueue();

                // Auto-mark as read after showing all notifications
                setTimeout(() => {
                    this.markAsRead(data.notifications.map(n => n.id));
                }, 5000 + (data.notifications.length * this.notificationDelay));
            }

        } catch (error) {
            console.error(`❌ Error checking ${this.userType} notifications:`, error);
        }
    }

    // Add notification to queue
    addToQueue(notification) {
        this.notificationQueue.push(notification);
        console.log(`📋 Added notification to queue. Queue length: ${this.notificationQueue.length}`);
    }

    // Process notification queue one by one
    processNotificationQueue() {
        if (this.isShowingNotification || this.notificationQueue.length === 0) {
            return;
        }

        this.isShowingNotification = true;
        const notification = this.notificationQueue.shift();
        
        console.log(`📨 Processing notification from queue: ${notification.message}`);
        this.showToast(notification);

        // Wait before showing next notification
        setTimeout(() => {
            this.isShowingNotification = false;
            // Process next notification in queue
            if (this.notificationQueue.length > 0) {
                this.processNotificationQueue();
            }
        }, this.notificationDelay);
    }

    showToast(notification) {
        const toastType = this.getNotificationType(notification.type);
        const config = this.getToastConfig(toastType);
        
        console.log(`📨 Showing ${this.userType} toast:`, notification);

        const toast = document.createElement('div');
        toast.className = `notification-toast ${config.bgClass}`;
        toast.style.cssText = `
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            pointer-events: auto;
            transform: translateX(100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid ${config.borderColor};
            backdrop-filter: blur(10px);
            min-width: 350px;
            max-width: 400px;
        `;

        toast.innerHTML = `
            <div class="notification-icon" style="
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: ${config.iconBg};
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
                ">${notification.message}</div>
                <div class="notification-time" style="
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 11px;
                    margin-top: 4px;
                ">${notification.time_ago || 'Just now'}</div>
            </div>
            <button class="notification-close" style="
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.8);
                cursor: pointer;
                padding: 4px;
                border-radius: 4px;
                transition: background-color 0.2s;
                flex-shrink: 0;
            " onclick="this.parentElement.remove()">
                <i class="fas fa-times" style="font-size: 12px;"></i>
            </button>
        `;

        const container = document.getElementById('notification-container');
        container.appendChild(toast);

        // Trigger entrance animation
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);

        // Play notification sound
        this.playNotificationSound();

        // Auto-remove after duration
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, config.duration);
    }

    getNotificationType(type) {
        const typeMap = {
            'business_request': 'business',
            'proposal': 'proposal',
            'approval': 'success',
            'rejection': 'error',
            'assignment': 'info',
            'system': 'info'
        };
        return typeMap[type] || 'info';
    }

    getToastConfig(type) {
        const configs = {
            'business': {
                title: 'New Business Opportunity!',
                icon: 'fas fa-briefcase',
                iconBg: 'linear-gradient(135deg, #3b82f6, #1e40af)',
                bgClass: 'bg-blue-600',
                borderColor: '#3b82f6',
                duration: 8000
            },
            'proposal': {
                title: 'New Proposal Received!',
                icon: 'fas fa-paper-plane',
                iconBg: 'linear-gradient(135deg, #8b5cf6, #7c3aed)',
                bgClass: 'bg-purple-600',
                borderColor: '#8b5cf6',
                duration: 7000
            },
            'success': {
                title: 'Great News!',
                icon: 'fas fa-check-circle',
                iconBg: 'linear-gradient(135deg, #10b981, #059669)',
                bgClass: 'bg-green-600',
                borderColor: '#10b981',
                duration: 6000
            },
            'error': {
                title: 'Important Update',
                icon: 'fas fa-exclamation-circle',
                iconBg: 'linear-gradient(135deg, #ef4444, #dc2626)',
                bgClass: 'bg-red-600',
                borderColor: '#ef4444',
                duration: 9000
            },
            'info': {
                title: 'Information',
                icon: 'fas fa-info-circle',
                iconBg: 'linear-gradient(135deg, #06b6d4, #0891b2)',
                bgClass: 'bg-cyan-600',
                borderColor: '#06b6d4',
                duration: 5000
            }
        };
        return configs[type] || configs['info'];
    }

    async markAsRead(notificationIds) {
        try {
            const endpoint = this.getMarkAsReadEndpoint();
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify({
                    notification_ids: notificationIds
                })
            });

            if (response.ok) {
                console.log(`✅ Marked ${notificationIds.length} ${this.userType} notifications as read`);
            }
        } catch (error) {
            console.error(`❌ Error marking ${this.userType} notifications as read:`, error);
        }
    }

    playNotificationSound() {
        try {
            // Create a subtle notification beep
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0, audioContext.currentTime);
            gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
        } catch (error) {
            // Sound not critical, fail silently
        }
    }

    setupRealtimeListening() {
        // Wait for Echo to be available
        const maxRetries = 10;
        let retryCount = 0;
        
        const trySetupEcho = () => {
            // Check if Echo is available and has the channel method
            if (typeof window.Echo !== 'undefined' && window.Echo !== null && typeof window.Echo.channel === 'function') {
                console.log(`🎯 Setting up real-time listening for ${this.userType} ${this.userId}`);
                
                try {
                    if (this.userType === 'doctor') {
                        // Listen for business request notifications
                        window.Echo.channel(`doctor.${this.userId}`)
                            .listen('.doctor-message.sent', (notification) => {
                                console.log('🔔 Real-time doctor notification received:', notification);
                                
                                // Transform the notification to match our display format
                                const displayNotification = {
                                    id: notification.message.id,
                                    type: notification.message.type || 'business_request',
                                    message: notification.message.message,
                                    time_ago: 'Just now',
                                    created_at: new Date().toISOString()
                                };
                                
                                // Add to queue instead of showing immediately
                                this.addToQueue(displayNotification);
                                this.processNotificationQueue();
                                
                                // Update notification count
                                this.notificationCount++;
                                console.log(`✅ Real-time doctor notification queued for ${this.userType} ${this.userId}`);
                            });
                            
                        console.log(`🎯 Doctor real-time listening setup complete for doctor.${this.userId}`);
                        
                    } else if (this.userType === 'admin') {
                        // Listen for admin notifications (proposals, etc.)
                        window.Echo.channel(`admin.${this.userId}`)
                            .listen('.admin-message.sent', (notification) => {
                                console.log('🔔 Real-time admin notification received:', notification);
                                
                                const displayNotification = {
                                    id: notification.message.id,
                                    type: notification.message.type || 'proposal',
                                    message: notification.message.message,
                                    time_ago: 'Just now',
                                    created_at: new Date().toISOString()
                                };
                                
                                this.addToQueue(displayNotification);
                                this.processNotificationQueue();
                                this.notificationCount++;
                                console.log(`✅ Real-time admin notification queued for ${this.userType} ${this.userId}`);
                            });
                            
                        console.log(`🎯 Admin real-time listening setup complete for admin.${this.userId}`);
                        
                    } else if (this.userType === 'user') {
                        // Listen for user notifications
                        window.Echo.channel(`user.${this.userId}`)
                            .listen('.user-message.sent', (notification) => {
                                console.log('🔔 Real-time user notification received:', notification);
                                
                                const displayNotification = {
                                    id: notification.message.id,
                                    type: notification.message.type || 'info',
                                    message: notification.message.message,
                                    time_ago: 'Just now',
                                    created_at: new Date().toISOString()
                                };
                                
                                this.addToQueue(displayNotification);
                                this.processNotificationQueue();
                                this.notificationCount++;
                                console.log(`✅ Real-time user notification queued for ${this.userType} ${this.userId}`);
                            });
                            
                        console.log(`🎯 User real-time listening setup complete for user.${this.userId}`);
                    }
                } catch (error) {
                    console.error(`❌ Error setting up real-time listening for ${this.userType}:`, error);
                }
            } else {
                retryCount++;
                if (retryCount < maxRetries) {
                    console.log(`⏳ Echo not ready yet for ${this.userType}, retrying... (${retryCount}/${maxRetries})`);
                    setTimeout(trySetupEcho, 1000);
                } else {
                    console.log(`⚠️ Echo setup failed for ${this.userType} after ${maxRetries} retries, using polling only`);
                }
            }
        };
        
        // Start trying to setup Echo after a small delay
        setTimeout(trySetupEcho, 2000);
    }

    setupEventListeners() {
        // Pause polling when tab is not visible
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else {
                this.startPolling();
            }
        });

        // Resume polling on focus
        window.addEventListener('focus', () => {
            if (!this.isPolling) {
                this.startPolling();
            }
        });
    }

    // Manual notification trigger for testing
    testNotification(type = 'info', message = 'This is a test notification') {
        const notification = {
            id: 'test-' + Date.now(),
            type: type,
            message: message,
            time_ago: 'Just now'
        };
        
        this.addToQueue(notification);
        this.processNotificationQueue();
    }
}

// Auto-initialize based on current page context
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 DOM loaded, initializing notifications...');
    
    // Detect user type and initialize notifications
    if (window.location.pathname.startsWith('/doctor/')) {
        // Doctor pages
        const doctorId = document.querySelector('meta[name="doctor-id"]')?.getAttribute('content');
        console.log('🩺 Doctor page detected, doctor ID:', doctorId);
        if (doctorId) {
            window.fdNotifications = new FreeDoctorNotifications('doctor', doctorId);
            console.log('✅ Doctor notifications initialized');
        } else {
            console.log('❌ Doctor ID not found in meta tags');
        }
    } else if (window.location.pathname.startsWith('/admin/')) {
        // Admin pages
        const adminId = document.querySelector('meta[name="admin-id"]')?.getAttribute('content');
        console.log('👨‍💼 Admin page detected, admin ID:', adminId);
        if (adminId) {
            window.fdNotifications = new FreeDoctorNotifications('admin', adminId);
            console.log('✅ Admin notifications initialized');
        } else {
            console.log('❌ Admin ID not found in meta tags');
        }
    } else {
        // User pages
        const userId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        console.log('👤 User page detected, user ID:', userId);
        if (userId) {
            window.fdNotifications = new FreeDoctorNotifications('user', userId);
            console.log('✅ User notifications initialized');
        } else {
            console.log('❌ User ID not found in meta tags');
        }
    }
});

// Global access for manual testing
window.FreeDoctorNotifications = FreeDoctorNotifications;
