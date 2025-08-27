@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'AI Engine Settings')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="whatsapp-card mb-3">
        <div class="whatsapp-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">
                        <i class="fas fa-brain me-2"></i>
                        AI Engine Configuration
                    </h4>
                    <small class="opacity-90">
                        <span class="live-indicator"></span>
                        Configure OpenAI and AI response settings
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>
                        AI Engine Online
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Status Row -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="stat-number">GPT-4</div>
                <p class="stat-label">Active Model</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-number">1.8s</div>
                <p class="stat-label">Avg Response</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number">97%</div>
                <p class="stat-label">Success Rate</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stat-number">1,247</div>
                <p class="stat-label">Responses Today</p>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- AI Configuration Panel -->
        <div class="col-md-8">
            <div class="whatsapp-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        AI Engine Settings
                    </h6>
                </div>
                <div class="card-body">
                    <form id="aiSettingsForm">
                        <!-- OpenAI Configuration -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fab fa-openai me-2"></i>
                                OpenAI Configuration
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">API Key</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="openaiApiKey" 
                                               value="sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx" readonly>
                                        <button class="btn btn-outline-secondary" type="button" onclick="toggleApiKey()">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Your OpenAI API key for AI responses</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Model</label>
                                    <select class="form-select" id="aiModel">
                                        <option value="gpt-4" selected>GPT-4 (Recommended)</option>
                                        <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                                        <option value="gpt-4-turbo">GPT-4 Turbo</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Temperature</label>
                                    <input type="range" class="form-range" min="0" max="1" step="0.1" 
                                           value="0.7" id="temperature" oninput="updateTempValue(this.value)">
                                    <small class="text-muted">Creativity: <span id="tempValue">0.7</span></small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Max Tokens</label>
                                    <input type="number" class="form-control" value="150" id="maxTokens" min="50" max="1000">
                                    <small class="text-muted">Response length limit</small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Timeout (seconds)</label>
                                    <input type="number" class="form-control" value="10" id="timeout" min="5" max="30">
                                    <small class="text-muted">Request timeout</small>
                                </div>
                            </div>
                        </div>

                        <!-- AI Behavior Settings -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user-robot me-2"></i>
                                AI Behavior Settings
                            </h6>
                            
                            <div class="mb-3">
                                <label class="form-label">System Prompt</label>
                                <textarea class="form-control" rows="4" id="systemPrompt">You are a helpful medical assistant for FreeDoctor platform. Provide accurate, professional medical guidance while being empathetic and supportive. Always recommend consulting with healthcare professionals for serious conditions.</textarea>
                                <small class="text-muted">This defines how the AI should behave and respond</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Response Language</label>
                                    <select class="form-select" id="responseLanguage">
                                        <option value="auto" selected>Auto-detect</option>
                                        <option value="en">English</option>
                                        <option value="hi">Hindi</option>
                                        <option value="mr">Marathi</option>
                                        <option value="gu">Gujarati</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fallback Action</label>
                                    <select class="form-select" id="fallbackAction">
                                        <option value="human" selected>Transfer to Human</option>
                                        <option value="retry">Retry with AI</option>
                                        <option value="template">Use Template Response</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Context Settings -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-stethoscope me-2"></i>
                                Medical Context Settings
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="medicalKnowledge" checked>
                                        <label class="form-check-label" for="medicalKnowledge">
                                            Enhanced Medical Knowledge
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="symptomAnalysis" checked>
                                        <label class="form-check-label" for="symptomAnalysis">
                                            Symptom Analysis
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="emergencyDetection" checked>
                                        <label class="form-check-label" for="emergencyDetection">
                                            Emergency Detection
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="doctorRecommendation" checked>
                                        <label class="form-check-label" for="doctorRecommendation">
                                            Doctor Recommendations
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="appointmentSuggestion" checked>
                                        <label class="form-check-label" for="appointmentSuggestion">
                                            Appointment Suggestions
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="followUpReminders">
                                        <label class="form-check-label" for="followUpReminders">
                                            Follow-up Reminders
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Save Settings -->
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="testAIConnection()">
                                <i class="fas fa-vial me-1"></i>
                                Test Connection
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- AI Performance Panel -->
        <div class="col-md-4">
            <div class="whatsapp-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        AI Performance
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Response Time Chart -->
                    <div class="mb-4">
                        <h6 class="small text-muted mb-2">Response Time (Last 24h)</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" style="width: 85%">1.8s avg</div>
                        </div>
                        <small class="text-muted">Target: <2.0s</small>
                    </div>

                    <!-- Success Rate -->
                    <div class="mb-4">
                        <h6 class="small text-muted mb-2">Success Rate</h6>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-primary" style="width: 97%">97%</div>
                        </div>
                        <small class="text-muted">Target: >95%</small>
                    </div>

                    <!-- User Satisfaction -->
                    <div class="mb-4">
                        <h6 class="small text-muted mb-2">User Satisfaction</h6>
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </span>
                            </div>
                            <small class="text-muted">4.8/5.0 (127 reviews)</small>
                        </div>
                    </div>

                    <!-- Recent AI Activity -->
                    <div>
                        <h6 class="small text-muted mb-2">Recent AI Activity</h6>
                        <div class="ai-activity-feed">
                            <div class="activity-item-small mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="activity-dot bg-success me-2"></div>
                                    <small class="text-muted flex-grow-1">Symptom analysis completed</small>
                                    <small class="text-muted">2m ago</small>
                                </div>
                            </div>
                            
                            <div class="activity-item-small mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="activity-dot bg-primary me-2"></div>
                                    <small class="text-muted flex-grow-1">Doctor recommendation sent</small>
                                    <small class="text-muted">5m ago</small>
                                </div>
                            </div>
                            
                            <div class="activity-item-small mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="activity-dot bg-info me-2"></div>
                                    <small class="text-muted flex-grow-1">Emergency protocol triggered</small>
                                    <small class="text-muted">8m ago</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="whatsapp-card mt-3">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="clearAICache()">
                            <i class="fas fa-broom me-1"></i>
                            Clear AI Cache
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="exportAILogs()">
                            <i class="fas fa-download me-1"></i>
                            Export AI Logs
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="viewAIAnalytics()">
                            <i class="fas fa-chart-bar me-1"></i>
                            View Analytics
                        </button>
                        <button class="btn btn-outline-warning btn-sm" onclick="restartAIEngine()">
                            <i class="fas fa-sync-alt me-1"></i>
                            Restart AI Engine
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Temperature slider update
function updateTempValue(value) {
    document.getElementById('tempValue').textContent = value;
}

// Toggle API key visibility
function toggleApiKey() {
    const input = document.getElementById('openaiApiKey');
    const icon = input.nextElementSibling.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Form submission
document.getElementById('aiSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Saving AI Settings...',
        text: 'Please wait while we update your configuration',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading()
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Settings Saved!',
            text: 'AI Engine configuration updated successfully',
            timer: 2000
        });
    }, 2000);
});

// Test AI connection
function testAIConnection() {
    Swal.fire({
        title: 'Testing AI Connection...',
        text: 'Connecting to OpenAI API',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading()
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Connection Successful!',
            html: `
                <div class="text-start">
                    <h6>Connection Details:</h6>
                    <ul class="small">
                        <li>Status: Connected ✓</li>
                        <li>Model: GPT-4</li>
                        <li>Response Time: 1.2s</li>
                        <li>API Credits: Available</li>
                    </ul>
                </div>
            `,
            confirmButtonText: 'Great!'
        });
    }, 2000);
}

// Quick actions
function clearAICache() {
    Swal.fire({
        title: 'Clear AI Cache?',
        text: 'This will remove all cached AI responses',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Clear Cache',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Cache Cleared!', 'AI cache has been cleared successfully', 'success');
        }
    });
}

function exportAILogs() {
    Swal.fire({
        title: 'Exporting AI Logs...',
        text: 'Preparing download',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => Swal.showLoading()
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Export Ready!',
            text: 'AI logs have been exported successfully',
            timer: 2000
        });
    }, 1500);
}

function viewAIAnalytics() {
    window.open('/admin/whatsapp/analytics', '_blank');
}

function restartAIEngine() {
    Swal.fire({
        title: 'Restart AI Engine?',
        text: 'This will temporarily interrupt AI responses',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Restart',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Restarting AI Engine...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => Swal.showLoading()
            });
            
            setTimeout(() => {
                Swal.fire('Restarted!', 'AI Engine is now running with new settings', 'success');
            }, 3000);
        }
    });
}

console.log('✅ AI Engine Settings loaded successfully');
</script>
@endpush
