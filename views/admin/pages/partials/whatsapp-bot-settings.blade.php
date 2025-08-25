<!-- WhatsApp Settings -->
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">WhatsApp Bot Settings</h5>
    </div>
    <div class="card-body">
        <form id="whatsappSettingsForm" action="{{ route('admin.whatsapp.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- API Configuration -->
            <div class="mb-4">
                <h6 class="mb-3">API Configuration</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="apiKey">API Key</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="apiKey" 
                                   name="api_key" 
                                   value="{{ $settings->api_key ?? '' }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="apiSecret">API Secret</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="apiSecret" 
                                   name="api_secret"
                                   value="{{ $settings->api_secret ?? '' }}" 
                                   required>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="webhook">Webhook URL</label>
                    <input type="url" 
                           class="form-control" 
                           id="webhook" 
                           value="{{ url('/api/whatsapp/webhook') }}" 
                           readonly>
                    <small class="text-muted">Configure this URL in your WhatsApp Business API settings</small>
                </div>
            </div>

            <!-- Message Settings -->
            <div class="mb-4">
                <h6 class="mb-3">Message Settings</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="defaultLanguage">Default Language</label>
                            <select class="form-control" id="defaultLanguage" name="default_language">
                                <option value="en" {{ ($settings->default_language ?? '') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($settings->default_language ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                                <option value="hi" {{ ($settings->default_language ?? '') == 'hi' ? 'selected' : '' }}>Hindi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="messageDelay">Message Delay (seconds)</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="messageDelay" 
                                   name="message_delay"
                                   value="{{ $settings->message_delay ?? 1 }}"
                                   min="1"
                                   max="10">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="mb-4">
                <h6 class="mb-3">Notification Settings</h6>
                <div class="form-group mb-3">
                    <label for="notificationEmail">Notification Email</label>
                    <input type="email" 
                           class="form-control" 
                           id="notificationEmail" 
                           name="notification_email"
                           value="{{ $settings->notification_email ?? '' }}">
                    <small class="text-muted">Receive notifications about failures and important events</small>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="deliveryReports" 
                           name="delivery_reports"
                           {{ ($settings->delivery_reports ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="deliveryReports">
                        Enable delivery reports
                    </label>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="failureAlerts" 
                           name="failure_alerts"
                           {{ ($settings->failure_alerts ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="failureAlerts">
                        Send failure alerts
                    </label>
                </div>
            </div>

            <!-- Rate Limiting -->
            <div class="mb-4">
                <h6 class="mb-3">Rate Limiting</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="maxMessages">Max Messages Per Day</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="maxMessages" 
                                   name="max_messages"
                                   value="{{ $settings->max_messages ?? 1000 }}"
                                   min="1">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="retryAttempts">Retry Attempts</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="retryAttempts" 
                                   name="retry_attempts"
                                   value="{{ $settings->retry_attempts ?? 3 }}"
                                   min="0"
                                   max="5">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </form>
    </div>
</div>

<!-- Connection Test Card -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Connection Test</h5>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <button id="testConnection" class="btn btn-info me-3">
                <i class="fas fa-sync"></i> Test Connection
            </button>
            <span id="connectionStatus">Click to test connection</span>
        </div>
        <div id="testResults" class="alert" style="display: none;"></div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle settings form submission
    $('#whatsappSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                Swal.fire('Success!', 'Settings have been updated.', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Failed to update settings.', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false);
            }
        });
    });

    // Handle connection test
    $('#testConnection').click(function() {
        const btn = $(this);
        const status = $('#connectionStatus');
        const results = $('#testResults');
        
        btn.prop('disabled', true);
        status.text('Testing connection...');
        results.hide();
        
        $.get('/admin/whatsapp/test-connection')
            .done(function(response) {
                status.text('Connected');
                results.removeClass('alert-danger').addClass('alert-success')
                    .html('<strong>Success!</strong> WhatsApp API connection is working properly.')
                    .slideDown();
            })
            .fail(function(xhr) {
                status.text('Connection failed');
                results.removeClass('alert-success').addClass('alert-danger')
                    .html('<strong>Error!</strong> ' + (xhr.responseJSON?.message || 'Failed to connect to WhatsApp API.'))
                    .slideDown();
            })
            .always(function() {
                btn.prop('disabled', false);
            });
    });
});
</script>
@endpush
