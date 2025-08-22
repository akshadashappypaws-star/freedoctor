@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Live Conversations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Live Conversations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($statistics))
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $statistics['total_conversations'] ?? 0 }}</h4>
                            <small>Total Conversations</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $statistics['active_conversations'] ?? 0 }}</h4>
                            <small>Active Today</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $statistics['messages_today'] ?? 0 }}</h4>
                            <small>Messages Today</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $statistics['active_workflows'] ?? 0 }}</h4>
                            <small>Active Workflows</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cogs fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Active WhatsApp Conversations
                    </h4>
                    <span class="badge badge-light text-primary">
                        {{ isset($conversations) ? $conversations->total() : 0 }} total
                    </span>
                </div>
                <div class="card-body p-0">
                    @if(isset($conversations) && $conversations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th><i class="fas fa-user mr-1"></i> Contact</th>
                                        <th><i class="fas fa-comment mr-1"></i> Last Message</th>
                                        <th><i class="fas fa-chart-line mr-1"></i> Status</th>
                                        <th><i class="fas fa-hashtag mr-1"></i> Messages</th>
                                        <th><i class="fas fa-clock mr-1"></i> Last Activity</th>
                                        <th><i class="fas fa-tools mr-1"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conversations as $conversation)
                                        @php
                                            $details = $conversationDetails[$conversation->phone] ?? null;
                                        @endphp
                                        <tr data-phone="{{ $conversation->phone }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-success text-white mr-3" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $details['user_name'] ?? 'Unknown User' }}</div>
                                                        <small class="text-muted">{{ $conversation->phone }}</small>
                                                        @if($details && $details['user_type'] === 'registered')
                                                            <span class="badge badge-primary badge-sm ml-1">Registered</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    {{ $details['last_message'] ?? 'No messages' }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status = $details['status'] ?? 'active';
                                                    $badgeClass = match($status) {
                                                        'new' => 'badge-info',
                                                        'interested' => 'badge-warning',
                                                        'qualified' => 'badge-success',
                                                        'closed' => 'badge-secondary',
                                                        default => 'badge-success'
                                                    };
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-primary">{{ $details['message_count'] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $details['last_interaction'] ? \Carbon\Carbon::parse($details['last_interaction'])->diffForHumans() : 'Never' }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.whatsapp.conversations.show', urlencode($conversation->phone)) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Conversation">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success" 
                                                            title="Send Message"
                                                            onclick="sendQuickMessage('{{ $conversation->phone }}')">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            title="Mark as Important"
                                                            onclick="markImportant('{{ $conversation->phone }}')">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $conversations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fab fa-whatsapp fa-5x text-muted mb-4"></i>
                            <h5 class="text-muted">No active conversations</h5>
                            <p class="text-muted">WhatsApp conversations will appear here when users interact with your bot.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.whatsapp.dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-cog mr-2"></i>
                                    Configure WhatsApp
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Status Indicator -->
<div class="real-time-indicator" id="realTimeIndicator">
    <div class="status-dot"></div>
    <span>Live Updates</span>
</div>

<style>
.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.card {
    border-radius: 15px;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge-outline-primary {
    color: #007bff;
    border: 1px solid #007bff;
    background: transparent;
}

/* Real-time update animations */
.pulse-animation {
    animation: pulse 1s ease-in-out;
    background-color: rgba(0, 123, 255, 0.1) !important;
}

@keyframes pulse {
    0% { background-color: rgba(0, 123, 255, 0.3); }
    50% { background-color: rgba(0, 123, 255, 0.1); }
    100% { background-color: transparent; }
}

.badge-pulse {
    animation: badgePulse 0.5s ease-in-out;
}

@keyframes badgePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); background-color: #28a745; }
    100% { transform: scale(1); }
}

.conversation-row-new {
    animation: slideInFromTop 0.5s ease-out;
    background-color: rgba(40, 167, 69, 0.1);
}

@keyframes slideInFromTop {
    0% { 
        opacity: 0;
        transform: translateY(-20px);
    }
    100% { 
        opacity: 1;
        transform: translateY(0);
    }
}

.conversation-row-updated {
    background-color: rgba(255, 193, 7, 0.1) !important;
    transition: background-color 0.3s ease;
}

/* Toast notification styles */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    animation: slideInFromRight 0.3s ease-out;
}

.toast-content {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 12px 16px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    min-width: 250px;
}

.toast-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    margin-left: auto;
    padding: 0 0 0 10px;
}

.toast-close:hover {
    opacity: 0.8;
}

@keyframes slideInFromRight {
    0% {
        opacity: 0;
        transform: translateX(100%);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Statistics card animations */
.card.bg-primary h4,
.card.bg-success h4,
.card.bg-info h4,
.card.bg-warning h4 {
    transition: all 0.3s ease;
}

.card.bg-primary h4.updated,
.card.bg-success h4.updated,
.card.bg-info h4.updated,
.card.bg-warning h4.updated {
    animation: numberUpdate 0.6s ease-in-out;
}

@keyframes numberUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Real-time indicator */
.real-time-indicator {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 12px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.real-time-indicator.updating {
    background: #ffc107;
    color: #000;
}

.real-time-indicator .status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    margin-right: 6px;
    animation: blink 1s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}
</style>

<script>
let lastUpdateTime = new Date().toISOString();
let isUpdating = false;

function sendQuickMessage(phone) {
    // Implement quick message functionality
    console.log('Send message to:', phone);
}

function markImportant(phone) {
    // Implement mark as important functionality
    console.log('Mark important:', phone);
}

// Real-time update function - Enhanced for customer messages
function updateConversations() {
    if (isUpdating) return;
    
    isUpdating = true;
    
    // Update indicator
    const indicator = document.getElementById('realTimeIndicator');
    if (indicator) {
        indicator.classList.add('updating');
        indicator.innerHTML = '<div class="status-dot"></div><span>Checking for updates...</span>';
    }
    
    fetch('{{ route("admin.whatsapp.conversations") }}?ajax=1&since=' + encodeURIComponent(lastUpdateTime), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Dashboard update check:', {
                conversations: data.conversations?.length || 0,
                newMessages: data.newMessageCount || 0,
                timestamp: data.timestamp
            });
            
            updateStatistics(data.statistics);
            updateConversationList(data.conversations);
            lastUpdateTime = data.timestamp;
            
            // Show notification for new messages
            if (data.newMessageCount > 0) {
                showNewMessageNotification(data.newMessageCount);
                updatePageTitleWithCount(data.newMessageCount);
            }
            
            // Update indicator
            if (indicator) {
                indicator.classList.remove('updating');
                indicator.innerHTML = '<div class="status-dot"></div><span>Live Updates</span>';
            }
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        if (indicator) {
            indicator.classList.remove('updating');
            indicator.classList.add('error');
            indicator.innerHTML = '<div class="status-dot"></div><span>Connection Error</span>';
            setTimeout(() => {
                indicator.classList.remove('error');
                indicator.innerHTML = '<div class="status-dot"></div><span>Live Updates</span>';
            }, 3000);
        }
    })
    .finally(() => {
        isUpdating = false;
    });
}

function updatePageTitleWithCount(newCount) {
    const originalTitle = 'Live Conversations';
    if (newCount > 0 && document.hidden) {
        document.title = `(${newCount}) ${originalTitle}`;
        
        // Reset title when page becomes visible
        document.addEventListener('visibilitychange', function resetTitle() {
            if (!document.hidden) {
                document.title = originalTitle;
                document.removeEventListener('visibilitychange', resetTitle);
            }
        });
    }
}

function showBrowserNotification(count) {
    if (Notification.permission === 'granted') {
        const notification = new Notification('New WhatsApp Messages', {
            body: `${count} new message${count > 1 ? 's' : ''} received`,
            icon: '/favicon.ico',
            badge: '/favicon.ico',
            tag: 'whatsapp-conversations'
        });
        
        setTimeout(() => notification.close(), 5000);
        
        notification.onclick = function() {
            window.focus();
            notification.close();
        };
    }
}

function updateStatistics(stats) {
    if (!stats) return;
    
    // Update statistics cards
    const elements = {
        total: document.querySelector('.card.bg-primary h4'),
        active: document.querySelector('.card.bg-success h4'),
        today: document.querySelector('.card.bg-info h4'),
        workflows: document.querySelector('.card.bg-warning h4')
    };
    
    if (elements.total) elements.total.textContent = stats.total_conversations || 0;
    if (elements.active) elements.active.textContent = stats.active_conversations || 0;
    if (elements.today) elements.today.textContent = stats.messages_today || 0;
    if (elements.workflows) elements.workflows.textContent = stats.active_workflows || 0;
}

function updateConversationList(conversations) {
    if (!conversations || conversations.length === 0) return;
    
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;
    
    // Update existing rows or add new ones
    conversations.forEach(conversation => {
        updateOrCreateConversationRow(conversation);
    });
    
    // Add pulse animation to updated rows
    document.querySelectorAll('.conversation-row-updated').forEach(row => {
        row.classList.add('pulse-animation');
        setTimeout(() => {
            row.classList.remove('conversation-row-updated', 'pulse-animation');
        }, 2000);
    });
}

function updateOrCreateConversationRow(conversation) {
    const details = conversation.details;
    if (!details) return;
    
    // Find existing row or create new one
    let existingRow = document.querySelector(`tr[data-phone="${conversation.phone}"]`);
    
    if (existingRow) {
        // Update existing row
        updateExistingRow(existingRow, conversation);
    } else {
        // Create new row
        createNewConversationRow(conversation);
    }
}

function updateExistingRow(row, conversation) {
    const details = conversation.details;
    
    // Update last message
    const messageCell = row.querySelector('.text-truncate');
    if (messageCell && messageCell.textContent !== details.last_message) {
        messageCell.textContent = details.last_message;
        row.classList.add('conversation-row-updated');
    }
    
    // Update message count
    const countBadge = row.querySelector('.badge-outline-primary');
    if (countBadge) {
        const newCount = details.message_count;
        if (countBadge.textContent !== newCount.toString()) {
            countBadge.textContent = newCount;
            countBadge.classList.add('badge-pulse');
            setTimeout(() => countBadge.classList.remove('badge-pulse'), 1000);
        }
    }
    
    // Update last activity time
    const timeCell = row.querySelector('small.text-muted');
    if (timeCell && details.last_interaction) {
        const timeAgo = moment(details.last_interaction).fromNow();
        timeCell.textContent = timeAgo;
    }
}

function createNewConversationRow(conversation) {
    const tbody = document.querySelector('table tbody');
    const details = conversation.details;
    
    const statusBadgeClass = getStatusBadgeClass(details.status);
    const userTypeBadge = details.user_type === 'registered' ? 
        '<span class="badge badge-primary badge-sm ml-1">Registered</span>' : '';
    
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-phone', conversation.phone);
    newRow.classList.add('conversation-row-new');
    
    newRow.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <div class="avatar-circle bg-success text-white mr-3" style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <div class="font-weight-bold">${details.user_name}</div>
                    <small class="text-muted">${conversation.phone}</small>
                    ${userTypeBadge}
                </div>
            </div>
        </td>
        <td>
            <div class="text-truncate" style="max-width: 200px;">
                ${details.last_message}
            </div>
        </td>
        <td>
            <span class="badge ${statusBadgeClass}">${details.status.charAt(0).toUpperCase() + details.status.slice(1)}</span>
        </td>
        <td>
            <span class="badge badge-outline-primary">${details.message_count}</span>
        </td>
        <td>
            <small class="text-muted">
                ${moment(details.last_interaction).fromNow()}
            </small>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a href="/admin/whatsapp/conversations/${encodeURIComponent(conversation.phone)}" 
                   class="btn btn-sm btn-outline-primary" 
                   title="View Conversation">
                    <i class="fas fa-eye"></i>
                </a>
                <button class="btn btn-sm btn-outline-success" 
                        title="Send Message"
                        onclick="sendQuickMessage('${conversation.phone}')">
                    <i class="fas fa-paper-plane"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning" 
                        title="Mark as Important"
                        onclick="markImportant('${conversation.phone}')">
                    <i class="fas fa-star"></i>
                </button>
            </div>
        </td>
    `;
    
    // Insert at the top of the table
    tbody.insertBefore(newRow, tbody.firstChild);
    
    // Add entrance animation
    setTimeout(() => {
        newRow.classList.remove('conversation-row-new');
    }, 100);
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'new': return 'badge-info';
        case 'interested': return 'badge-warning';
        case 'qualified': return 'badge-success';
        case 'closed': return 'badge-secondary';
        default: return 'badge-success';
    }
}

function showNewMessageNotification(count) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fas fa-bell text-primary mr-2"></i>
            <span>${count} new message${count > 1 ? 's' : ''} received</span>
            <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);
    
    // Play notification sound
    playNotificationSound();
    
    // Show browser notification
    showBrowserNotification(count);
}

function playNotificationSound() {
    try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJaajuaOSMtkjWvG');
        audio.volume = 0.3;
        audio.play().catch(() => {
            // Silently fail if audio can't play
        });
    } catch (e) {
        // Ignore audio errors
    }
}

function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Notification permission granted for dashboard');
            }
        });
    }
}

// Start real-time updates every 3 seconds for dashboard
setInterval(updateConversations, 3000);

// Update when page becomes visible
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateConversations();
    }
});

// Initial load and setup
document.addEventListener('DOMContentLoaded', function() {
    // Request notification permission
    requestNotificationPermission();
    
    // Load moment.js for time formatting
    if (typeof moment === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js';
        document.head.appendChild(script);
    }
    
    // Initial update after 1 second
    setTimeout(updateConversations, 1000);
    
    console.log('Real-time conversation dashboard initialized');
});
</script>

@endsection
