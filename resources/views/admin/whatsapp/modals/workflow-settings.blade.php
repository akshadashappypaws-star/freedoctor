<!-- Workflow Settings Modal -->
<div class="modal fade" id="workflowSettingsModal" tabindex="-1" aria-labelledby="workflowSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workflowSettingsModalLabel">
                    <i class="fas fa-project-diagram me-2"></i>Workflow Settings
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="workflowSettingsForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowName" class="form-label">Workflow Name</label>
                                <input type="text" class="form-control" id="workflowName" placeholder="Enter workflow name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowStatus" class="form-label">Status</label>
                                <select class="form-select" id="workflowStatus">
                                    <option value="draft">Draft</option>
                                    <option value="active">Active</option>
                                    <option value="paused">Paused</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="workflowDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="workflowDescription" rows="3" placeholder="Enter workflow description"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowTrigger" class="form-label">Trigger Type</label>
                                <select class="form-select" id="workflowTrigger" onchange="showTriggerConfig()">
                                    <option value="">Select Trigger</option>
                                    <option value="webhook">Webhook</option>
                                    <option value="schedule">Schedule</option>
                                    <option value="keyword">Keyword</option>
                                    <option value="button_click">Button Click</option>
                                    <option value="user_event">User Event</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="workflowPriority" class="form-label">Priority</label>
                                <select class="form-select" id="workflowPriority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Trigger Configuration Sections -->
                    <div id="webhookConfig" class="trigger-config" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-link me-2"></i>Webhook Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="webhookUrl" class="form-label">Webhook URL</label>
                                    <input type="url" class="form-control" id="webhookUrl" placeholder="https://your-domain.com/webhook">
                                    <div class="form-text">This URL will be called when the workflow is triggered</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="webhookMethod" class="form-label">HTTP Method</label>
                                            <select class="form-select" id="webhookMethod">
                                                <option value="POST">POST</option>
                                                <option value="GET">GET</option>
                                                <option value="PUT">PUT</option>
                                                <option value="PATCH">PATCH</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="webhookTimeout" class="form-label">Timeout (seconds)</label>
                                            <input type="number" class="form-control" id="webhookTimeout" value="30" min="1" max="300">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="webhookHeaders" class="form-label">Custom Headers (JSON)</label>
                                    <textarea class="form-control" id="webhookHeaders" rows="3" placeholder='{"Authorization": "Bearer token", "Content-Type": "application/json"}'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="scheduleConfig" class="trigger-config" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Schedule Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="scheduleType" class="form-label">Schedule Type</label>
                                            <select class="form-select" id="scheduleType" onchange="showScheduleOptions()">
                                                <option value="once">Once</option>
                                                <option value="recurring">Recurring</option>
                                                <option value="cron">Custom (Cron)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="scheduleTimezone" class="form-label">Timezone</label>
                                            <select class="form-select" id="scheduleTimezone">
                                                <option value="UTC">UTC</option>
                                                <option value="America/New_York">Eastern Time</option>
                                                <option value="America/Chicago">Central Time</option>
                                                <option value="America/Denver">Mountain Time</option>
                                                <option value="America/Los_Angeles">Pacific Time</option>
                                                <option value="Asia/Kolkata">India Standard Time</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="onceSchedule" class="schedule-option">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="scheduleDate" class="form-label">Date</label>
                                                <input type="date" class="form-control" id="scheduleDate">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="scheduleTime" class="form-label">Time</label>
                                                <input type="time" class="form-control" id="scheduleTime">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="recurringSchedule" class="schedule-option" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="recurringFrequency" class="form-label">Frequency</label>
                                                <select class="form-select" id="recurringFrequency">
                                                    <option value="daily">Daily</option>
                                                    <option value="weekly">Weekly</option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="yearly">Yearly</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="recurringInterval" class="form-label">Every</label>
                                                <input type="number" class="form-control" id="recurringInterval" value="1" min="1">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="recurringTime" class="form-label">Time</label>
                                                <input type="time" class="form-control" id="recurringTime">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="cronSchedule" class="schedule-option" style="display: none;">
                                    <div class="mb-3">
                                        <label for="cronExpression" class="form-label">Cron Expression</label>
                                        <input type="text" class="form-control" id="cronExpression" placeholder="0 9 * * 1-5">
                                        <div class="form-text">Format: minute hour day month day-of-week</div>
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>Examples:</strong><br>
                                        <code>0 9 * * 1-5</code> - Every weekday at 9:00 AM<br>
                                        <code>0 12 1 * *</code> - First day of every month at 12:00 PM<br>
                                        <code>*/15 * * * *</code> - Every 15 minutes
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="keywordConfig" class="trigger-config" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-keyboard me-2"></i>Keyword Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="triggerKeywords" class="form-label">Trigger Keywords</label>
                                    <textarea class="form-control" id="triggerKeywords" rows="3" placeholder="Enter keywords, one per line or comma-separated"></textarea>
                                    <div class="form-text">Workflow will trigger when user sends any of these keywords</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="caseSensitive">
                                            <label class="form-check-label" for="caseSensitive">
                                                Case Sensitive
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="exactMatch">
                                            <label class="form-check-label" for="exactMatch">
                                                Exact Match Only
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="buttonClickConfig" class="trigger-config" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-mouse-pointer me-2"></i>Button Click Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="buttonPayload" class="form-label">Button Payload</label>
                                    <input type="text" class="form-control" id="buttonPayload" placeholder="button_payload_value">
                                    <div class="form-text">Workflow triggers when user clicks button with this payload</div>
                                </div>
                                <div class="mb-3">
                                    <label for="buttonTemplate" class="form-label">Source Template (Optional)</label>
                                    <select class="form-select" id="buttonTemplate">
                                        <option value="">Any Template</option>
                                        <!-- Templates will be loaded here -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="userEventConfig" class="trigger-config" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>User Event Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="eventType" class="form-label">Event Type</label>
                                            <select class="form-select" id="eventType">
                                                <option value="user_registered">User Registered</option>
                                                <option value="user_login">User Login</option>
                                                <option value="order_placed">Order Placed</option>
                                                <option value="payment_received">Payment Received</option>
                                                <option value="appointment_booked">Appointment Booked</option>
                                                <option value="custom">Custom Event</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="eventDelay" class="form-label">Delay (minutes)</label>
                                            <input type="number" class="form-control" id="eventDelay" value="0" min="0">
                                            <div class="form-text">Delay before triggering workflow</div>
                                        </div>
                                    </div>
                                </div>
                                <div id="customEventConfig" style="display: none;">
                                    <div class="mb-3">
                                        <label for="customEventName" class="form-label">Custom Event Name</label>
                                        <input type="text" class="form-control" id="customEventName" placeholder="my_custom_event">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Workflow Execution Settings -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-play me-2"></i>Execution Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="maxExecutions" class="form-label">Max Executions</label>
                                        <input type="number" class="form-control" id="maxExecutions" placeholder="Unlimited" min="1">
                                        <div class="form-text">Leave empty for unlimited</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="executionTimeout" class="form-label">Timeout (minutes)</label>
                                        <input type="number" class="form-control" id="executionTimeout" value="30" min="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="retryAttempts" class="form-label">Retry Attempts</label>
                                        <input type="number" class="form-control" id="retryAttempts" value="3" min="0" max="10">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="enableLogging" checked>
                                        <label class="form-check-label" for="enableLogging">
                                            Enable Execution Logging
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="enableNotifications">
                                        <label class="form-check-label" for="enableNotifications">
                                            Notify on Failures
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Workflow Conditions -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Execution Conditions</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="executionConditions" class="form-label">Conditions (JavaScript)</label>
                                <textarea class="form-control" id="executionConditions" rows="4" placeholder="// Return true to execute workflow, false to skip
// Available variables: user, message, context
// Example: return user.subscription === 'premium';"></textarea>
                                <div class="form-text">Optional JavaScript code to determine if workflow should execute</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info" onclick="validateWorkflow()">
                    <i class="fas fa-check me-2"></i>Validate
                </button>
                <button type="button" class="btn btn-primary" onclick="saveWorkflowSettings()">
                    <i class="fas fa-save me-2"></i>Save Workflow
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupWorkflowSettings();
});

function setupWorkflowSettings() {
    // Setup event type change handler
    $('#eventType').on('change', function() {
        if ($(this).val() === 'custom') {
            $('#customEventConfig').show();
        } else {
            $('#customEventConfig').hide();
        }
    });
}

function showTriggerConfig() {
    const triggerType = $('#workflowTrigger').val();
    
    // Hide all trigger configs
    $('.trigger-config').hide();
    
    // Show selected trigger config
    if (triggerType) {
        $(`#${triggerType}Config`).show();
    }
}

function showScheduleOptions() {
    const scheduleType = $('#scheduleType').val();
    
    // Hide all schedule options
    $('.schedule-option').hide();
    
    // Show selected schedule option
    switch(scheduleType) {
        case 'once':
            $('#onceSchedule').show();
            break;
        case 'recurring':
            $('#recurringSchedule').show();
            break;
        case 'cron':
            $('#cronSchedule').show();
            break;
    }
}

function validateWorkflow() {
    const workflowData = getWorkflowData();
    
    // Basic validation
    if (!workflowData.name) {
        showAlert('warning', 'Validation Error', 'Workflow name is required.');
        return;
    }
    
    if (!workflowData.trigger.type) {
        showAlert('warning', 'Validation Error', 'Please select a trigger type.');
        return;
    }
    
    // Trigger-specific validation
    const isValid = validateTriggerConfig(workflowData.trigger);
    
    if (isValid) {
        showAlert('success', 'Validation Passed', 'Workflow configuration is valid.');
    }
}

function validateTriggerConfig(trigger) {
    switch(trigger.type) {
        case 'webhook':
            if (!trigger.config.url) {
                showAlert('warning', 'Validation Error', 'Webhook URL is required.');
                return false;
            }
            break;
        case 'schedule':
            if (trigger.config.type === 'once') {
                if (!trigger.config.date || !trigger.config.time) {
                    showAlert('warning', 'Validation Error', 'Schedule date and time are required.');
                    return false;
                }
            } else if (trigger.config.type === 'cron') {
                if (!trigger.config.expression) {
                    showAlert('warning', 'Validation Error', 'Cron expression is required.');
                    return false;
                }
            }
            break;
        case 'keyword':
            if (!trigger.config.keywords) {
                showAlert('warning', 'Validation Error', 'At least one keyword is required.');
                return false;
            }
            break;
        case 'button_click':
            if (!trigger.config.payload) {
                showAlert('warning', 'Validation Error', 'Button payload is required.');
                return false;
            }
            break;
        case 'user_event':
            if (!trigger.config.event_type) {
                showAlert('warning', 'Validation Error', 'Event type is required.');
                return false;
            }
            break;
    }
    
    return true;
}

function saveWorkflowSettings() {
    const workflowData = getWorkflowData();
    
    // Validate workflow
    if (!validateTriggerConfig(workflowData.trigger)) {
        return;
    }
    
    $.ajax({
        url: '/api/automation/workflows/settings',
        method: 'POST',
        data: JSON.stringify(workflowData),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                $('#workflowSettingsModal').modal('hide');
                resetWorkflowSettings();
                showAlert('success', 'Workflow Saved', 'Workflow settings have been saved successfully.');
            }
        },
        error: function() {
            showAlert('error', 'Save Failed', 'Failed to save workflow settings.');
        }
    });
}

function getWorkflowData() {
    const triggerType = $('#workflowTrigger').val();
    let triggerConfig = {};
    
    // Get trigger-specific configuration
    switch(triggerType) {
        case 'webhook':
            triggerConfig = {
                url: $('#webhookUrl').val(),
                method: $('#webhookMethod').val(),
                timeout: parseInt($('#webhookTimeout').val()),
                headers: $('#webhookHeaders').val()
            };
            break;
        case 'schedule':
            const scheduleType = $('#scheduleType').val();
            triggerConfig = {
                type: scheduleType,
                timezone: $('#scheduleTimezone').val()
            };
            
            if (scheduleType === 'once') {
                triggerConfig.date = $('#scheduleDate').val();
                triggerConfig.time = $('#scheduleTime').val();
            } else if (scheduleType === 'recurring') {
                triggerConfig.frequency = $('#recurringFrequency').val();
                triggerConfig.interval = parseInt($('#recurringInterval').val());
                triggerConfig.time = $('#recurringTime').val();
            } else if (scheduleType === 'cron') {
                triggerConfig.expression = $('#cronExpression').val();
            }
            break;
        case 'keyword':
            triggerConfig = {
                keywords: $('#triggerKeywords').val(),
                case_sensitive: $('#caseSensitive').prop('checked'),
                exact_match: $('#exactMatch').prop('checked')
            };
            break;
        case 'button_click':
            triggerConfig = {
                payload: $('#buttonPayload').val(),
                template: $('#buttonTemplate').val()
            };
            break;
        case 'user_event':
            triggerConfig = {
                event_type: $('#eventType').val(),
                delay: parseInt($('#eventDelay').val()),
                custom_event: $('#customEventName').val()
            };
            break;
    }
    
    return {
        name: $('#workflowName').val(),
        description: $('#workflowDescription').val(),
        status: $('#workflowStatus').val(),
        priority: $('#workflowPriority').val(),
        trigger: {
            type: triggerType,
            config: triggerConfig
        },
        execution: {
            max_executions: $('#maxExecutions').val() ? parseInt($('#maxExecutions').val()) : null,
            timeout: parseInt($('#executionTimeout').val()),
            retry_attempts: parseInt($('#retryAttempts').val()),
            enable_logging: $('#enableLogging').prop('checked'),
            enable_notifications: $('#enableNotifications').prop('checked'),
            conditions: $('#executionConditions').val()
        }
    };
}

function resetWorkflowSettings() {
    $('#workflowSettingsForm')[0].reset();
    $('.trigger-config').hide();
    $('.schedule-option').hide();
    $('#customEventConfig').hide();
}

// Export functions
window.showTriggerConfig = showTriggerConfig;
window.showScheduleOptions = showScheduleOptions;
window.validateWorkflow = validateWorkflow;
window.saveWorkflowSettings = saveWorkflowSettings;
window.resetWorkflowSettings = resetWorkflowSettings;
</script>
