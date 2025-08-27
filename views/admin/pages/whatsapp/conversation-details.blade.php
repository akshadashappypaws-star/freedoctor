@extends('admin.master')

@section('title', 'Conversation Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h4 font-weight-bold text-dark mb-1">
                                <i class="fab fa-whatsapp text-success mr-2"></i>
                                Conversation: {{ $whatsappNumber }}
                            </h2>
                            @if($userInfo)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $userInfo['name'] ?? 'Guest User' }}
                                    <span class="badge badge-{{ $userInfo['type'] === 'registered' ? 'primary' : 'secondary' }} ml-2">
                                        {{ ucfirst($userInfo['type']) }}
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.whatsapp.conversations') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Conversations
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Conversation Messages -->
                <div class="col-lg-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-comments mr-2"></i>
                                Messages ({{ $stats['total_messages'] ?? 0 }})
                            </h5>
                        </div>
                        <div class="card-body p-0" style="height: 600px; overflow-y: auto;">
                            <!-- Messages Container -->
                            <div class="messages-container p-3" id="messagesContainer">
                                @forelse($messages as $message)
                                    {{-- Display incoming message --}}
                                    @if($message->message)
                                        <div class="message-bubble incoming mb-3 fade-in-message">
                                            <div class="d-flex align-items-start">
                                                <div class="avatar-circle bg-success text-white mr-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="message-content flex-grow-1">
                                                    <div class="bg-light border rounded-lg p-3">
                                                        <p class="mb-1">{{ $message->message }}</p>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $message->created_at->format('M d, Y h:i A') }}
                                                            <span class="badge badge-info ml-2">Customer</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Display bot reply --}}
                                    @if($message->reply)
                                        <div class="message-bubble outgoing mb-3 fade-in-message">
                                            <div class="d-flex align-items-start justify-content-end">
                                                <div class="message-content flex-grow-1 text-right">
                                                    <div class="bg-primary text-white rounded-lg p-3 d-inline-block" style="max-width: 80%;">
                                                        <p class="mb-1">{{ $message->reply }}</p>
                                                        <small class="text-light">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $message->updated_at->format('M d, Y h:i A') }}
                                                            <span class="badge badge-light text-primary ml-2">
                                                                {{ ucfirst($message->reply_type ?? 'bot') }}
                                                            </span>
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="avatar-circle bg-primary text-white ml-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-robot"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No messages yet in this conversation</p>
                                        <p class="text-muted">Start by sending a message to this WhatsApp number: {{ $whatsappNumber }}</p>
                                    </div>
                                @endforelse
                                
                                {{-- Debug Information (remove in production) --}}
                                <div class="alert alert-info mt-3">
                                    <strong>Debug Info:</strong><br>
                                    Total Messages: {{ $messages->count() }}<br>
                                    Messages with content: {{ $messages->whereNotNull('message')->count() }}<br>
                                    Messages with replies: {{ $messages->whereNotNull('reply')->count() }}<br>
                                    Phone: {{ $whatsappNumber }}
                                </div>
                            </div>
                        </div>
                        <!-- Message Input -->
                        <div class="card-footer">
                            <form id="sendMessageForm" class="d-flex">
                                @csrf
                                <input type="text" 
                                       class="form-control rounded-pill mr-2" 
                                       id="messageInput" 
                                       placeholder="Type your message..."
                                       required>
                                <button type="submit" class="btn btn-primary rounded-circle">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar with Stats and Actions -->
                <div class="col-lg-4">
                    <!-- User Info Card -->
                    @if($userInfo)
                    <div class="card shadow border-0 mb-4">
                        <div class="card-header bg-gradient-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-user mr-2"></i>
                                User Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="avatar-circle bg-info text-white mx-auto mb-2" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h5 class="font-weight-bold">{{ $userInfo['name'] ?? 'Guest User' }}</h5>
                                <span class="badge badge-{{ $userInfo['type'] === 'registered' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($userInfo['type']) }}
                                </span>
                            </div>
                            
                            @if($userInfo['type'] === 'registered')
                                <div class="user-details">
                                    <div class="mb-2">
                                        <strong>Phone:</strong> {{ $whatsappNumber }}
                                    </div>
                                    @if(isset($userInfo['email']))
                                        <div class="mb-2">
                                            <strong>Email:</strong> {{ $userInfo['email'] }}
                                        </div>
                                    @endif
                                    @if(isset($userInfo['location']))
                                        <div class="mb-2">
                                            <strong>Location:</strong> {{ $userInfo['location'] }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted text-center">
                                    <p>Unregistered user</p>
                                    <small>Phone: {{ $whatsappNumber }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Conversation Stats -->
                    <div class="card shadow border-0 mb-4">
                        <div class="card-header bg-gradient-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Conversation Stats
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-right">
                                        <h4 class="text-primary">{{ $stats['total_messages'] ?? 0 }}</h4>
                                        <small class="text-muted">Total Messages</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ $stats['replied_messages'] ?? 0 }}</h4>
                                    <small class="text-muted">Replied</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-right">
                                        <h4 class="text-warning">{{ $stats['pending_messages'] ?? 0 }}</h4>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-info">{{ $stats['lead_status'] ?? 'New' }}</h4>
                                    <small class="text-muted">Lead Status</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Actions -->
                    <div class="card shadow border-0">
                        <div class="card-header bg-gradient-warning text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-tools mr-2"></i>
                                Admin Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.whatsapp.conversations.intervene', urlencode($whatsappNumber)) }}" 
                                  method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-hand-paper mr-2"></i>
                                    Intervene Manually
                                </button>
                            </form>

                            <form action="{{ route('admin.whatsapp.conversations.handover', urlencode($whatsappNumber)) }}" 
                                  method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-robot mr-2"></i>
                                    Hand Back to Bot
                                </button>
                            </form>

                            <hr>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    Last activity: 
                                    {{ $messages->last() ? $messages->last()->created_at->diffForHumans() : 'Never' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.message-bubble {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.messages-container {
    scroll-behavior: smooth;
}

.card {
    border-radius: 15px;
}

.rounded-lg {
    border-radius: 10px;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}

#messagesContainer::-webkit-scrollbar {
    width: 6px;
}

#messagesContainer::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#messagesContainer::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#messagesContainer::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Real-time message animations */
.fade-in-message {
    animation: fadeInMessage 0.5s ease-out;
}

@keyframes fadeInMessage {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.new-message-alert {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    z-index: 9999;
    animation: slideDownAlert 0.3s ease-out;
    font-weight: 500;
}

@keyframes slideDownAlert {
    0% {
        opacity: 0;
        transform: translateX(-50%) translateY(-100%);
    }
    100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
}

/* Message bubble hover effects */
.message-bubble:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

/* Typing indicator (for future use) */
.typing-indicator {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 10px;
    margin: 10px 0;
}

.typing-indicator span {
    height: 8px;
    width: 8px;
    border-radius: 50%;
    background: #007bff;
    margin: 0 2px;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

/* Stats update animation */
.stats-updated {
    animation: statsUpdate 0.6s ease-in-out;
}

@keyframes statsUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); background-color: rgba(0, 123, 255, 0.1); }
    100% { transform: scale(1); background-color: transparent; }
}

/* Real-time connection indicator */
.connection-status {
    position: fixed;
    bottom: 20px;
    left: 20px;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    z-index: 1000;
}

.connection-status.connected {
    background: #28a745;
    color: white;
}

.connection-status.connecting {
    background: #ffc107;
    color: #000;
}

.connection-status.disconnected {
    background: #dc3545;
    color: white;
}

.connection-status .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    margin-right: 6px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    const sendForm = document.getElementById('sendMessageForm');
    const messageInput = document.getElementById('messageInput');
    
    let lastMessageTime = new Date().toISOString();
    let isUpdating = false;
    
    // Auto-scroll to bottom of messages
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Handle message sending
    if (sendForm) {
        sendForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Here you can add AJAX call to send message
            // For now, just clear the input
            messageInput.value = '';
            
            // You can implement the actual sending logic here
            console.log('Sending message:', message);
        });
    }
    
    // Real-time message updates - More aggressive checking
    function updateMessages() {
        if (isUpdating) return;
        
        isUpdating = true;
        const whatsappNumber = '{{ $whatsappNumber }}';
        
        // Show connection status
        updateConnectionStatus('connecting');
        
        fetch(`{{ route('admin.whatsapp.conversations.show', ':phone') }}`.replace(':phone', encodeURIComponent(whatsappNumber)) + '?ajax=1&since=' + encodeURIComponent(lastMessageTime), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        })
        .then(response => {
            updateConnectionStatus('connected');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (data.newMessages && data.newMessages.length > 0) {
                    console.log('New messages received:', data.newMessages.length);
                    appendNewMessages(data.newMessages);
                    updateStats(data.stats);
                    lastMessageTime = data.timestamp;
                    
                    // Show new message notification
                    showNewMessageAlert(data.newMessages.length);
                    
                    // Update page title with new message indicator
                    updatePageTitle(data.newMessages.length);
                }
                
                // Always update stats even if no new messages
                if (data.stats) {
                    updateStats(data.stats);
                }
            }
        })
        .catch(error => {
            console.error('Message update error:', error);
            updateConnectionStatus('disconnected');
        })
        .finally(() => {
            isUpdating = false;
        });
    }
    
    function updateConnectionStatus(status) {
        let statusElement = document.getElementById('connectionStatus');
        if (!statusElement) {
            statusElement = document.createElement('div');
            statusElement.id = 'connectionStatus';
            statusElement.className = 'connection-status';
            document.body.appendChild(statusElement);
        }
        
        statusElement.className = `connection-status ${status}`;
        statusElement.innerHTML = `
            <div class="status-dot"></div>
            <span>${status === 'connected' ? 'Live' : status === 'connecting' ? 'Updating...' : 'Disconnected'}</span>
        `;
    }
    
    function updatePageTitle(newMessageCount) {
        const originalTitle = 'Conversation Details';
        if (newMessageCount > 0 && document.hidden) {
            document.title = `(${newMessageCount}) ${originalTitle}`;
            
            // Reset title when page becomes visible
            document.addEventListener('visibilitychange', function resetTitle() {
                if (!document.hidden) {
                    document.title = originalTitle;
                    document.removeEventListener('visibilitychange', resetTitle);
                }
            });
        }
    }
    
    function appendNewMessages(newMessages) {
        newMessages.forEach(message => {
            createMessageBubble(message);
        });
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
    
    function createMessageBubble(message) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message-bubble', 'fade-in-message');
        
        if (message.message) {
            // Incoming message
            messageDiv.classList.add('incoming', 'mb-3');
            messageDiv.innerHTML = `
                <div class="d-flex align-items-start">
                    <div class="avatar-circle bg-success text-white mr-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="message-content flex-grow-1">
                        <div class="bg-light border rounded-lg p-3">
                            <p class="mb-1">${message.message}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatMessageTime(message.created_at)}
                            </small>
                        </div>
                    </div>
                </div>
            `;
        }
        
        if (message.reply) {
            // Outgoing reply
            messageDiv.classList.add('outgoing', 'mb-3');
            messageDiv.innerHTML = `
                <div class="d-flex align-items-start justify-content-end">
                    <div class="message-content flex-grow-1 text-right">
                        <div class="bg-primary text-white rounded-lg p-3 d-inline-block" style="max-width: 80%;">
                            <p class="mb-1">${message.reply}</p>
                            <small class="text-light">
                                <i class="fas fa-clock mr-1"></i>
                                ${formatMessageTime(message.updated_at)}
                                ${message.reply_type ? `<span class="badge badge-light text-primary ml-2">${message.reply_type.charAt(0).toUpperCase() + message.reply_type.slice(1)}</span>` : ''}
                            </small>
                        </div>
                    </div>
                    <div class="avatar-circle bg-primary text-white ml-3" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
            `;
        }
        
        messagesContainer.appendChild(messageDiv);
        
        // Remove animation class after animation completes
        setTimeout(() => {
            messageDiv.classList.remove('fade-in-message');
        }, 500);
    }
    
    function updateStats(stats) {
        if (!stats) return;
        
        // Update message count in header
        const messageCountHeader = document.querySelector('.card-header h5');
        if (messageCountHeader) {
            messageCountHeader.innerHTML = `<i class="fas fa-comments mr-2"></i>Messages (${stats.total_messages || 0})`;
        }
        
        // Update sidebar stats
        const totalMessagesEl = document.querySelector('.text-primary');
        const repliedMessagesEl = document.querySelector('.text-success');
        const pendingMessagesEl = document.querySelector('.text-warning');
        const leadStatusEl = document.querySelector('.text-info');
        
        if (totalMessagesEl) totalMessagesEl.textContent = stats.total_messages || 0;
        if (repliedMessagesEl) repliedMessagesEl.textContent = stats.replied_messages || 0;
        if (pendingMessagesEl) pendingMessagesEl.textContent = stats.pending_messages || 0;
        if (leadStatusEl) leadStatusEl.textContent = stats.lead_status || 'New';
    }
    
    function formatMessageTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
    
    function showNewMessageAlert(count) {
        // Create floating alert
        const alert = document.createElement('div');
        alert.className = 'new-message-alert';
        alert.innerHTML = `
            <i class="fas fa-bell mr-2"></i>
            ${count} new message${count > 1 ? 's' : ''} received
        `;
        
        document.body.appendChild(alert);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
        
        // Play notification sound
        playNotificationSound();
        
        // Show browser notification if permission granted
        showBrowserNotification(count);
    }
    
    function showBrowserNotification(count) {
        if (Notification.permission === 'granted') {
            const notification = new Notification('New WhatsApp Message', {
                body: `${count} new message${count > 1 ? 's' : ''} received from {{ $whatsappNumber }}`,
                icon: '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'whatsapp-message'
            });
            
            // Auto-close after 5 seconds
            setTimeout(() => notification.close(), 5000);
            
            // Focus window when clicked
            notification.onclick = function() {
                window.focus();
                notification.close();
            };
        }
    }
    
    function requestNotificationPermission() {
        if (Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('Notification permission granted');
                }
            });
        }
    }
    
    function playNotificationSound() {
        // Create audio element for notification sound
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJaajuaOSMtkjWvG');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Silently fail if audio can't play
        });
    }
    
    // Start real-time updates every 2 seconds for faster response
    setInterval(updateMessages, 2000);
    
    // Update immediately when page becomes visible
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateMessages();
        }
    });
    
    // Initial setup
    updateConnectionStatus('connected');
    
    // Request notification permission
    requestNotificationPermission();
    
    // Load moment.js for time formatting if not available
    if (typeof moment === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js';
        document.head.appendChild(script);
    }
    
    // Initial message update check
    setTimeout(updateMessages, 1000);
    
    console.log('Real-time conversation updates initialized for:', '{{ $whatsappNumber }}');
});
</script>

@endsection
                      