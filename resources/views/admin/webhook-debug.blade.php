@extends('layouts.app')

@section('title', 'WhatsApp Webhook Debugger')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        🔍 WhatsApp Webhook Debugger
                    </h3>
                    <div>
                        <a href="{{ route('admin.webhook.monitor') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Monitor
                        </a>
                        <button id="startLiveMonitoring" class="btn btn-success btn-sm">
                            <i class="fas fa-play"></i> Start Live Monitoring
                        </button>
                        <button id="stopLiveMonitoring" class="btn btn-danger btn-sm" style="display: none;">
                            <i class="fas fa-stop"></i> Stop Live Monitoring
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Quick Tests Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>🧪 Quick Webhook Tests</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Local Test URL:</strong>
                                        <div class="input-group mt-2">
                                            <input type="text" id="localTestUrl" class="form-control" 
                                                   value="{{ $envInfo['local_test_url'] }}" readonly>
                                            <button class="btn btn-outline-primary" onclick="testUrl('local')">
                                                <i class="fas fa-play"></i> Test
                                            </button>
                                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('localTestUrl')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <strong>Production Webhook URL:</strong>
                                        <div class="input-group mt-2">
                                            <input type="text" id="prodWebhookUrl" class="form-control" 
                                                   value="{{ $envInfo['webhook_url'] }}" readonly>
                                            <button class="btn btn-outline-warning" onclick="testUrl('production')">
                                                <i class="fas fa-globe"></i> Test
                                            </button>
                                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('prodWebhookUrl')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="alert alert-info">
                                        <h6>🎯 Expected Result:</h6>
                                        <p class="mb-0">Both URLs should return: <code>TEST123</code></p>
                                        <small>If they don't, your webhook verification is broken.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>📋 Configuration Check</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>App URL:</strong></td>
                                            <td><code>{{ $envInfo['app_url'] }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Webhook URL:</strong></td>
                                            <td><code>{{ $envInfo['webhook_url'] }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Verify Token:</strong></td>
                                            <td><code>{{ Str::mask($envInfo['verify_token'], '*', 3, -3) }}</code></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Local Webhook:</strong></td>
                                            <td><code>{{ $envInfo['local_webhook'] }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Logs Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5>📊 Live Webhook Logs</h5>
                                    <div>
                                        <span id="logStatus" class="badge bg-secondary">Ready</span>
                                        <span id="logCount" class="badge bg-info">0 logs</span>
                                        <button id="clearLiveLogs" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                    <div id="liveLogs">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-info-circle"></i> Click "Start Live Monitoring" to see real-time webhook logs
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Debug Instructions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>🔧 Debug Instructions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>✅ Step 1: Test Verification</h6>
                                            <p>Click the "Test" buttons above. Both should return <code>TEST123</code>.</p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>📱 Step 2: Send Test Message</h6>
                                            <p>Send a WhatsApp message to your business number. Check live logs for activity.</p>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>🔍 Step 3: Check Meta Dashboard</h6>
                                            <p>Go to Meta Developer Console → Webhooks → Activity to see delivery status.</p>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>🚨 Common Issues & Solutions:</h6>
                                            <ul class="list-unstyled">
                                                <li><strong>❌ Verification fails:</strong> Check your verify token in .env file</li>
                                                <li><strong>❌ No POST logs:</strong> Your webhook might not be handling POST requests</li>
                                                <li><strong>❌ 500 errors:</strong> Check PHP error logs and database connection</li>
                                                <li><strong>❌ "Active" but no messages:</strong> Meta might be getting non-200 responses</li>
                                            </ul>
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
.live-log-entry {
    border-left: 4px solid #28a745;
    padding: 10px;
    margin-bottom: 5px;
    background: #f8f9fa;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.live-log-entry.error {
    border-left-color: #dc3545;
    background: #f8d7da;
}

.live-log-entry.warning {
    border-left-color: #ffc107;
    background: #fff3cd;
}

.live-log-entry.info {
    border-left-color: #17a2b8;
    background: #d1ecf1;
}

.live-log-timestamp {
    font-weight: bold;
    color: #6c757d;
}

.live-log-emoji {
    font-size: 16px;
    margin-right: 5px;
}

.live-log-message {
    margin-top: 5px;
    word-break: break-all;
}

.monitoring-active {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { background-color: #28a745; }
    50% { background-color: #20c997; }
    100% { background-color: #28a745; }
}
</style>

<script>
let liveMonitoringInterval;
let logCount = 0;

$(document).ready(function() {
    $('#startLiveMonitoring').click(startLiveMonitoring);
    $('#stopLiveMonitoring').click(stopLiveMonitoring);
    $('#clearLiveLogs').click(clearLiveLogs);
});

function startLiveMonitoring() {
    $('#startLiveMonitoring').hide();
    $('#stopLiveMonitoring').show();
    $('#logStatus').removeClass('bg-secondary').addClass('bg-success monitoring-active').text('Monitoring...');
    
    loadLiveLogs();
    liveMonitoringInterval = setInterval(loadLiveLogs, 2000); // Check every 2 seconds
}

function stopLiveMonitoring() {
    $('#startLiveMonitoring').show();
    $('#stopLiveMonitoring').hide();
    $('#logStatus').removeClass('bg-success monitoring-active').addClass('bg-secondary').text('Stopped');
    
    if (liveMonitoringInterval) {
        clearInterval(liveMonitoringInterval);
        liveMonitoringInterval = null;
    }
}

function loadLiveLogs() {
    $.ajax({
        url: '{{ route("admin.webhook.live-logs") }}',
        method: 'GET',
        success: function(response) {
            displayLiveLogs(response.logs || []);
            $('#logCount').text(response.count + ' logs');
        },
        error: function(xhr) {
            console.error('Error loading live logs:', xhr);
            $('#liveLogs').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error loading logs: ${xhr.statusText}
                </div>
            `);
        }
    });
}

function displayLiveLogs(logs) {
    let html = '';
    
    if (logs.length === 0) {
        html = `
            <div class="text-center text-muted">
                <i class="fas fa-clock"></i> No webhook logs found yet. Send a WhatsApp message to generate logs.
            </div>
        `;
    } else {
        logs.forEach(function(log) {
            const emoji = log.emoji || '📝';
            const severityClass = log.severity || 'info';
            
            html += `
                <div class="live-log-entry ${severityClass}">
                    <div class="live-log-timestamp">
                        <span class="live-log-emoji">${emoji}</span>
                        ${log.timestamp}
                        <span class="badge bg-${severityClass} ms-2">${log.type.toUpperCase()}</span>
                    </div>
                    <div class="live-log-message">${escapeHtml(log.message)}</div>
                </div>
            `;
        });
    }
    
    $('#liveLogs').html(html);
    
    // Auto-scroll to bottom
    const logsContainer = $('#liveLogs').parent();
    logsContainer.scrollTop(logsContainer[0].scrollHeight);
}

function clearLiveLogs() {
    $('#liveLogs').html(`
        <div class="text-center text-muted">
            <i class="fas fa-info-circle"></i> Logs cleared. New logs will appear here.
        </div>
    `);
    $('#logCount').text('0 logs');
}

function testUrl(type) {
    const url = type === 'local' ? $('#localTestUrl').val() : $('#prodWebhookUrl').val();
    const buttonText = type === 'local' ? 'Testing...' : 'Testing...';
    
    const button = type === 'local' ? 
        $('#localTestUrl').siblings('button').first() : 
        $('#prodWebhookUrl').siblings('button').first();
    
    button.html('<i class="fas fa-spinner fa-spin"></i> ' + buttonText).prop('disabled', true);
    
    $.ajax({
        url: url,
        method: 'GET',
        timeout: 10000,
        success: function(response) {
            if (response === 'TEST123') {
                showAlert('✅ Success!', `${type} webhook test passed! Response: ${response}`, 'success');
            } else {
                showAlert('⚠️ Unexpected Response', `Expected 'TEST123' but got: ${response}`, 'warning');
            }
        },
        error: function(xhr) {
            showAlert('❌ Test Failed', `${type} webhook test failed! Status: ${xhr.status} ${xhr.statusText}`, 'danger');
        },
        complete: function() {
            const icon = type === 'local' ? '<i class="fas fa-play"></i>' : '<i class="fas fa-globe"></i>';
            button.html(icon + ' Test').prop('disabled', false);
        }
    });
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    showAlert('📋 Copied!', 'URL copied to clipboard', 'info');
}

function showAlert(title, message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show mt-2" role="alert">
            <strong>${title}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Remove existing alerts and add new one
    $('.alert').remove();
    $('.card-body').first().prepend(alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

function escapeHtml(text) {
    return $('<div>').text(text).html();
}
</script>

@endsection
