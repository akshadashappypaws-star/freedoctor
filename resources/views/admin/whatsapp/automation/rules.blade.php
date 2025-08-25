@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Smart Rules Engine')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    .rules-engine {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .rules-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .rule-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .rule-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .rule-card:hover::before {
        left: 100%;
    }

    .rule-card:hover {
        border-color: #667eea;
        box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
        transform: translateY(-2px);
    }

    .rule-priority {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-high {
        background: #dc3545;
        color: white;
    }

    .priority-medium {
        background: #ffc107;
        color: #212529;
    }

    .priority-low {
        background: #28a745;
        color: white;
    }

    .rule-type-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .type-keyword { background: linear-gradient(135deg, #28a745, #20c997); }
    .type-ai { background: linear-gradient(135deg, #007bff, #6610f2); }
    .type-pattern { background: linear-gradient(135deg, #fd7e14, #e83e8c); }
    .type-fallback { background: linear-gradient(135deg, #6c757d, #495057); }

    .keyword-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 0.75rem 0;
    }

    .keyword-tag {
        background: #f8f9fa;
        color: #495057;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid #dee2e6;
    }

    .keyword-tag.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .rule-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .test-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 20px;
    }

    .test-input {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        min-height: 100px;
        resize: vertical;
        transition: all 0.3s ease;
    }

    .test-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }

    .test-result {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        border-left: 4px solid #28a745;
    }

    .ai-fallback-section {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 15px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    .ai-config {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-row {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .stat-card {
        text-align: center;
        padding: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rule-flow-chart {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .flow-step {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }

    .flow-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .flow-arrow {
        text-align: center;
        color: #6c757d;
        margin: 0.5rem 0;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #667eea;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .floating-add-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .floating-add-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }

    .rule-performance {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    .performance-good { border-left: 4px solid #28a745; }
    .performance-average { border-left: 4px solid #ffc107; }
    .performance-poor { border-left: 4px solid #dc3545; }
</style>
@endpush

@section('content')
<div class="rules-engine">
    <div class="container-fluid">
        <!-- Header -->
        <div class="rules-header animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Smart Rules Engine
                    </h2>
                    <p class="text-muted mb-0">
                        Configure keyword-based rules with AI fallback for intelligent message handling
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="importRules()">
                            <i class="fas fa-upload me-1"></i> Import
                        </button>
                        <button class="btn btn-outline-success" onclick="exportRules()">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-primary" onclick="createRule()">
                            <i class="fas fa-plus me-1"></i> New Rule
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="stats-row animate__animated animate__fadeInUp">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number text-primary">{{ $stats['total_rules'] ?? 12 }}</div>
                        <div class="stat-label">Total Rules</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number text-success">{{ $stats['active_rules'] ?? 8 }}</div>
                        <div class="stat-label">Active Rules</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number text-info">{{ $stats['keyword_matches'] ?? 342 }}</div>
                        <div class="stat-label">Keyword Matches</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-number text-warning">{{ $stats['ai_fallbacks'] ?? 89 }}</div>
                        <div class="stat-label">AI Fallbacks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rule Flow Chart -->
        <div class="rule-flow-chart animate__animated animate__fadeInLeft">
            <h4 class="mb-4">
                <i class="fas fa-sitemap text-primary me-2"></i>
                Message Processing Flow
            </h4>
            <div class="row">
                <div class="col-lg-12">
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Message Received</h6>
                            <small class="text-muted">User sends WhatsApp message to the system</small>
                        </div>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-down fa-2x"></i>
                    </div>
                    <div class="flow-step">
                        <div class="flow-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Keyword Analysis</h6>
                            <small class="text-muted">System searches for matching keywords in active rules</small>
                        </div>
                    </div>
                    <div class="flow-arrow">
                        <i class="fas fa-arrow-down fa-2x"></i>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="flow-step">
                                <div class="flow-icon" style="background: #28a745;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Keyword Match Found</h6>
                                    <small class="text-muted">Send configured template or custom message</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="flow-step">
                                <div class="flow-icon" style="background: #007bff;">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">AI Analysis</h6>
                                    <small class="text-muted">AI analyzes intent and generates response</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Rules List -->
            <div class="col-lg-8">
                <!-- AI Fallback Configuration -->
                <div class="ai-fallback-section animate__animated animate__fadeInLeft">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="fas fa-brain me-2"></i>
                            AI Fallback Configuration
                        </h4>
                        <label class="toggle-switch">
                            <input type="checkbox" id="ai-fallback-enabled" checked onchange="toggleAIFallback()">
                            <span class="slider"></span>
                        </label>
                    </div>
                    <p class="mb-3 opacity-75">
                        When no keyword rules match, the system will use AI to analyze the message and generate an appropriate response.
                    </p>
                    <div class="ai-config">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">AI Model</label>
                                <select class="form-select form-select-sm">
                                    <option value="gpt-3.5">GPT-3.5 Turbo</option>
                                    <option value="gpt-4" selected>GPT-4</option>
                                    <option value="claude">Claude</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Response Tone</label>
                                <select class="form-select form-select-sm">
                                    <option value="professional" selected>Professional</option>
                                    <option value="friendly">Friendly</option>
                                    <option value="casual">Casual</option>
                                    <option value="medical">Medical</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">System Prompt</label>
                                <textarea class="form-control form-control-sm" rows="3" placeholder="You are a helpful medical assistant for FreeDoctor platform...">You are a helpful medical assistant for FreeDoctor platform. Provide accurate, professional responses about healthcare services, doctor appointments, and medical information. Always be empathetic and suggest consulting with healthcare professionals for serious concerns.</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rules List -->
                <div class="animate__animated animate__fadeInUp">
                    <div id="rules-container">
                        <!-- Sample Rules -->
                        <div class="rule-card">
                            <div class="rule-priority priority-high">High</div>
                            <div class="d-flex align-items-start">
                                <div class="rule-type-icon type-keyword">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Doctor Search Request</h6>
                                        <label class="toggle-switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <p class="text-muted small mb-2">Responds to doctor search queries with available specialists</p>
                                    <div class="keyword-tags">
                                        <span class="keyword-tag active">doctor</span>
                                        <span class="keyword-tag active">specialist</span>
                                        <span class="keyword-tag active">appointment</span>
                                        <span class="keyword-tag active">find doctor</span>
                                    </div>
                                    <div class="rule-performance performance-good">
                                        <span><i class="fas fa-chart-line me-1"></i> 85% match rate</span>
                                        <span><i class="fas fa-clock me-1"></i> 156 triggers today</span>
                                        <span><i class="fas fa-thumbs-up me-1"></i> 92% satisfaction</span>
                                    </div>
                                    <div class="rule-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule(1)">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="testRule(1)">
                                            <i class="fas fa-play me-1"></i> Test
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewStats(1)">
                                            <i class="fas fa-chart-bar me-1"></i> Stats
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRule(1)">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rule-card">
                            <div class="rule-priority priority-medium">Medium</div>
                            <div class="d-flex align-items-start">
                                <div class="rule-type-icon type-keyword">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Greeting Response</h6>
                                        <label class="toggle-switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <p class="text-muted small mb-2">Welcomes users with greeting messages</p>
                                    <div class="keyword-tags">
                                        <span class="keyword-tag active">hello</span>
                                        <span class="keyword-tag active">hi</span>
                                        <span class="keyword-tag active">hey</span>
                                        <span class="keyword-tag active">start</span>
                                    </div>
                                    <div class="rule-performance performance-good">
                                        <span><i class="fas fa-chart-line me-1"></i> 95% match rate</span>
                                        <span><i class="fas fa-clock me-1"></i> 234 triggers today</span>
                                        <span><i class="fas fa-thumbs-up me-1"></i> 88% satisfaction</span>
                                    </div>
                                    <div class="rule-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule(2)">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="testRule(2)">
                                            <i class="fas fa-play me-1"></i> Test
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewStats(2)">
                                            <i class="fas fa-chart-bar me-1"></i> Stats
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRule(2)">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rule-card">
                            <div class="rule-priority priority-low">Low</div>
                            <div class="d-flex align-items-start">
                                <div class="rule-type-icon type-pattern">
                                    <i class="fas fa-magic"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Emergency Keywords</h6>
                                        <label class="toggle-switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <p class="text-muted small mb-2">Detects emergency situations and provides immediate assistance</p>
                                    <div class="keyword-tags">
                                        <span class="keyword-tag active">emergency</span>
                                        <span class="keyword-tag active">urgent</span>
                                        <span class="keyword-tag active">help</span>
                                        <span class="keyword-tag active">pain</span>
                                    </div>
                                    <div class="rule-performance performance-average">
                                        <span><i class="fas fa-chart-line me-1"></i> 72% match rate</span>
                                        <span><i class="fas fa-clock me-1"></i> 23 triggers today</span>
                                        <span><i class="fas fa-thumbs-up me-1"></i> 95% satisfaction</span>
                                    </div>
                                    <div class="rule-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule(3)">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="testRule(3)">
                                            <i class="fas fa-play me-1"></i> Test
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewStats(3)">
                                            <i class="fas fa-chart-bar me-1"></i> Stats
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRule(3)">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rule-card">
                            <div class="rule-priority priority-medium">Medium</div>
                            <div class="d-flex align-items-start">
                                <div class="rule-type-icon type-ai">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">AI Symptom Analysis</h6>
                                        <label class="toggle-switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <p class="text-muted small mb-2">Uses AI to analyze symptoms and suggest appropriate specialists</p>
                                    <div class="keyword-tags">
                                        <span class="keyword-tag active">symptoms</span>
                                        <span class="keyword-tag active">feeling sick</span>
                                        <span class="keyword-tag active">not well</span>
                                        <span class="keyword-tag active">health issue</span>
                                    </div>
                                    <div class="rule-performance performance-good">
                                        <span><i class="fas fa-chart-line me-1"></i> 89% match rate</span>
                                        <span><i class="fas fa-clock me-1"></i> 67 triggers today</span>
                                        <span><i class="fas fa-thumbs-up me-1"></i> 94% satisfaction</span>
                                    </div>
                                    <div class="rule-actions">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editRule(4)">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="testRule(4)">
                                            <i class="fas fa-play me-1"></i> Test
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewStats(4)">
                                            <i class="fas fa-chart-bar me-1"></i> Stats
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteRule(4)">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Section -->
            <div class="col-lg-4">
                <div class="test-section animate__animated animate__fadeInRight">
                    <h5 class="mb-3">
                        <i class="fas fa-vial text-primary me-2"></i>
                        Rule Testing
                    </h5>
                    <p class="text-muted small mb-3">
                        Test your rules with sample messages to see how the system responds
                    </p>
                    
                    <div class="form-group mb-3">
                        <label class="form-label small">Test Message</label>
                        <textarea class="test-input" id="test-message" placeholder="Type a message to test against rules...
                        
Examples:
• Hello, I need a doctor
• I have chest pain
• Find cardiologist near me
• Emergency help needed"></textarea>
                    </div>
                    
                    <button class="btn btn-primary w-100 mb-3" onclick="testMessage()">
                        <i class="fas fa-play me-2"></i>
                        Test Message
                    </button>
                    
                    <div id="test-results" style="display: none;">
                        <h6 class="mb-2">Test Results:</h6>
                        <div id="test-output" class="test-result">
                            <!-- Results will appear here -->
                        </div>
                    </div>
                    
                    <!-- Quick Test Buttons -->
                    <div class="mt-4">
                        <h6 class="mb-2">Quick Tests:</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="quickTest('hello doctor')">
                                "hello doctor"
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="quickTest('I need urgent help')">
                                "I need urgent help"
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="quickTest('find cardiologist')">
                                "find cardiologist"
                            </button>
                            <button class="btn btn-outline-primary btn-sm" onclick="quickTest('random message')">
                                "random message"
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rule Performance Overview -->
                <div class="test-section mt-3">
                    <h6 class="mb-3">
                        <i class="fas fa-tachometer-alt text-success me-2"></i>
                        Performance Overview
                    </h6>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-success h4">94%</div>
                            <small class="text-muted">Avg Match Rate</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-primary h4">1.2s</div>
                            <small class="text-muted">Avg Response Time</small>
                        </div>
                        <div class="col-6">
                            <div class="text-warning h4">7%</div>
                            <small class="text-muted">AI Fallback Rate</small>
                        </div>
                        <div class="col-6">
                            <div class="text-info h4">91%</div>
                            <small class="text-muted">User Satisfaction</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Add Button -->
    <button class="floating-add-btn" onclick="createRule()">
        <i class="fas fa-plus"></i>
    </button>
</div>
@endsection

@push('scripts')
<script>
    let rules = [];
    
    $(document).ready(function() {
        loadRules();
        setupRealTimeUpdates();
    });
    
    function createRule() {
        Swal.fire({
            title: 'Create New Rule',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Rule Name</label>
                        <input type="text" class="form-control" id="rule-name" placeholder="Enter rule name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rule Type</label>
                        <select class="form-select" id="rule-type" onchange="updateRuleForm()">
                            <option value="keyword">Keyword Matching</option>
                            <option value="pattern">Pattern Matching</option>
                            <option value="ai">AI Analysis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords (comma separated)</label>
                        <input type="text" class="form-control" id="rule-keywords" placeholder="hello, hi, start">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Response Type</label>
                        <select class="form-select" id="response-type">
                            <option value="template">Template Message</option>
                            <option value="custom">Custom Message</option>
                            <option value="ai">AI Generated</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Response Message</label>
                        <textarea class="form-control" id="response-message" rows="3" placeholder="Enter response message"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="rule-priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create Rule',
            cancelButtonText: 'Cancel',
            width: 600,
            preConfirm: () => {
                const name = document.getElementById('rule-name').value;
                const keywords = document.getElementById('rule-keywords').value;
                
                if (!name || !keywords) {
                    Swal.showValidationMessage('Please fill in all required fields');
                    return false;
                }
                
                return {
                    name: name,
                    type: document.getElementById('rule-type').value,
                    keywords: keywords,
                    responseType: document.getElementById('response-type').value,
                    responseMessage: document.getElementById('response-message').value,
                    priority: document.getElementById('rule-priority').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                saveNewRule(result.value);
            }
        });
    }
    
    function saveNewRule(ruleData) {
        Swal.fire({
            title: 'Creating Rule...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
        
        // Simulate API call
        setTimeout(() => {
            Swal.fire('Success!', 'Rule created successfully!', 'success');
            addRuleToList(ruleData);
        }, 1500);
    }
    
    function addRuleToList(ruleData) {
        const container = document.getElementById('rules-container');
        const ruleHtml = `
            <div class="rule-card animate__animated animate__fadeInUp">
                <div class="rule-priority priority-${ruleData.priority}">${ruleData.priority}</div>
                <div class="d-flex align-items-start">
                    <div class="rule-type-icon type-${ruleData.type}">
                        <i class="fas fa-${getTypeIcon(ruleData.type)}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">${ruleData.name}</h6>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <p class="text-muted small mb-2">${ruleData.responseMessage}</p>
                        <div class="keyword-tags">
                            ${ruleData.keywords.split(',').map(k => `<span class="keyword-tag active">${k.trim()}</span>`).join('')}
                        </div>
                        <div class="rule-performance performance-good">
                            <span><i class="fas fa-chart-line me-1"></i> New rule</span>
                            <span><i class="fas fa-clock me-1"></i> 0 triggers</span>
                            <span><i class="fas fa-thumbs-up me-1"></i> No data</span>
                        </div>
                        <div class="rule-actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="editRule('new')">
                                <i class="fas fa-edit me-1"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-success" onclick="testRule('new')">
                                <i class="fas fa-play me-1"></i> Test
                            </button>
                            <button class="btn btn-sm btn-outline-info" onclick="viewStats('new')">
                                <i class="fas fa-chart-bar me-1"></i> Stats
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteRule('new')">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('afterbegin', ruleHtml);
    }
    
    function getTypeIcon(type) {
        const icons = {
            keyword: 'key',
            pattern: 'magic',
            ai: 'brain'
        };
        return icons[type] || 'cog';
    }
    
    function editRule(ruleId) {
        Swal.fire({
            title: 'Edit Rule',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Rule Name</label>
                        <input type="text" class="form-control" value="Doctor Search Request" id="edit-rule-name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keywords</label>
                        <input type="text" class="form-control" value="doctor, specialist, appointment, find doctor" id="edit-keywords">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Response Message</label>
                        <textarea class="form-control" rows="3" id="edit-response">I can help you find a doctor! Please let me know:
1. What type of specialist you need
2. Your preferred location
3. Any specific requirements</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" id="edit-priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high" selected>High</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Save Changes',
            cancelButtonText: 'Cancel',
            width: 600
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Updated!', 'Rule updated successfully!', 'success');
            }
        });
    }
    
    function testRule(ruleId) {
        Swal.fire({
            title: 'Test Rule',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Test Message</label>
                        <textarea class="form-control" rows="3" id="rule-test-message" placeholder="Enter test message...">I need to find a doctor</textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Run Test',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const message = document.getElementById('rule-test-message').value;
                runRuleTest(message, ruleId);
            }
        });
    }
    
    function runRuleTest(message, ruleId) {
        Swal.fire({
            title: 'Testing Rule...',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <strong>Input:</strong> "${message}"
                    </div>
                    <div class="mb-3">
                        <strong>Processing:</strong>
                        <div class="mt-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                <span>Checking keywords...</span>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false
        });
        
        setTimeout(() => {
            Swal.fire({
                title: 'Test Results',
                html: `
                    <div class="text-start">
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Rule Matched!</strong>
                        </div>
                        <div class="mb-3">
                            <strong>Matched Keywords:</strong> doctor, find
                        </div>
                        <div class="mb-3">
                            <strong>Response:</strong>
                            <div class="bg-light p-2 rounded mt-1">
                                I can help you find a doctor! Please let me know:
                                1. What type of specialist you need
                                2. Your preferred location
                                3. Any specific requirements
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Processing Time:</strong> 0.2 seconds
                        </div>
                    </div>
                `,
                confirmButtonText: 'Close'
            });
        }, 2000);
    }
    
    function viewStats(ruleId) {
        Swal.fire({
            title: 'Rule Statistics',
            html: `
                <div class="text-start">
                    <div class="row text-center mb-4">
                        <div class="col-3">
                            <div class="h4 text-primary">156</div>
                            <small>Today's Triggers</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 text-success">85%</div>
                            <small>Match Rate</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 text-info">0.3s</div>
                            <small>Avg Response</small>
                        </div>
                        <div class="col-3">
                            <div class="h4 text-warning">92%</div>
                            <small>Satisfaction</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6>Performance Trend (Last 7 Days)</h6>
                        <canvas id="rule-chart" width="400" height="200"></canvas>
                    </div>
                    <div class="mb-3">
                        <h6>Most Common Matched Keywords</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">doctor (45%)</span>
                            <span class="badge bg-secondary">specialist (32%)</span>
                            <span class="badge bg-success">appointment (23%)</span>
                        </div>
                    </div>
                </div>
            `,
            width: 700,
            confirmButtonText: 'Close'
        });
    }
    
    function deleteRule(ruleId) {
        Swal.fire({
            title: 'Delete Rule?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deleted!', 'Rule has been deleted.', 'success');
                // Remove rule from DOM
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        });
    }
    
    function testMessage() {
        const message = document.getElementById('test-message').value;
        if (!message.trim()) {
            Swal.fire('Empty Message', 'Please enter a message to test!', 'warning');
            return;
        }
        
        const resultsDiv = document.getElementById('test-results');
        const outputDiv = document.getElementById('test-output');
        
        resultsDiv.style.display = 'block';
        outputDiv.innerHTML = `
            <div class="d-flex align-items-center mb-2">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                <span>Processing message...</span>
            </div>
        `;
        
        setTimeout(() => {
            const result = simulateRuleProcessing(message);
            outputDiv.innerHTML = result;
        }, 1500);
    }
    
    function quickTest(message) {
        document.getElementById('test-message').value = message;
        testMessage();
    }
    
    function simulateRuleProcessing(message) {
        const lowerMessage = message.toLowerCase();
        
        // Check for keyword matches
        if (lowerMessage.includes('doctor') || lowerMessage.includes('specialist')) {
            return `
                <div class="alert alert-success mb-2">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Keyword Rule Matched:</strong> Doctor Search Request
                </div>
                <div class="mb-2">
                    <strong>Matched Keywords:</strong> ${lowerMessage.includes('doctor') ? 'doctor' : 'specialist'}
                </div>
                <div class="mb-2">
                    <strong>Response:</strong>
                    <div class="bg-light p-2 rounded mt-1 small">
                        I can help you find a doctor! Please let me know:<br>
                        1. What type of specialist you need<br>
                        2. Your preferred location<br>
                        3. Any specific requirements
                    </div>
                </div>
                <div class="small text-muted">Processing time: 0.2 seconds</div>
            `;
        } else if (lowerMessage.includes('hello') || lowerMessage.includes('hi')) {
            return `
                <div class="alert alert-success mb-2">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Keyword Rule Matched:</strong> Greeting Response
                </div>
                <div class="mb-2">
                    <strong>Matched Keywords:</strong> ${lowerMessage.includes('hello') ? 'hello' : 'hi'}
                </div>
                <div class="mb-2">
                    <strong>Response:</strong>
                    <div class="bg-light p-2 rounded mt-1 small">
                        Hello! Welcome to FreeDoctor. I'm here to help you with your healthcare needs. How can I assist you today?
                    </div>
                </div>
                <div class="small text-muted">Processing time: 0.1 seconds</div>
            `;
        } else if (lowerMessage.includes('emergency') || lowerMessage.includes('urgent') || lowerMessage.includes('help')) {
            return `
                <div class="alert alert-danger mb-2">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Emergency Rule Matched:</strong> Emergency Keywords
                </div>
                <div class="mb-2">
                    <strong>Matched Keywords:</strong> ${lowerMessage.includes('emergency') ? 'emergency' : lowerMessage.includes('urgent') ? 'urgent' : 'help'}
                </div>
                <div class="mb-2">
                    <strong>Response:</strong>
                    <div class="bg-light p-2 rounded mt-1 small">
                        🚨 This seems urgent! If this is a medical emergency, please call emergency services immediately or visit the nearest emergency room. For non-emergency urgent care, I can help you find the nearest clinic.
                    </div>
                </div>
                <div class="small text-muted">Processing time: 0.1 seconds (High Priority)</div>
            `;
        } else {
            return `
                <div class="alert alert-info mb-2">
                    <i class="fas fa-brain me-2"></i>
                    <strong>AI Fallback Activated</strong>
                </div>
                <div class="mb-2">
                    <strong>Analysis:</strong> No keyword matches found, using AI analysis
                </div>
                <div class="mb-2">
                    <strong>Response:</strong>
                    <div class="bg-light p-2 rounded mt-1 small">
                        I understand you're looking for healthcare assistance. While I didn't recognize specific keywords in your message, I'm here to help! Could you please provide more details about what you need? For example:
                        <br>• Finding a doctor or specialist
                        <br>• Booking an appointment
                        <br>• General health information
                        <br>• Emergency assistance
                    </div>
                </div>
                <div class="small text-muted">Processing time: 1.2 seconds (AI Analysis)</div>
            `;
        }
    }
    
    function toggleAIFallback() {
        const checkbox = document.getElementById('ai-fallback-enabled');
        const status = checkbox.checked ? 'enabled' : 'disabled';
        
        Swal.fire({
            title: `AI Fallback ${status}`,
            text: `AI fallback has been ${status} for unmatched messages.`,
            icon: checkbox.checked ? 'success' : 'warning',
            timer: 2000,
            showConfirmButton: false
        });
    }
    
    function loadRules() {
        // Rules are already loaded in the HTML for this demo
        console.log('Rules loaded');
    }
    
    function setupRealTimeUpdates() {
        // Simulate real-time rule performance updates
        setInterval(() => {
            updateRuleStats();
        }, 5000);
    }
    
    function updateRuleStats() {
        // Simulate random stat updates
        const statElements = document.querySelectorAll('.rule-performance span');
        statElements.forEach(element => {
            if (element.textContent.includes('triggers today')) {
                const current = parseInt(element.textContent.match(/\d+/)[0]);
                if (Math.random() > 0.8) {
                    element.innerHTML = element.innerHTML.replace(/\d+/, current + 1);
                }
            }
        });
    }
    
    function importRules() {
        Swal.fire({
            title: 'Import Rules',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Import Source</label>
                        <select class="form-select" id="import-source">
                            <option value="file">Upload JSON File</option>
                            <option value="template">Use Template</option>
                            <option value="backup">Restore from Backup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">File</label>
                        <input type="file" class="form-control" accept=".json">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Import',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Importing...', 'Processing rules...', 'info');
                setTimeout(() => {
                    Swal.fire('Success!', 'Rules imported successfully!', 'success');
                }, 2000);
            }
        });
    }
    
    function exportRules() {
        Swal.fire({
            title: 'Export Rules',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Export Format</label>
                        <select class="form-select" id="export-format">
                            <option value="json">JSON</option>
                            <option value="csv">CSV</option>
                            <option value="backup">Full Backup</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="include-stats" checked>
                            <label class="form-check-label" for="include-stats">
                                Include Performance Statistics
                            </label>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Exporting...', 'Preparing download...', 'info');
                setTimeout(() => {
                    Swal.fire('Downloaded!', 'Rules exported successfully!', 'success');
                }, 1500);
            }
        });
    }
</script>
@endpush
