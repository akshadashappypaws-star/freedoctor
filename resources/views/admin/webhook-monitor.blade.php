@extends('layouts.app')

@section('title', 'Webhook Monitor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-satellite-dish"></i> WhatsApp Webhook Monitor
                    </h3>
                    <div>
                        <button id="refreshLogs" class="btn btn-primary btn-sm">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                        <button id="clearLogs" class="btn btn-danger btn-sm ml-2">
                            <i class="fas fa-trash"></i> Clear Logs
                        </button>
                        <div class="form-check form-switch d-inline-block ml-3">
                            <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
                            <label class="form-check-label" for="autoRefresh">Auto Refresh</label>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Status Info -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-link"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Webhook URL</span>
                                    <span class="info-box-number" style="font-size: 12px;">
                                        {{ config('services.whatsapp.webhook_url') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success">
                                    <i class="fas fa-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Requests</span>
                                    <span class="info-box-number" id="totalRequests">0</span>
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

                    <!-- Test Webhook -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-flask"></i> Test Your Webhook</h5>
                        <p><strong>Local Test URL:</strong> <code>http://127.0.0.1:8000/api/webhook/whatsapp?hub.mode=subscribe&hub.verify_token={{ config('services.whatsapp.verify_token') }}&hub.challenge=test123</code></p>
                        <p><strong>Production URL:</strong> <code>{{ config('services.whatsapp.webhook_url') }}</code></p>
                        <button id="testWebhook" class="btn btn-info">
                            <i class="fas fa-play"></i> Test Local Webhook
                        </button>
                        <button id="testProductionWebhook" class="btn btn-warning ml-2">
                            <i class="fas fa-globe"></i> Test Production Webhook
                        </button>
                    </div>

                    <!-- Logs Display -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Real-time Webhook Logs</h4>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            <div id="webhookLogs">
                                <div class="text-center">
                                    <i class="fas fa-spinner fa-spin"></i> Loading logs...
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
}
.log-entry.new {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}
.log-timestamp {
    color: #6c757d;
    font-weight: bold;
}
.log-message {
    margin-top: 5px;
    word-break: break-all;
}
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


<script>
let autoRefreshInterval;
let lastLogCount = 0;

$(document).ready(function() {
    loadLogs();
    startAutoRefresh();
    
    $('#refreshLogs').click(function() {
        loadLogs();
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
});

function loadLogs() {
    $.ajax({
        url: '{{ route("admin.webhook.logs") }}',
        method: 'GET',
        success: function(response) {
            displayLogs(response.logs || []);
            $('#totalRequests').text(response.count || 0);
            
            if (response.logs && response.logs.length > 0) {
                $('#lastRequest').text(response.logs[0].timestamp);
            }
            
            // Highlight new logs
            if ((response.count || 0) > lastLogCount) {
                $('#status').text('New Request!').css('color', '#28a745');
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

function displayLogs(logs) {
    let html = '';
    
    if (logs.length === 0) {
        html = '<div class="text-center text-muted">No webhook requests yet. Send a test message!</div>';
    } else {
        logs.forEach(function(log, index) {
            let isNew = index < 3; // Mark first 3 as new
            html += `
                <div class="log-entry ${isNew ? 'new' : ''}">
                    <div class="log-timestamp">${escapeHtml(log.timestamp)}</div>
                    <div class="log-message">${escapeHtml(log.message)}</div>
                </div>
            `;
        });
    }
    
    $('#webhookLogs').html(html);
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
                + '&hub.verify_token={{ config("services.whatsapp.verify_token") }}'
                + '&hub.challenge=test123';
    } else {
        testUrl = '{{ config("services.whatsapp.webhook_url") }}'
                + '?hub.mode=subscribe'
                + '&hub.verify_token={{ config("services.whatsapp.verify_token") }}'
                + '&hub.challenge=test123';
    }
    
    const buttonText = type === 'local' ? 'Testing Local...' : 'Testing Production...';
    const buttonId = type === 'local' ? '#testWebhook' : '#testProductionWebhook';
    
    $(buttonId).html('<i class="fas fa-spinner fa-spin"></i> ' + buttonText).prop('disabled', true);
    
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
                errorMsg += '\n\n💡 Tip: Status 0 usually means:\n';
                if (type === 'local') {
                    errorMsg += '- Laravel server is not running\n- Check http://127.0.0.1:8000';
                } else {
                    errorMsg += '- Production server is down\n- Domain is not accessible\n- CORS issue';
                }
            }
            
            alert(errorMsg);
        },
        complete: function() {
            $(buttonId).html('<i class="fas fa-play"></i> Test ' + (type === 'local' ? 'Local' : 'Production') + ' Webhook').prop('disabled', false);
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
