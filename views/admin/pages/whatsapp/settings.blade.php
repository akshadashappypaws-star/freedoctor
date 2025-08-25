@extends('admin.pages.whatsapp.layouts.whatsapp')


@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">WhatsApp Bot Settings</h3>
                </div>
                <div class="card-body">
                    <form id="whatsappSettingsForm" method="POST" action="{{ route('admin.whatsapp.settings.update') }}">
                        @csrf
                        <div class="row">
                            <!-- API Configuration -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="whatsapp_api_key">WhatsApp API Key</label>
                                    <input type="password" class="form-control" id="whatsapp_api_key" name="whatsapp_api_key" 
                                           value="{{ $settings['whatsapp_api_key'] }}" required>
                                    <small class="form-text text-muted">Your WhatsApp Business API key</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="whatsapp_phone_number_id">Phone Number ID</label>
                                    <input type="text" class="form-control" id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" 
                                           value="{{ $settings['whatsapp_phone_number_id'] }}" required>
                                    <small class="form-text text-muted">Your WhatsApp Business Phone Number ID</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="whatsapp_business_account_id">Business Account ID</label>
                                    <input type="text" class="form-control" id="whatsapp_business_account_id" name="whatsapp_business_account_id" 
                                           value="{{ $settings['whatsapp_business_account_id'] }}" required>
                                    <small class="form-text text-muted">Your WhatsApp Business Account ID</small>
                                </div>
                            </div>

                            <!-- Bot Configuration -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="auto_reply_delay">Auto Reply Delay (seconds)</label>
                                    <input type="number" class="form-control" id="auto_reply_delay" name="auto_reply_delay" 
                                           value="{{ $settings['auto_reply_delay'] }}" min="0" max="300" required>
                                    <small class="form-text text-muted">Delay before sending automatic responses (0-300 seconds)</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="max_daily_messages">Maximum Daily Messages</label>
                                    <input type="number" class="form-control" id="max_daily_messages" name="max_daily_messages" 
                                           value="{{ $settings['max_daily_messages'] }}" min="1" max="10000" required>
                                    <small class="form-text text-muted">Maximum number of messages to send per day</small>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="notification_email">Notification Email</label>
                                    <input type="email" class="form-control" id="notification_email" name="notification_email" 
                                           value="{{ $settings['notification_email'] }}" required>
                                    <small class="form-text text-muted">Email address for system notifications</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    $('#whatsappSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success('Settings updated successfully');
                } else {
                    toastr.error('Failed to update settings');
                }
            },
            error: function(xhr) {
                toastr.error('Error updating settings');
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
@endpush
