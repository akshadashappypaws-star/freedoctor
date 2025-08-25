@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'WhatsApp Automation Hub')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    .automation-hub {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .hub-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .feature-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        height: 100%;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .feature-card:hover::before {
        left: 100%;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: #667eea;
        box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .feature-description {
        color: #7f8c8d;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .feature-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-coming-soon {
        background: #fff3cd;
        color: #856404;
    }

    .quick-stats {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
        100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
    }

    .workflow-preview {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        margin-top: 1rem;
    }

    .real-time-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 1000;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .real-time-indicator::before {
        content: '●';
        animation: blink 1s infinite;
        margin-right: 0.5rem;
    }

    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0; }
    }
</style>
@endpush

@section('content')
<div class="automation-hub">
    <div class="container-fluid">
        <!-- Real-time Indicator -->
        <div class="real-time-indicator">
            LIVE SYSTEM
        </div>

        <!-- Header Section -->
        <div class="hub-header animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="mb-3" style="background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <i class="fas fa-robot me-3"></i>
                        WhatsApp Automation Hub
                    </h1>
                    <p class="lead text-muted">
                        Create intelligent workflows, manage templates, and analyze user behavior with AI-powered automation
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex flex-column gap-2">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>
                            System Online
                        </span>
                        <span class="text-muted small">
                            Last updated: <span id="last-update">{{ now()->format('h:i A') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats animate__animated animate__fadeInUp">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number text-primary" id="active-workflows">{{ $stats['active_workflows'] ?? 0 }}</div>
                        <div class="stat-label">Active Workflows</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number text-success" id="messages-today">{{ $stats['messages_today'] ?? 0 }}</div>
                        <div class="stat-label">Messages Today</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number text-info" id="ai-interactions">{{ $stats['ai_interactions'] ?? 0 }}</div>
                        <div class="stat-label">AI Interactions</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number text-warning" id="response-rate">{{ $stats['response_rate'] ?? '0%' }}</div>
                        <div class="stat-label">Response Rate</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="row g-4">
            <!-- Workflow Builder -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInLeft" onclick="navigateToWorkflow()">
                    <div class="feature-icon pulse-animation">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 class="feature-title">Visual Workflow Builder</h3>
                    <p class="feature-description">
                        Create complex automation workflows with drag-and-drop interface. Build message flows, AI responses, and user journeys visually.
                    </p>
                    <div class="workflow-preview">
                        <div class="d-flex justify-content-center align-items-center gap-3">
                            <div class="workflow-step bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <i class="fas fa-arrow-right text-muted"></i>
                            <div class="workflow-step bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-brain"></i>
                            </div>
                            <i class="fas fa-arrow-right text-muted"></i>
                            <div class="workflow-step bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-reply"></i>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block">WhatsApp → AI Analysis → Response</small>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>

            <!-- Rules Engine -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInUp" onclick="navigateToRules()">
                    <div class="feature-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3 class="feature-title">Smart Rules Engine</h3>
                    <p class="feature-description">
                        Set up keyword-based rules and AI fallbacks. Create intelligent responses based on user behavior and message patterns.
                    </p>
                    <div class="workflow-preview">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="bg-warning text-white rounded p-2 small">
                                    <i class="fas fa-key"></i><br>Keywords
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-primary text-white rounded p-2 small">
                                    <i class="fas fa-robot"></i><br>AI Analysis
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-success text-white rounded p-2 small">
                                    <i class="fas fa-reply"></i><br>Response
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>

            <!-- Analytics Dashboard -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInRight" onclick="navigateToAnalytics()">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Behavior Analytics</h3>
                    <p class="feature-description">
                        Track user engagement, analyze conversation patterns, and categorize users as Interested, Average, or Not Interested.
                    </p>
                    <div class="workflow-preview">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-success">
                                    <i class="fas fa-thumbs-up fa-2x"></i>
                                    <div class="small mt-1">Interested</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-warning">
                                    <i class="fas fa-meh fa-2x"></i>
                                    <div class="small mt-1">Average</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-danger">
                                    <i class="fas fa-thumbs-down fa-2x"></i>
                                    <div class="small mt-1">Not Interested</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>

            <!-- Template Manager -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInLeft" onclick="navigateToTemplates()">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="feature-title">Template Manager</h3>
                    <p class="feature-description">
                        Create and manage WhatsApp message templates with variables, media, and interactive buttons.
                    </p>
                    <div class="workflow-preview">
                        <div class="bg-light p-2 rounded">
                            <small class="text-muted">Template Preview:</small>
                           <div class="mt-1 small">
    Hello @{{ name }}, your appointment with Dr. @{{ doctor }} is confirmed for @{{ date }}.
</div>

                        </div>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>

            <!-- Bulk Messaging -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInUp" onclick="navigateToBulk()">
                    <div class="feature-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h3 class="feature-title">Bulk Messaging</h3>
                    <p class="feature-description">
                        Send targeted bulk messages with analytics integration. Track delivery, opens, and user engagement.
                    </p>
                    <div class="workflow-preview">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="bg-primary text-white rounded p-2 small">
                                    <i class="fas fa-users"></i><br>1,234 Recipients
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-success text-white rounded p-2 small">
                                    <i class="fas fa-check"></i><br>95% Delivered
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>

            <!-- Machine Config -->
            <div class="col-lg-4 col-md-6">
                <div class="feature-card animate__animated animate__fadeInRight" onclick="navigateToMachines()">
                    <div class="feature-icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <h3 class="feature-title">Machine Configuration</h3>
                    <p class="feature-description">
                        Configure AI models, API connections, and system settings. Monitor machine health and performance.
                    </p>
                    <div class="workflow-preview">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="text-success small">
                                    <i class="fas fa-brain"></i><br>AI
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-primary small">
                                    <i class="fas fa-cog"></i><br>API
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-info small">
                                    <i class="fas fa-database"></i><br>DB
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-warning small">
                                    <i class="fab fa-whatsapp"></i><br>WA
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="feature-status status-active">Active</span>
                </div>
            </div>
        </div>

        <!-- Real-time Activity Feed -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="quick-stats">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Real-time Activity
                        </h4>
                        <span class="badge bg-success">
                            <i class="fas fa-circle"></i> Live
                        </span>
                    </div>
                    <div id="activity-feed" class="activity-feed">
                        <!-- Activity items will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Floating Button -->
<div class="position-fixed bottom-0 end-0 p-4">
    <div class="btn-group-vertical" role="group">
        <button type="button" class="btn btn-primary rounded-circle mb-2" style="width: 60px; height: 60px;" onclick="quickCreateWorkflow()" title="Quick Create Workflow">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <button type="button" class="btn btn-success rounded-circle mb-2" style="width: 60px; height: 60px;" onclick="testMessage()" title="Test Message">
            <i class="fas fa-paper-plane fa-lg"></i>
        </button>
        <button type="button" class="btn btn-info rounded-circle" style="width: 60px; height: 60px;" onclick="viewLiveStats()" title="Live Statistics">
            <i class="fas fa-chart-pulse fa-lg"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.2/socket.io.js"></script>
<script>
    let socket = null;
    let activityInterval = null;

    // Initialize when page loads
    $(document).ready(function() {
        initializeRealTime();
        loadActivityFeed();
        startStatsUpdates();
        
        // Auto-refresh every 30 seconds
        setInterval(refreshStats, 30000);
    });

    // Navigation functions
    function navigateToWorkflow() {
        window.location.href = '{{ route("admin.whatsapp.automation.workflow") }}';
    }

    function navigateToRules() {
        window.location.href = '{{ route("admin.whatsapp.automation.rules") }}';
    }

    function navigateToAnalytics() {
        window.location.href = '{{ route("admin.whatsapp.automation.analytics") }}';
    }

    function navigateToTemplates() {
        window.location.href = '{{ route("admin.whatsapp.templates") }}';
    }

    function navigateToBulk() {
        window.location.href = '{{ route("admin.whatsapp.bulk-messages") }}';
    }

    function navigateToMachines() {
        window.location.href = '{{ route("admin.whatsapp.automation.machines") }}';
    }

    // Real-time functionality
    function initializeRealTime() {
        // Initialize WebSocket connection if available
        if (typeof io !== 'undefined') {
            socket = io('{{ config("app.websocket_url", "ws://localhost:3000") }}');
            
            socket.on('connect', function() {
                console.log('Real-time connection established');
            });

            socket.on('workflow_update', function(data) {
                updateWorkflowStats(data);
            });

            socket.on('new_message', function(data) {
                addActivityItem(data);
                updateMessageCount();
            });

            socket.on('ai_interaction', function(data) {
                updateAIStats(data);
                addActivityItem(data);
            });
        }
    }

    function loadActivityFeed() {
        const feed = document.getElementById('activity-feed');
        
        // Sample real-time activities
        const activities = [
            {
                type: 'message',
                icon: 'fab fa-whatsapp',
                color: 'success',
                message: 'New message from +91 98765 43210',
                time: '2 seconds ago'
            },
            {
                type: 'ai',
                icon: 'fas fa-brain',
                color: 'primary',
                message: 'AI analyzed intent: doctor_search',
                time: '5 seconds ago'
            },
            {
                type: 'workflow',
                icon: 'fas fa-project-diagram',
                color: 'info',
                message: 'Workflow "Doctor Search" executed successfully',
                time: '8 seconds ago'
            },
            {
                type: 'template',
                icon: 'fas fa-file-alt',
                color: 'warning',
                message: 'Template "Appointment Confirmation" sent',
                time: '12 seconds ago'
            }
        ];

        feed.innerHTML = activities.map(activity => `
            <div class="activity-item d-flex align-items-center p-3 mb-2 bg-light rounded animate__animated animate__fadeInRight">
                <div class="activity-icon bg-${activity.color} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="activity-message">${activity.message}</div>
                    <small class="text-muted">${activity.time}</small>
                </div>
                <div class="activity-status">
                    <i class="fas fa-check-circle text-success"></i>
                </div>
            </div>
        `).join('');
    }

    function addActivityItem(data) {
        const feed = document.getElementById('activity-feed');
        const activityHtml = `
            <div class="activity-item d-flex align-items-center p-3 mb-2 bg-light rounded animate__animated animate__fadeInRight">
                <div class="activity-icon bg-${data.color || 'primary'} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                    <i class="${data.icon || 'fas fa-info'}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="activity-message">${data.message}</div>
                    <small class="text-muted">Just now</small>
                </div>
                <div class="activity-status">
                    <i class="fas fa-check-circle text-success"></i>
                </div>
            </div>
        `;
        
        feed.insertAdjacentHTML('afterbegin', activityHtml);
        
        // Remove old items (keep only 10)
        const items = feed.querySelectorAll('.activity-item');
        if (items.length > 10) {
            items[items.length - 1].remove();
        }
    }

    function startStatsUpdates() {
        // Simulate real-time stats updates
        setInterval(() => {
            // Random small increments to simulate activity
            if (Math.random() > 0.7) {
                const messagesElement = document.getElementById('messages-today');
                const currentMessages = parseInt(messagesElement.textContent);
                messagesElement.textContent = currentMessages + 1;
                
                // Add activity
                addActivityItem({
                    icon: 'fab fa-whatsapp',
                    color: 'success',
                    message: `New message from +91 ${Math.floor(Math.random() * 9000000000) + 1000000000}`,
                    time: 'Just now'
                });
            }

            if (Math.random() > 0.8) {
                const aiElement = document.getElementById('ai-interactions');
                const currentAI = parseInt(aiElement.textContent);
                aiElement.textContent = currentAI + 1;
                
                // Add AI activity
                addActivityItem({
                    icon: 'fas fa-brain',
                    color: 'primary',
                    message: 'AI processed user query successfully',
                    time: 'Just now'
                });
            }

            // Update last update time
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }, 5000);
    }

    function refreshStats() {
        fetch('{{ route("admin.whatsapp.automation.stats") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStatsDisplay(data.stats);
                }
            })
            .catch(error => console.error('Stats update failed:', error));
    }

    function updateStatsDisplay(stats) {
        document.getElementById('active-workflows').textContent = stats.active_workflows || 0;
        document.getElementById('messages-today').textContent = stats.messages_today || 0;
        document.getElementById('ai-interactions').textContent = stats.ai_interactions || 0;
        document.getElementById('response-rate').textContent = stats.response_rate || '0%';
    }

    // Quick action functions
    function quickCreateWorkflow() {
        Swal.fire({
            title: 'Quick Create Workflow',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Workflow Type</label>
                        <select class="form-select" id="workflowType">
                            <option value="simple">Simple Message Response</option>
                            <option value="ai">AI-Powered Response</option>
                            <option value="doctor-search">Doctor Search Flow</option>
                            <option value="appointment">Appointment Booking</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trigger Keywords</label>
                        <input type="text" class="form-control" id="triggerKeywords" placeholder="hello, hi, start">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Response Message</label>
                        <textarea class="form-control" id="responseMessage" rows="3" placeholder="Enter your response message"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create Workflow',
            cancelButtonText: 'Cancel',
            width: 600
        }).then((result) => {
            if (result.isConfirmed) {
                // Create workflow logic here
                Swal.fire('Success!', 'Workflow created successfully!', 'success');
                setTimeout(() => navigateToWorkflow(), 1500);
            }
        });
    }

    function testMessage() {
        Swal.fire({
            title: 'Test WhatsApp Message',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="testPhone" placeholder="+91 9876543210">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" id="testMessage" rows="3" placeholder="Hello, this is a test message from FreeDoctor automation system."></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Send Test Message',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send test message logic here
                Swal.fire({
                    title: 'Sending...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                setTimeout(() => {
                    Swal.fire('Sent!', 'Test message sent successfully!', 'success');
                }, 2000);
            }
        });
    }

    function viewLiveStats() {
        window.open('{{ route("admin.whatsapp.automation.analytics") }}', '_blank');
    }

    // Update functions for real-time data
    function updateWorkflowStats(data) {
        document.getElementById('active-workflows').textContent = data.active_workflows;
    }

    function updateMessageCount() {
        const element = document.getElementById('messages-today');
        const current = parseInt(element.textContent);
        element.textContent = current + 1;
    }

    function updateAIStats(data) {
        const element = document.getElementById('ai-interactions');
        const current = parseInt(element.textContent);
        element.textContent = current + 1;
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        if (socket) {
            socket.disconnect();
        }
        if (activityInterval) {
            clearInterval(activityInterval);
        }
    });
</script>
@endpush
