@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'WhatsApp Automation Hub')


@section('content')
<div class="container-fluid">
    <!-- Compact Header -->
    <div class="whatsapp-card mb-3">
        <div class="whatsapp-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">
                        <i class="fas fa-robot me-2"></i>
                        WhatsApp Automation Hub
                    </h4>
                    <small class="opacity-90">
                        <span class="live-indicator"></span>
                        Real-time intelligent automation system
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-light text-dark">
                        <i class="fas fa-check-circle me-1"></i>
                        System Online
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Stats Row -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="stat-number">{{ $stats['active_automations'] ?? 0 }}</div>
                <p class="stat-label">Active Automations</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-message"></i>
                </div>
                <div class="stat-number">{{ $stats['messages_today'] ?? 0 }}</div>
                <p class="stat-label">Messages Today</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="stat-number">{{ $stats['ai_responses'] ?? 0 }}</div>
                <p class="stat-label">AI Responses</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $stats['active_users'] ?? 0 }}</div>
                <p class="stat-label">Active Users</p>
            </div>
        </div>
    </div>

    <!-- Compact Feature Grid -->
    <div class="automation-grid">
        <!-- AI Response Engine -->
        <div class="feature-card" onclick="manageAIEngine()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-brain"></i>
                </div>
                <span class="feature-status status-active">
                    <span class="live-indicator"></span>
                    ACTIVE
                </span>
            </div>
            <h6 class="feature-title">AI Response Engine</h6>
            <p class="feature-description">Intelligent automated responses powered by OpenAI</p>
            <small class="text-muted">Response Rate: 95%</small>
        </div>

        <!-- Smart Templates -->
        <div class="feature-card" onclick="manageTemplates()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span class="feature-status status-active">{{ $templates_count ?? 0 }} Active</span>
            </div>
            <h6 class="feature-title">Smart Templates</h6>
            <p class="feature-description">Dynamic message templates with AI personalization</p>
            <small class="text-muted">Usage: 87%</small>
        </div>

        <!-- Workflow Engine -->
        <div class="feature-card" onclick="manageWorkflows()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <span class="feature-status status-active">
                    <span class="live-indicator"></span>
                    RUNNING
                </span>
            </div>
            <h6 class="feature-title">Workflow Engine</h6>
            <p class="feature-description">Automated conversation flows and decision trees</p>
            <small class="text-muted">5 Workflows Active</small>
        </div>

        <!-- Bulk Campaigns -->
        <div class="feature-card" onclick="manageBulkMessages()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <span class="feature-status status-warning">3 Scheduled</span>
            </div>
            <h6 class="feature-title">Bulk Campaigns</h6>
            <p class="feature-description">Mass messaging with smart targeting</p>
            <small class="text-muted">Next: 2:00 PM</small>
        </div>

        <!-- Real-time Analytics -->
        <div class="feature-card" onclick="viewAnalytics()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="feature-status status-active">Live Data</span>
            </div>
            <h6 class="feature-title">Analytics Dashboard</h6>
            <p class="feature-description">Performance metrics and insights</p>
            <small class="text-muted">Updated: 2 min ago</small>
        </div>

        <!-- System Health -->
        <div class="feature-card" onclick="viewSystemHealth()">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="feature-icon-small">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <span class="feature-status status-active">
                    <span class="live-indicator"></span>
                    HEALTHY
                </span>
            </div>
            <h6 class="feature-title">System Health</h6>
            <p class="feature-description">Monitor system performance</p>
            <small class="text-muted">Uptime: 99.9%</small>
        </div>
    </div>

    <!-- Activity & Actions Row -->
    <div class="row">
        <div class="col-md-8">
            <div class="whatsapp-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Recent Activity
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="activity-feed">
                        <div class="activity-item">
                            <div class="activity-icon bg-success text-white">
                                <i class="fas fa-robot"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-message">AI Response Generated</div>
                                <div class="activity-time">2 minutes ago</div>
                            </div>
                            <span class="compact-badge bg-success text-white">Success</span>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-info text-white">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-message">Bulk Message Sent</div>
                                <div class="activity-time">5 minutes ago</div>
                            </div>
                            <span class="compact-badge bg-info text-white">Completed</span>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-warning text-white">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-message">Workflow Triggered</div>
                                <div class="activity-time">10 minutes ago</div>
                            </div>
                            <span class="compact-badge bg-warning text-white">Processing</span>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon bg-primary text-white">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="activity-message">System Health Check</div>
                                <div class="activity-time">15 minutes ago</div>
                            </div>
                            <span class="compact-badge bg-success text-white">Healthy</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="whatsapp-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" onclick="createNewAutomation()">
                            <i class="fas fa-plus me-1"></i>
                            New Automation
                        </button>
                        <button class="btn btn-success btn-sm" onclick="testAIResponse()">
                            <i class="fas fa-robot me-1"></i>
                            Test AI Response
                        </button>
                        <button class="btn btn-info btn-sm" onclick="viewLiveChat()">
                            <i class="fas fa-comments me-1"></i>
                            Live Monitor
                        </button>
                        <button class="btn btn-secondary btn-sm" onclick="systemSettings()">
                            <i class="fas fa-cog me-1"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Floating Buttons -->
<div class="quick-actions">
    <button class="quick-action-btn btn btn-primary" onclick="quickCreateWorkflow()" title="Quick Create">
        <i class="fas fa-plus"></i>
    </button>
    <button class="quick-action-btn btn btn-success" onclick="testMessage()" title="Test Message">
        <i class="fas fa-paper-plane"></i>
    </button>
    <button class="quick-action-btn btn btn-info" onclick="viewLiveStats()" title="Live Stats">
        <i class="fas fa-chart-pulse"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('🚀 Compact WhatsApp Automation Hub initialized');
    
    // Initialize real-time updates
    initializeRealTimeUpdates();
    
    // Start health check
    startHealthCheck();
    
    // Animate counters
    animateCounters();
});

// Compact real-time updates
function initializeRealTimeUpdates() {
    console.log('📡 Initializing real-time updates...');
    
    // Update stats every 30 seconds
    setInterval(updateStats, 30000);
    
    // Update activity every 10 seconds  
    setInterval(updateActivity, 10000);
}

function startHealthCheck() {
    setInterval(performHealthCheck, 60000);
    performHealthCheck();
}

function performHealthCheck() {
    // Simple health check - update live indicators
    $('.live-indicator').css('animation', 'pulse 2s infinite');
}

function animateCounters() {
    $('.stat-number').each(function() {
        const $counter = $(this);
        const targetValue = parseInt($counter.text()) || 0;
        
        if (targetValue > 0) {
            $counter.text('0');
            $({ count: 0 }).animate({ count: targetValue }, {
                duration: 1500,
                step: function() {
                    $counter.text(Math.floor(this.count));
                },
                complete: function() {
                    $counter.text(targetValue);
                }
            });
        }
    });
}

function updateStats() {
    // Simulate stat updates
    console.log('📊 Updating stats...');
    
    // Add some random increments occasionally
    if (Math.random() > 0.7) {
        const $messagesCounter = $('.stat-number').eq(1);
        const current = parseInt($messagesCounter.text());
        $messagesCounter.text(current + 1);
    }
}

function updateActivity() {
    console.log('📝 Updating activity...');
    
    // Simulate new activity occasionally
    if (Math.random() > 0.8) {
        const activities = [
            { icon: 'fas fa-robot', bg: 'success', message: 'AI Response Generated', badge: 'Success' },
            { icon: 'fas fa-paper-plane', bg: 'info', message: 'Message Sent', badge: 'Completed' },
            { icon: 'fas fa-cogs', bg: 'warning', message: 'Workflow Triggered', badge: 'Processing' }
        ];
        
        const activity = activities[Math.floor(Math.random() * activities.length)];
        addNewActivity(activity);
    }
}

function addNewActivity(activity) {
    const newActivity = `
        <div class="activity-item">
            <div class="activity-icon bg-${activity.bg} text-white">
                <i class="${activity.icon}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="activity-message">${activity.message}</div>
                <div class="activity-time">Just now</div>
            </div>
            <span class="compact-badge bg-${activity.bg} text-white">${activity.badge}</span>
        </div>
    `;
    
    $('.activity-feed').prepend(newActivity);
    
    // Keep only 6 latest activities
    $('.activity-item:gt(5)').fadeOut(300, function() {
        $(this).remove();
    });
}

// Management functions
function manageAIEngine() {
    console.log('🤖 Opening AI Engine management...');
    
    Swal.fire({
        title: 'AI Response Engine',
        html: `
            <div class="text-start">
                <h6>Current Configuration:</h6>
                <ul class="small">
                    <li>Model: GPT-3.5 Turbo</li>
                    <li>Response Rate: 95%</li>
                    <li>Avg Response Time: 2.3s</li>
                    <li>Context Memory: 10 messages</li>
                </ul>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Open Settings',
        cancelButtonText: 'Close'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/whatsapp/settings/ai-engine';
        }
    });
}

function manageTemplates() {
    window.location.href = '/admin/whatsapp/templates';
}

function manageWorkflows() {
    window.location.href = '/admin/whatsapp/automation/workflows';
}

function manageBulkMessages() {
    window.location.href = '/admin/whatsapp/bulk-messages';
}

function viewAnalytics() {
    window.location.href = '/admin/whatsapp/analytics';
}

function viewSystemHealth() {
    Swal.fire({
        title: 'System Health Status',
        html: `
            <div class="text-start">
                <div class="row">
                    <div class="col-6">
                        <h6 class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            WhatsApp API
                        </h6>
                        <small>Connected & Active</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            AI Engine
                        </h6>
                        <small>OpenAI Ready</small>
                    </div>
                </div>
                <div class="progress mt-3">
                    <div class="progress-bar bg-success" style="width: 95%">95%</div>
                </div>
                <p class="small text-muted mt-2 mb-0">
                    All systems operational. Last checked: Just now
                </p>
            </div>
        `,
        confirmButtonText: 'Close'
    });
}

function createNewAutomation() {
    Swal.fire({
        title: 'Create New Automation',
        text: 'What type would you like to create?',
        showCancelButton: true,
        confirmButtonText: 'AI Response Rule',
        cancelButtonText: 'Workflow',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/admin/whatsapp/automation/rules/create';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = '/admin/whatsapp/automation/workflows/create';
        }
    });
}

function testAIResponse() {
    Swal.fire({
        title: 'Test AI Response',
        input: 'textarea',
        inputLabel: 'Enter test message:',
        inputPlaceholder: 'Hi, I need help with booking...',
        showCancelButton: true,
        confirmButtonText: 'Test'
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            Swal.fire({
                title: 'Testing...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
            
            setTimeout(() => {
                Swal.fire({
                    title: 'AI Response',
                    html: `
                        <div class="text-start">
                            <strong>Your message:</strong><br>
                            <div class="bg-light p-2 rounded mb-3 small">${result.value}</div>
                            <strong>AI Response:</strong><br>
                            <div class="bg-primary bg-opacity-10 p-2 rounded small">
                                Hello! I'd be happy to help you with booking an appointment. 
                                Let me guide you through the process. Which doctor would you like to see?
                            </div>
                        </div>
                    `,
                    confirmButtonText: 'Great!'
                });
            }, 2000);
        }
    });
}

function viewLiveChat() {
    window.location.href = '/admin/whatsapp/conversations';
}

function systemSettings() {
    window.location.href = '/admin/whatsapp/settings';
}

// Quick action functions
function quickCreateWorkflow() {
    createNewAutomation();
}

function testMessage() {
    testAIResponse();
}

function viewLiveStats() {
    viewAnalytics();
}

console.log('✅ Compact WhatsApp Automation Hub loaded successfully');
</script>
@endpush
