@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'WhatsApp Conversations')

@section('content')
<div class="container-fluid">
    <div class="whatsapp-content">
        <!-- Page Title -->
        <div class="page-title-box">
            <h4 class="gradient-text-green">WhatsApp Conversations</h4>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.9rem;">Monitor and manage live WhatsApp conversations</p>
        </div>

        <!-- Conversation Stats -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="stat-number">{{ $stats['active_conversations'] ?? 0 }}</div>
                    <p class="stat-label">Active Conversations</p>
                    <small style="color: #10b981; font-size: 0.75rem;">
                        <span class="live-indicator"></span>Currently online
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-number">{{ $stats['total_messages'] ?? 0 }}</div>
                    <p class="stat-label">Total Messages</p>
                    <small style="color: #6b7280; font-size: 0.75rem;">Today</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">{{ $stats['avg_response_time'] ?? '2m' }}</div>
                    <p class="stat-label">Avg Response Time</p>
                    <small style="color: #6b7280; font-size: 0.75rem;">Last 24 hours</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-number">{{ $stats['responded_conversations'] ?? 0 }}</div>
                    <p class="stat-label">Responded</p>
                    <small style="color: #8b5cf6; font-size: 0.75rem;">
                        {{ $stats['response_rate'] ?? 0 }}% rate
                    </small>
                </div>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="whatsapp-card">
            <div class="whatsapp-header">
                <h4><i class="fas fa-list me-2"></i>Active WhatsApp Conversations</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm" onclick="refreshConversations()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                    <button class="btn btn-light btn-sm" onclick="filterConversations()">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($conversations) && $conversations->count() > 0)
                    <div class="conversation-list">
                        @foreach($conversations as $conversation)
                        <div class="conversation-item" onclick="viewConversation({{ $conversation->id }})">
                            <div class="conversation-avatar">
                                {{ strtoupper(substr($conversation->whatsapp_number ?? 'U', -1)) }}
                            </div>
                            <div class="conversation-details">
                                <div class="conversation-name">
                                    {{ $conversation->whatsapp_number ?? 'Unknown Contact' }}
                                    @if($conversation->workflow)
                                        <small class="ms-2 text-muted">• {{ $conversation->workflow->name }}</small>
                                    @endif
                                </div>
                                <div class="conversation-last-message">
                                    {{ Str::limit($conversation->message_content ?? 'No messages yet', 60) }}
                                </div>
                            </div>
                            <div class="conversation-meta">
                                <div class="conversation-time">
                                    {{ $conversation->created_at ? $conversation->created_at->diffForHumans() : 'Now' }}
                                </div>
                                <div class="status-badge status-active">
                                    <span class="live-indicator"></span>Active
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #6b7280 0%, #374151 100%);">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Active Conversations</h6>
                        <p class="text-muted mb-0" style="font-size: 0.9rem;">WhatsApp conversations will appear here when users interact with your automation system.</p>
                        <button class="btn btn-primary mt-3" onclick="refreshConversations()">
                            <i class="fas fa-sync-alt me-1"></i>Check for New Messages
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshConversations() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Checking for new conversations',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        location.reload();
    }, 1500);
}

function filterConversations() {
    Swal.fire({
        title: 'Filter Conversations',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Workflow</label>
                    <select class="form-select" id="workflowFilter">
                        <option value="">All Workflows</option>
                        <option value="appointment">Appointment</option>
                        <option value="support">Support</option>
                        <option value="general">General</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Time Range</label>
                    <select class="form-select" id="timeFilter">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="all">All Time</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Apply Filter',
        preConfirm: () => {
            const status = document.getElementById('statusFilter').value;
            const workflow = document.getElementById('workflowFilter').value;
            const time = document.getElementById('timeFilter').value;
            
            // Apply filters (this would normally make an AJAX request)
            let url = new URL(window.location);
            url.searchParams.set('status', status);
            url.searchParams.set('workflow', workflow);
            url.searchParams.set('time', time);
            
            window.location.href = url.toString();
        }
    });
}

function viewConversation(id) {
    // This would normally open a conversation detail modal or page
    Swal.fire({
        title: 'Conversation Details',
        text: 'Opening conversation #' + id + '...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

// Auto-refresh conversations every 30 seconds
setInterval(() => {
    // Check for new conversations without full page reload
    fetch('/admin/whatsapp/conversations/check')
        .then(response => response.json())
        .then(data => {
            if (data.has_new) {
                // Show notification badge or update indicator
                console.log('New conversations available');
            }
        })
        .catch(error => console.log('Conversation check failed:', error));
}, 30000);
</script>
@endpush
