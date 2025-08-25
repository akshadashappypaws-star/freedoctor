@extends('layouts.app')

@section('title', 'WhatsApp Webhook Monitor & Debugger')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-satellite-dish"></i> WhatsApp Webhook Monitor & Debugger
                    </h3>
                    <div>
                        <button id="refreshLogs" class="btn btn-primary btn-sm">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                        <button id="clearLogs" class="btn btn-danger btn-sm ml-2">
                            <i class="fas fa-trash"></i> Clear Logs
                        </button>
                        <a href="{{ route('admin.webhook.debug') }}" class="btn btn-info btn-sm ml-2">
                            <i class="fas fa-bug"></i> Debug Tools
                        </a>
                        <div class="form-check form-switch d-inline-block ml-3">
                            <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                            <label class="form-check-label" for="autoRefresh">Auto Refresh</label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Environment Information Panel -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5><i class="fas fa-cog"></i> Environment Configuration</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>App Environment:</strong><br>
                                            <span class="badge bg-{{ ($envInfo['app_env'] ?? 'local') === 'production' ? 'success' : 'warning' }}">
                                                {{ strtoupper($envInfo['app_env'] ?? 'LOCAL') }}
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>App URL:</strong><br>
                                            <code>{{ $envInfo['app_url'] ?? 'Not configured' }}</code>
                                        </div>
                                        <div class="col-md-5">
                                            <strong>Webhook URL:</strong><br>
                                            <code>{{ $envInfo['webhook_url'] ?? 'Not configured' }}</code>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Verify Token:</strong><br>
                                            <code>{{ $envInfo['verify_token'] ?? 'Not set' }}</code>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Phone Number ID:</strong><br>
                                            <code>{{ $envInfo['phone_number_id'] ?? 'Not set' }}</code>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Business Account ID:</strong><br>
                                            <code>{{ $envInfo['business_account_id'] ?? 'Not set' }}</code>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Database:</strong><br>
                                            <code>{{ $envInfo['db_database'] ?? 'Not configured' }}</code>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>DB Host:</strong><br>
                                            <code>{{ $envInfo['db_host'] ?? 'localhost' }}</code>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>PHP Version:</strong><br>
                                            <code>{{ $envInfo['php_version'] ?? 'Unknown' }}</code>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Laravel Version:</strong><br>
                                            <code>{{ $envInfo['laravel_version'] ?? 'Unknown' }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Statistics Cards -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-link"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Logs</span>
                                    <span class="info-box-number" id="totalRequests">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-comments"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Messages</span>
                                    <span class="info-box-number" id="totalMessages">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Errors</span>
                                    <span class="info-box-number" id="totalErrors">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Last Request</span>
                                    <span class="info-box-number" id="lastRequest" style="font-size: 12px;">Never</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-sync"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Status</span>
                                    <span class="info-box-number" id="status">Monitoring...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Process Flow Visualization -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-sitemap"></i> WhatsApp Webhook Process Flow</h5>
                                </div>
                                <div class="card-body">
                                    <div class="process-flow">
                                        <div class="process-step" id="step1">
                                            <div class="step-icon"><i class="fab fa-whatsapp"></i></div>
                                            <div class="step-title">WhatsApp Sends</div>
                                            <div class="step-desc">User message → Meta API</div>
                                            <div class="step-status" id="step1-status">Waiting...</div>
                                        </div>
                                        <div class="process-arrow">→</div>
                                        <div class="process-step" id="step2">
                                            <div class="step-icon"><i class="fas fa-globe"></i></div>
                                            <div class="step-title">Webhook Received</div>
                                            <div class="step-desc">{{ $envInfo['webhook_url'] ?? 'Not configured' }}</div>
                                            <div class="step-status" id="step2-status">Ready</div>
                                        </div>
                                        <div class="process-arrow">→</div>
                                        <div class="process-step" id="step3">
                                            <div class="step-icon"><i class="fas fa-cogs"></i></div>
                                            <div class="step-title">Laravel Processes</div>
                                            <div class="step-desc">WebhookController.php</div>
                                            <div class="step-status" id="step3-status">Ready</div>
                                        </div>
                                        <div class="process-arrow">→</div>
                                        <div class="process-step" id="step4">
                                            <div class="step-icon"><i class="fas fa-database"></i></div>
                                            <div class="step-title">Data Stored</div>
                                            <div class="step-desc">Database + Logs</div>
                                            <div class="step-status" id="step4-status">Ready</div>
                                        </div>
                                        <div class="process-arrow">→</div>
                                        <div class="process-step" id="step5">
                                            <div class="step-icon"><i class="fas fa-reply"></i></div>
                                            <div class="step-title">Response Sent</div>
                                            <div class="step-desc">200 OK to WhatsApp</div>
                                            <div class="step-status" id="step5-status">Ready</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Test Webhook Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-flask"></i> Test Webhook Endpoints</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <strong>Test URLs:</strong><br>
                                        <p><strong>Local:</strong> <code>http://127.0.0.1:8000/api/webhook/whatsapp</code></p>
                                        <p><strong>Production:</strong> <code>{{ $envInfo['webhook_url'] ?? 'Not configured' }}</code></p>
                                        <p><strong>API Version:</strong> <code>v23.0 (Latest)</code></p>
                                        <button id="testWebhook" class="btn btn-info">
                                            <i class="fas fa-play"></i> Test Local
                                        </button>
                                        <button id="testProductionWebhook" class="btn btn-warning ml-2">
                                            <i class="fas fa-globe"></i> Test Production
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-paper-plane"></i> Send Test Message</h5>
                                </div>
                                <div class="card-body">
                                    <form id="testMessageForm">
                                        <div class="form-group">
                                            <label for="testPhoneNumber">Phone Number:</label>
                                            <input type="text" id="testPhoneNumber" class="form-control" value="+918519931876" placeholder="+918519931876">
                                        </div>
                                        <div class="form-group">
                                            <label for="testMessage">Message:</label>
                                            <textarea id="testMessage" class="form-control" rows="3" placeholder="Test message...">Hello! This is a test message from FreeDoctorCORPO webhook system.</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-paper-plane"></i> Send Test Message
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabbed Logs Display -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <ul class="nav nav-tabs card-header-tabs" id="logTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="all-logs-tab" data-bs-toggle="tab" href="#all-logs" role="tab">
                                                <i class="fas fa-list"></i> All Logs <span id="allLogsCount" class="badge bg-primary">0</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="received-messages-tab" data-bs-toggle="tab" href="#received-messages" role="tab">
                                                <i class="fas fa-inbox"></i> Received Messages <span id="receivedCount" class="badge bg-info">0</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="messages-tab" data-bs-toggle="tab" href="#messages" role="tab">
                                                <i class="fas fa-comments"></i> Messages <span id="messagesCount" class="badge bg-success">0</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="errors-tab" data-bs-toggle="tab" href="#errors" role="tab">
                                                <i class="fas fa-exclamation-triangle"></i> Errors <span id="errorsCount" class="badge bg-danger">0</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="statistics-tab" data-bs-toggle="tab" href="#statistics" role="tab">
                                                <i class="fas fa-chart-pie"></i> Statistics
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="logTabContent">
                                        <div class="tab-pane fade show active" id="all-logs" role="tabpanel">
                                            <div id="webhookLogs" style="max-height: 600px; overflow-y: auto;">
                                                <div class="text-center">
                                                    <i class="fas fa-spinner fa-spin"></i> Loading logs...
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="received-messages" role="tabpanel">
                                            <div class="mb-3">
                                                <button id="refreshReceivedMessages" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-sync"></i> Refresh Received Messages
                                                </button>
                                                <span class="text-muted ml-3">Shows messages received via WhatsApp webhook</span>
                                            </div>
                                            <div id="receivedMessages" style="max-height: 600px; overflow-y: auto;">
                                                <div class="text-center">
                                                    <i class="fas fa-spinner fa-spin"></i> Loading received messages...
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="messages" role="tabpanel">
                                            <div id="messageLogs" style="max-height: 600px; overflow-y: auto;">
                                                <div class="text-center text-muted">No message logs available</div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="errors" role="tabpanel">
                                            <div id="errorLogs" style="max-height: 600px; overflow-y: auto;">
                                                <div class="text-center text-muted">No error logs found</div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="statistics" role="tabpanel">
                                            <div id="statisticsContent">
                                                <div class="text-center text-muted">Loading statistics...</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.log-entry {
    border-bottom: 1px solid #eee;
    padding: 10px;
    margin-bottom: 5px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    border-left: 4px solid transparent;
}

.log-entry.new {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.log-entry.error {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

.log-entry.warning {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}

.log-entry.info {
    background-color: #d1ecf1;
    border-left: 4px solid #17a2b8;
}

.log-timestamp {
    color: #6c757d;
    font-weight: bold;
}

.log-message {
    margin-top: 5px;
    word-break: break-all;
}

.log-type {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 10px;
    font-weight: bold;
    margin-left: 10px;
}

.log-type.error { background-color: #dc3545; color: white; }
.log-type.warning { background-color: #ffc107; color: black; }
.log-type.info { background-color: #17a2b8; color: white; }
.log-type.message { background-color: #28a745; color: white; }
.log-type.verification { background-color: #6f42c1; color: white; }

.structured-data {
    margin-top: 8px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 4px;
    font-size: 11px;
}

.process-flow {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    margin: 20px 0;
}

.process-step {
    text-align: center;
    padding: 15px;
    border: 2px solid #e3e3e3;
    border-radius: 8px;
    background-color: #fff;
    min-width: 150px;
    transition: all 0.3s ease;
}

.process-step.active {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.process-step.success {
    border-color: #28a745;
    background-color: #d4edda;
}

.process-step.error {
    border-color: #dc3545;
    background-color: #f8d7da;
}

.step-icon {
    font-size: 24px;
    margin-bottom: 8px;
    color: #6c757d;
}

.step-title {
    font-weight: bold;
    margin-bottom: 4px;
}

.step-desc {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 8px;
    word-break: break-all;
}

.step-status {
    font-size: 11px;
    padding: 2px 6px;
    border-radius: 10px;
    background-color: #e9ecef;
    color: #495057;
}

.process-arrow {
    font-size: 24px;
    color: #6c757d;
    margin: 0 10px;
}

@media (max-width: 768px) {
    .process-flow {
        flex-direction: column;
    }
    .process-arrow {
        transform: rotate(90deg);
        margin: 10px 0;
    }
}

.statistics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    color: #495057;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 5px;
}

/* Received Messages Styles */
.received-message-entry {
    border: 1px solid #e3e3e3;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.received-message-entry.new {
    border-color: #17a2b8;
    background-color: #d1ecf1;
    box-shadow: 0 2px 4px rgba(23, 162, 184, 0.1);
}

.received-message-entry:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.message-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.phone-number {
    font-weight: bold;
    color: #495057;
}

.message-timestamp {
    font-size: 12px;
    color: #6c757d;
}

.message-source {
    font-size: 10px;
}

.message-type {
    font-size: 10px;
}

.message-content {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 10px;
    margin-bottom: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    line-height: 1.4;
    word-wrap: break-word;
}

.webhook-data {
    background-color: #e9ecef;
    border-radius: 4px;
    padding: 8px;
    margin-top: 8px;
    font-size: 11px;
}

.webhook-data pre {
    margin: 0;
    background: none;
    border: none;
    padding: 0;
    font-size: 10px;
}

.raw-log {
    background-color: #f1f3f4;
    border-radius: 4px;
    padding: 6px;
    margin-top: 6px;
    font-size: 10px;
    border-left: 3px solid #6c757d;
}

@media (max-width: 768px) {
    .message-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .message-info {
        width: 100%;
        justify-content: space-between;
    }
}

/* Tab functionality improvements */
.nav-tabs .nav-link {
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-tabs .nav-link:hover {
    background-color: #f8f9fa;
    border-color: #e9ecef #e9ecef #dee2e6;
}

.nav-tabs .nav-link.active {
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    color: #495057;
}

.tab-pane {
    display: none;
}

.tab-pane.show.active {
    display: block;
}

/* Loading animation improvements */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<script>
let autoRefreshInterval;
let lastLogCount = 0;

$(document).ready(function() {
    loadLogs();
    startAutoRefresh();
    
    // Tab switching handlers using manual jQuery approach
    $('a[data-bs-toggle="tab"]').click(function (e) {
        e.preventDefault();
        const target = $(this).attr("href");
        
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab and its content
        $(this).addClass('active');
        $(target).addClass('show active');
        
        // Load specific data when tabs are activated
        if (target === '#received-messages') {
            loadReceivedMessages();
        } else if (target === '#all-logs') {
            loadLogs();
        }
    });
    
    // Initial load of received messages when page loads
    loadReceivedMessages();
    
    $('#refreshLogs').click(function() {
        loadLogs();
    });
    
    $('#refreshReceivedMessages').click(function() {
        loadReceivedMessages();
    });
    
    $('#clearLogs').click(function() {
        if (confirm('Are you sure you want to clear all webhook logs?')) {
            clearLogs();
        }
    });
    
    $('#autoRefresh').change(function() {
        if (this.checked) {
            startAutoRefresh();
        } else {
            stopAutoRefresh();
        }
    });
    
    $('#testWebhook').click(function() {
        testWebhook('local');
    });
    
    $('#testProductionWebhook').click(function() {
        testWebhook('production');
    });
    
    $('#testMessageForm').submit(function(e) {
        e.preventDefault();
        sendTestMessage();
    });
    
    // Add click handlers for tab navigation using jQuery instead of Bootstrap API
    $('#received-messages-tab').click(function(e) {
        e.preventDefault();
        console.log('Received Messages tab clicked');
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab
        $(this).addClass('active');
        $('#received-messages').addClass('show active');
        
        loadReceivedMessages();
    });
    
    $('#all-logs-tab').click(function(e) {
        e.preventDefault();
        console.log('All Logs tab clicked');
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab
        $(this).addClass('active');
        $('#all-logs').addClass('show active');
        
        loadLogs();
    });
    
    $('#messages-tab').click(function(e) {
        e.preventDefault();
        console.log('Messages tab clicked');
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab
        $(this).addClass('active');
        $('#messages').addClass('show active');
    });
    
    $('#errors-tab').click(function(e) {
        e.preventDefault();
        console.log('Errors tab clicked');
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab
        $(this).addClass('active');
        $('#errors').addClass('show active');
    });
    
    $('#statistics-tab').click(function(e) {
        e.preventDefault();
        console.log('Statistics tab clicked');
        // Remove active from all tabs
        $('.nav-link').removeClass('active');
        $('.tab-pane').removeClass('show active');
        
        // Add active to this tab
        $(this).addClass('active');
        $('#statistics').addClass('show active');
    });
});

function loadLogs() {
    $.ajax({
        url: '{{ route("admin.webhook.logs") }}',
        method: 'GET',
        success: function(response) {
            displayLogs(response.logs || [], 'webhookLogs');
            displayLogs(response.message_logs || [], 'messageLogs');
            displayLogs(response.error_logs || [], 'errorLogs');
            displayReceivedMessages(response.received_messages || []);
            displayStatistics(response.statistics || {});
            
            // Update counters
            $('#totalRequests').text(response.count || 0);
            $('#totalMessages').text(response.message_count || 0);
            $('#totalErrors').text(response.error_count || 0);
            
            // Update tab badges
            $('#allLogsCount').text(response.count || 0);
            $('#messagesCount').text(response.message_count || 0);
            $('#errorsCount').text(response.error_count || 0);
            $('#receivedCount').text(response.received_count || 0);
            
            if (response.logs && response.logs.length > 0) {
                $('#lastRequest').text(response.logs[0].timestamp);
                animateProcessStep('step2', 'success');
            }
            
            // Highlight new logs and animate process
            if ((response.count || 0) > lastLogCount) {
                $('#status').text('New Request!').css('color', '#28a745');
                animateProcessFlow();
                setTimeout(() => {
                    $('#status').text('Monitoring...').css('color', '');
                }, 3000);
            }
            lastLogCount = response.count || 0;
        },
        error: function(xhr) {
            $('#webhookLogs').html('<div class="alert alert-danger">Error loading logs: ' + xhr.statusText + '</div>');
        }
    });
}

function displayLogs(logs, containerId) {
    let html = '';
    
    if (logs.length === 0) {
        html = '<div class="text-center text-muted">No logs available</div>';
    } else {
        logs.forEach(function(log, index) {
            let isNew = index < 3; // Mark first 3 as new
            let severity = log.severity || 'info';
            let type = log.type || 'general';
            
            html += `
                <div class="log-entry ${isNew ? 'new' : ''} ${severity}">
                    <div class="log-timestamp">
                        ${escapeHtml(log.timestamp)}
                        <span class="log-type ${type}">${type.toUpperCase()}</span>
                        <span class="log-type ${severity}">${severity.toUpperCase()}</span>
                    </div>
                    <div class="log-message">${escapeHtml(log.message)}</div>
                    ${log.structured_data && Object.keys(log.structured_data).length > 0 ? 
                        '<div class="structured-data"><strong>Data:</strong> ' + 
                        escapeHtml(JSON.stringify(log.structured_data, null, 2)) + 
                        '</div>' : ''}
                </div>
            `;
        });
    }
    
    $('#' + containerId).html(html);
}

function displayStatistics(stats) {
    let html = '<div class="statistics-grid">';
    
    // Total logs
    html += `
        <div class="stat-card">
            <div class="stat-value">${stats.total_logs || 0}</div>
            <div class="stat-label">Total Logs</div>
        </div>
    `;
    
    // By type
    if (stats.by_type) {
        Object.keys(stats.by_type).forEach(type => {
            html += `
                <div class="stat-card">
                    <div class="stat-value">${stats.by_type[type]}</div>
                    <div class="stat-label">${type.replace('_', ' ').toUpperCase()}</div>
                </div>
            `;
        });
    }
    
    html += '</div>';
    
    // Recent errors
    if (stats.recent_errors && stats.recent_errors.length > 0) {
        html += '<hr><h6><i class="fas fa-exclamation-triangle text-danger"></i> Recent Errors:</h6>';
        html += '<div style="max-height: 300px; overflow-y: auto;">';
        stats.recent_errors.forEach(error => {
            html += `
                <div class="alert alert-danger alert-sm">
                    <small><strong>${error.timestamp}:</strong> ${escapeHtml(error.message)}</small>
                </div>
            `;
        });
        html += '</div>';
    }
    
    $('#statisticsContent').html(html);
}

function animateProcessFlow() {
    // Reset all steps
    $('.process-step').removeClass('active success error');
    
    // Animate each step
    setTimeout(() => animateProcessStep('step1', 'active'), 100);
    setTimeout(() => animateProcessStep('step2', 'active'), 300);
    setTimeout(() => animateProcessStep('step3', 'active'), 500);
    setTimeout(() => animateProcessStep('step4', 'active'), 700);
    setTimeout(() => animateProcessStep('step5', 'success'), 900);
    
    // Update status text
    setTimeout(() => $('#step1-status').text('Message Sent'), 100);
    setTimeout(() => $('#step2-status').text('Received'), 300);
    setTimeout(() => $('#step3-status').text('Processing'), 500);
    setTimeout(() => $('#step4-status').text('Stored'), 700);
    setTimeout(() => $('#step5-status').text('Success'), 900);
}

function animateProcessStep(stepId, className) {
    $('#' + stepId).addClass(className);
}

function displayReceivedMessages(messages) {
    let html = '';
    
    if (messages.length === 0) {
        html = '<div class="text-center text-muted">No received messages found</div>';
    } else {
        messages.forEach(function(message, index) {
            let isNew = index < 3; // Mark first 3 as new
            let source = message.source || 'unknown';
            let messageType = message.message_type || 'text';
            
            html += `
                <div class="received-message-entry ${isNew ? 'new' : ''}" data-source="${source}">
                    <div class="message-header">
                        <div class="message-info">
                            <span class="phone-number"><i class="fas fa-phone"></i> ${escapeHtml(message.phone_number)}</span>
                            <span class="message-timestamp">${escapeHtml(message.timestamp)}</span>
                            <span class="message-source badge bg-${source === 'database' ? 'success' : 'info'}">${source.toUpperCase()}</span>
                            <span class="message-type badge bg-secondary">${messageType.toUpperCase()}</span>
                        </div>
                    </div>
                    <div class="message-content">${escapeHtml(message.message)}</div>
                    ${message.webhook_data && Object.keys(message.webhook_data).length > 0 ? 
                        '<div class="webhook-data"><strong>Webhook Data:</strong><br><pre>' + 
                        escapeHtml(JSON.stringify(message.webhook_data, null, 2)) + 
                        '</pre></div>' : ''}
                    ${message.raw_log ? 
                        '<div class="raw-log"><small><strong>Raw Log:</strong><br>' + 
                        escapeHtml(message.raw_log) + 
                        '</small></div>' : ''}
                </div>
            `;
        });
    }
    
    $('#receivedMessages').html(html);
}

function loadReceivedMessages() {
    console.log('Loading received messages...');
    $.ajax({
        url: '{{ route("admin.webhook.received-messages") }}',
        method: 'GET',
        success: function(response) {
            console.log('Received messages response:', response);
            displayReceivedMessages(response.messages || []);
            $('#receivedCount').text(response.count || 0);
            
            // Update last updated time
            if (response.last_updated) {
                const lastUpdated = new Date(response.last_updated).toLocaleString();
                $('#receivedMessages').prepend(`
                    <div class="text-muted small mb-2">
                        <i class="fas fa-clock"></i> Last updated: ${lastUpdated}
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('Error loading received messages:', xhr);
            $('#receivedMessages').html('<div class="alert alert-danger">Error loading received messages: ' + xhr.statusText + '</div>');
        }
    });
}

function clearLogs() {
    $.ajax({
        url: '{{ route("admin.webhook.clear") }}',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        success: function() {
            loadLogs();
            alert('Logs cleared successfully!');
        },
        error: function(xhr) {
            alert('Error clearing logs: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}

function testWebhook(type = 'local') {
    let testUrl;
    
    if (type === 'local') {
        testUrl = 'http://127.0.0.1:8000/api/webhook/whatsapp'
                + '?hub.mode=subscribe'
                + '&hub.verify_token={{ $envInfo["verify_token"] ?? "FreeDoctor2025SecureToken" }}'
                + '&hub.challenge=test123';
    } else {
        testUrl = '{{ $envInfo["webhook_url"] ?? "https://freedoctor.in/webhook/whatsapp" }}'
                + '?hub.mode=subscribe'
                + '&hub.verify_token={{ $envInfo["verify_token"] ?? "FreeDoctor2025SecureToken" }}'
                + '&hub.challenge=test123';
    }
    
    const buttonText = type === 'local' ? 'Testing Local...' : 'Testing Production...';
    const buttonId = type === 'local' ? '#testWebhook' : '#testProductionWebhook';
    
    $(buttonId).html('<i class="fas fa-spinner fa-spin"></i> ' + buttonText).prop('disabled', true);
    
    // Animate process during test
    animateProcessFlow();
    
    $.ajax({
        url: testUrl,
        method: 'GET',
        timeout: 10000,
        success: function(response) {
            alert('✅ ' + (type === 'local' ? 'Local' : 'Production') + ' webhook test successful! Response: ' + response);
            setTimeout(() => loadLogs(), 1000);
        },
        error: function(xhr) {
            let errorMsg = '❌ ' + (type === 'local' ? 'Local' : 'Production') + ' webhook test failed!\n';
            errorMsg += 'Status: ' + xhr.status + '\n';
            errorMsg += 'Error: ' + (xhr.statusText || 'Connection failed');
            
            if (xhr.status === 0) {
                errorMsg += '\n\n💡 Troubleshooting:\n';
                if (type === 'local') {
                    errorMsg += '- Laravel server not running\n- Check http://127.0.0.1:8000\n- Run: php artisan serve';
                } else {
                    errorMsg += '- Production server down\n- Domain not accessible\n- Check {{ $envInfo["webhook_url"] }}';
                }
            }
            
            alert(errorMsg);
            
            // Show error in process flow
            $('.process-step').removeClass('active success').addClass('error');
        },
        complete: function() {
            $(buttonId).html('<i class="fas fa-play"></i> Test ' + (type === 'local' ? 'Local' : 'Production') + ' Webhook').prop('disabled', false);
        }
    });
}

function sendTestMessage() {
    const phoneNumber = $('#testPhoneNumber').val();
    const message = $('#testMessage').val();
    
    if (!phoneNumber || !message) {
        alert('Please enter both phone number and message');
        return;
    }
    
    const submitBtn = $('#testMessageForm button[type="submit"]');
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Sending...').prop('disabled', true);
    
    $.ajax({
        url: '{{ route("admin.webhook.send-test") }}',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        data: {
            phone_number: phoneNumber,
            message: message
        },
        success: function(response) {
            alert('✅ Test message sent successfully!\n\nPhone: ' + response.phone_number + '\nMessage: ' + response.sent_message);
            
            // Animate process flow
            animateProcessFlow();
            
            // Refresh logs after a short delay
            setTimeout(() => loadLogs(), 1000);
        },
        error: function(xhr) {
            let errorMsg = '❌ Failed to send test message!\n';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMsg += 'Error: ' + response.message;
            } catch (e) {
                errorMsg += 'Status: ' + xhr.status + ' ' + xhr.statusText;
            }
            alert(errorMsg);
        },
        complete: function() {
            submitBtn.html('<i class="fas fa-paper-plane"></i> Send Test Message').prop('disabled', false);
        }
    });
}

function startAutoRefresh() {
    stopAutoRefresh(); // prevent duplicates
    autoRefreshInterval = setInterval(loadLogs, 5000); // Refresh every 5 seconds
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
        autoRefreshInterval = null;
    }
}

// Escape user-provided text before injecting into DOM
function escapeHtml(text) {
    return $('<div>').text(text).html();
}
</script>

@endsection
