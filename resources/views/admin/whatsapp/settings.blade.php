@extends('admin.master')

@section('title', 'WhatsApp Bot Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">WhatsApp Bot Settings</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">API Configuration</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.whatsapp.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="whatsapp_token" class="form-label">WhatsApp Access Token</label>
                            <input type="password" class="form-control" id="whatsapp_token" name="whatsapp_token" 
                                   value="{{ old('whatsapp_token', $settings['whatsapp_token'] ?? '') }}"
                                   placeholder="Enter your WhatsApp Business API token">
                        </div>
                        
                        <div class="mb-3">
                            <label for="whatsapp_verify_token" class="form-label">Verify Token</label>
                            <input type="text" class="form-control" id="whatsapp_verify_token" name="whatsapp_verify_token" 
                                   value="{{ old('whatsapp_verify_token', $settings['whatsapp_verify_token'] ?? '') }}"
                                   placeholder="Webhook verification token">
                        </div>
                        
                        <div class="mb-3">
                            <label for="whatsapp_phone_number_id" class="form-label">Phone Number ID</label>
                            <input type="text" class="form-control" id="whatsapp_phone_number_id" name="whatsapp_phone_number_id" 
                                   value="{{ old('whatsapp_phone_number_id', $settings['whatsapp_phone_number_id'] ?? '') }}"
                                   placeholder="Your WhatsApp phone number ID">
                        </div>
                        
                        <div class="mb-3">
                            <label for="openai_api_key" class="form-label">OpenAI API Key</label>
                            <input type="password" class="form-control" id="openai_api_key" name="openai_api_key" 
                                   value="{{ old('openai_api_key', $settings['openai_api_key'] ?? '') }}"
                                   placeholder="Enter your OpenAI API key for AI responses">
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bot_enabled" name="bot_enabled" value="1"
                                       {{ old('bot_enabled', $settings['bot_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bot_enabled">
                                    Enable WhatsApp Bot
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="auto_reply_enabled" name="auto_reply_enabled" value="1"
                                       {{ old('auto_reply_enabled', $settings['auto_reply_enabled'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="auto_reply_enabled">
                                    Enable Auto-Reply
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Connection Status</h4>
                </div>
                <div class="card-body">
                    <div class="status-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>WhatsApp API</span>
                            <span class="badge badge-{{ $status['whatsapp'] ?? false ? 'success' : 'danger' }}">
                                {{ $status['whatsapp'] ?? false ? 'Connected' : 'Disconnected' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="status-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>OpenAI API</span>
                            <span class="badge badge-{{ $status['openai'] ?? false ? 'success' : 'danger' }}">
                                {{ $status['openai'] ?? false ? 'Connected' : 'Disconnected' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="status-item mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Webhook</span>
                            <span class="badge badge-{{ $status['webhook'] ?? false ? 'success' : 'warning' }}">
                                {{ $status['webhook'] ?? false ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <button class="btn btn-outline-primary btn-block" onclick="testConnections()">
                        <i class="fas fa-plug"></i> Test Connections
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Setup</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Follow these steps to set up your WhatsApp bot:</p>
                    <ol class="list-unstyled">
                        <li><i class="fas fa-check text-success"></i> Create Facebook App</li>
                        <li><i class="fas fa-check text-success"></i> Add WhatsApp Business API</li>
                        <li><i class="fas fa-times text-danger"></i> Configure Webhook</li>
                        <li><i class="fas fa-times text-danger"></i> Test Message Flow</li>
                    </ol>
                    
                    <a href="#" class="btn btn-outline-info btn-block">
                        <i class="fas fa-book"></i> Setup Guide
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function testConnections() {
    // Add AJAX call to test API connections
    alert('Connection test feature coming soon!');
}
</script>
@endsection
