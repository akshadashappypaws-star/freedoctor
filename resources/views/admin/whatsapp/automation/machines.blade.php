@extends('admin.master')

@section('title', 'Machine Configuration')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    .machines-config {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .config-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .machine-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 3px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .machine-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        transition: left 0.5s;
    }

    .machine-card:hover::before {
        left: 100%;
    }

    .machine-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .machine-online {
        border-color: #28a745;
    }

    .machine-maintenance {
        border-color: #ffc107;
    }

    .machine-offline {
        border-color: #dc3545;
    }

    .machine-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .machine-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin-right: 1.5rem;
        position: relative;
    }

    .machine-ai { background: linear-gradient(135deg, #007bff, #6610f2); }
    .machine-template { background: linear-gradient(135deg, #28a745, #20c997); }
    .machine-datatable { background: linear-gradient(135deg, #17a2b8, #007bff); }
    .machine-function { background: linear-gradient(135deg, #fd7e14, #e83e8c); }
    .machine-visualization { background: linear-gradient(135deg, #6f42c1, #e83e8c); }

    .status-indicator {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 3px solid white;
        animation: pulse 2s infinite;
    }

    .status-online { background: #28a745; }
    .status-maintenance { background: #ffc107; }
    .status-offline { background: #dc3545; }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
    }

    .health-bar {
        background: #e9ecef;
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .health-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .health-excellent { background: linear-gradient(90deg, #28a745, #20c997); }
    .health-good { background: linear-gradient(90deg, #20c997, #17a2b8); }
    .health-warning { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .health-critical { background: linear-gradient(90deg, #fd7e14, #dc3545); }

    .config-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .config-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #dee2e6;
    }

    .config-item:last-child {
        border-bottom: none;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
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
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #28a745;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .machine-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin: 1rem 0;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: white;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .system-overview {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .overview-card {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .overview-card:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .overview-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .overview-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="machines-config">
    <div class="container-fluid">
        <!-- Header -->
        <div class="config-header animate__animated animate__fadeInDown">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="fas fa-server text-primary me-2"></i>
                        Machine Configuration
                    </h2>
                    <p class="text-muted mb-0">
                        Configure and monitor AI models, APIs, and system components
                    </p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group">
                        <button class="btn btn-outline-primary" onclick="refreshAllMachines()">
                            <i class="fas fa-sync me-1"></i> Refresh
                        </button>
                        <button class="btn btn-outline-warning" onclick="runDiagnostics()">
                            <i class="fas fa-stethoscope me-1"></i> Diagnostics
                        </button>
                        <button class="btn btn-primary" onclick="showSystemLogs()">
                            <i class="fas fa-list me-1"></i> System Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Overview -->
        <div class="system-overview animate__animated animate__fadeInUp">
            <h4 class="mb-3">
                <i class="fas fa-tachometer-alt text-primary me-2"></i>
                System Overview
            </h4>
            <div class="overview-grid">
                <div class="overview-card">
                    <div class="overview-number">{{ count($machines) }}</div>
                    <div class="overview-label">Total Machines</div>
                </div>
                <div class="overview-card">
                    <div class="overview-number">{{ collect($machines)->where('status', 'online')->count() }}</div>
                    <div class="overview-label">Online</div>
                </div>
                <div class="overview-card">
                    <div class="overview-number">{{ round(collect($machines)->avg('health')) }}%</div>
                    <div class="overview-label">Avg Health</div>
                </div>
                <div class="overview-card">
                    <div class="overview-number">99.8%</div>
                    <div class="overview-label">Uptime</div>
                </div>
            </div>
        </div>

        <!-- Machine Cards -->
        <div class="row">
            <!-- AI Machine -->
            <div class="col-lg-6">
                <div class="machine-card machine-{{ $machines['ai']['status'] ?? 'online' }} animate__animated animate__fadeInLeft">
                    <div class="machine-header">
                        <div class="machine-icon machine-ai">
                            <i class="fas fa-brain"></i>
                            <div class="status-indicator status-{{ $machines['ai']['status'] ?? 'online' }}"></div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">AI Analysis Engine</h4>
                            <p class="text-muted mb-0">GPT-4 powered intent analysis and response generation</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-{{ $machines['ai']['status'] === 'online' ? 'success' : ($machines['ai']['status'] === 'maintenance' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($machines['ai']['status'] ?? 'online') }}
                                </span>
                                <small class="text-muted">Health: {{ $machines['ai']['health'] ?? 95 }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="health-bar">
                        <div class="health-fill health-{{ $machines['ai']['health'] >= 90 ? 'excellent' : ($machines['ai']['health'] >= 70 ? 'good' : ($machines['ai']['health'] >= 50 ? 'warning' : 'critical')) }}" 
                             style="width: {{ $machines['ai']['health'] ?? 95 }}%"></div>
                    </div>

                    <div class="machine-stats">
                        <div class="stat-item">
                            <div class="stat-number text-primary">342</div>
                            <div class="stat-label">Requests Today</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-success">1.2s</div>
                            <div class="stat-label">Avg Response</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-info">97%</div>
                            <div class="stat-label">Success Rate</div>
                        </div>
                    </div>

                    <div class="config-section">
                        <h6 class="mb-3">Configuration</h6>
                        <div class="config-item">
                            <span>AI Model</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option value="gpt-4" selected>GPT-4</option>
                                <option value="gpt-3.5">GPT-3.5 Turbo</option>
                                <option value="claude">Claude</option>
                            </select>
                        </div>
                        <div class="config-item">
                            <span>Temperature</span>
                            <input type="range" class="form-range" min="0" max="1" step="0.1" value="0.7" style="width: 100px;">
                        </div>
                        <div class="config-item">
                            <span>Max Tokens</span>
                            <input type="number" class="form-control form-control-sm" value="150" style="width: 80px;">
                        </div>
                        <div class="config-item">
                            <span>Auto-retry Failed Requests</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="testAI()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewAILogs()">
                            <i class="fas fa-file-alt me-1"></i> Logs
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="restartAI()">
                            <i class="fas fa-redo me-1"></i> Restart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Template Machine -->
            <div class="col-lg-6">
                <div class="machine-card machine-{{ $machines['template']['status'] ?? 'online' }} animate__animated animate__fadeInRight">
                    <div class="machine-header">
                        <div class="machine-icon machine-template">
                            <i class="fas fa-file-alt"></i>
                            <div class="status-indicator status-{{ $machines['template']['status'] ?? 'online' }}"></div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Template Engine</h4>
                            <p class="text-muted mb-0">WhatsApp message template processing and delivery</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-{{ $machines['template']['status'] === 'online' ? 'success' : ($machines['template']['status'] === 'maintenance' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($machines['template']['status'] ?? 'online') }}
                                </span>
                                <small class="text-muted">Health: {{ $machines['template']['health'] ?? 98 }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="health-bar">
                        <div class="health-fill health-{{ $machines['template']['health'] >= 90 ? 'excellent' : ($machines['template']['health'] >= 70 ? 'good' : ($machines['template']['health'] >= 50 ? 'warning' : 'critical')) }}" 
                             style="width: {{ $machines['template']['health'] ?? 98 }}%"></div>
                    </div>

                    <div class="machine-stats">
                        <div class="stat-item">
                            <div class="stat-number text-primary">1,247</div>
                            <div class="stat-label">Messages Sent</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-success">0.8s</div>
                            <div class="stat-label">Avg Processing</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-info">99%</div>
                            <div class="stat-label">Delivery Rate</div>
                        </div>
                    </div>

                    <div class="config-section">
                        <h6 class="mb-3">Configuration</h6>
                        <div class="config-item">
                            <span>Template Cache</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="config-item">
                            <span>Auto-variable Detection</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="config-item">
                            <span>Fallback Language</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option value="en" selected>English</option>
                                <option value="hi">Hindi</option>
                                <option value="auto">Auto-detect</option>
                            </select>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="testTemplate()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewTemplateLogs()">
                            <i class="fas fa-file-alt me-1"></i> Logs
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="clearTemplateCache()">
                            <i class="fas fa-trash me-1"></i> Clear Cache
                        </button>
                    </div>
                </div>
            </div>

            <!-- Database Machine -->
            <div class="col-lg-6">
                <div class="machine-card machine-{{ $machines['datatable']['status'] ?? 'online' }} animate__animated animate__fadeInLeft">
                    <div class="machine-header">
                        <div class="machine-icon machine-datatable">
                            <i class="fas fa-database"></i>
                            <div class="status-indicator status-{{ $machines['datatable']['status'] ?? 'online' }}"></div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Database Engine</h4>
                            <p class="text-muted mb-0">Doctor search, user data, and analytics processing</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-{{ $machines['datatable']['status'] === 'online' ? 'success' : ($machines['datatable']['status'] === 'maintenance' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($machines['datatable']['status'] ?? 'online') }}
                                </span>
                                <small class="text-muted">Health: {{ $machines['datatable']['health'] ?? 92 }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="health-bar">
                        <div class="health-fill health-{{ $machines['datatable']['health'] >= 90 ? 'excellent' : ($machines['datatable']['health'] >= 70 ? 'good' : ($machines['datatable']['health'] >= 50 ? 'warning' : 'critical')) }}" 
                             style="width: {{ $machines['datatable']['health'] ?? 92 }}%"></div>
                    </div>

                    <div class="machine-stats">
                        <div class="stat-item">
                            <div class="stat-number text-primary">567</div>
                            <div class="stat-label">Queries Today</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-success">45ms</div>
                            <div class="stat-label">Avg Query Time</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-info">98%</div>
                            <div class="stat-label">Cache Hit Rate</div>
                        </div>
                    </div>

                    <div class="config-section">
                        <h6 class="mb-3">Configuration</h6>
                        <div class="config-item">
                            <span>Query Cache</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="config-item">
                            <span>Index Optimization</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="config-item">
                            <span>Connection Pool Size</span>
                            <input type="number" class="form-control form-control-sm" value="10" style="width: 80px;">
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="testDatabase()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewDBLogs()">
                            <i class="fas fa-file-alt me-1"></i> Logs
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="optimizeDB()">
                            <i class="fas fa-wrench me-1"></i> Optimize
                        </button>
                    </div>
                </div>
            </div>

            <!-- Function Machine -->
            <div class="col-lg-6">
                <div class="machine-card machine-{{ $machines['function']['status'] ?? 'online' }} animate__animated animate__fadeInRight">
                    <div class="machine-header">
                        <div class="machine-icon machine-function">
                            <i class="fas fa-code"></i>
                            <div class="status-indicator status-{{ $machines['function']['status'] ?? 'online' }}"></div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Function Engine</h4>
                            <p class="text-muted mb-0">Custom business logic and API integrations</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-{{ $machines['function']['status'] === 'online' ? 'success' : ($machines['function']['status'] === 'maintenance' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($machines['function']['status'] ?? 'online') }}
                                </span>
                                <small class="text-muted">Health: {{ $machines['function']['health'] ?? 97 }}%</small>
                            </div>
                        </div>
                    </div>

                    <div class="health-bar">
                        <div class="health-fill health-{{ $machines['function']['health'] >= 90 ? 'excellent' : ($machines['function']['health'] >= 70 ? 'good' : ($machines['function']['health'] >= 50 ? 'warning' : 'critical')) }}" 
                             style="width: {{ $machines['function']['health'] ?? 97 }}%"></div>
                    </div>

                    <div class="machine-stats">
                        <div class="stat-item">
                            <div class="stat-number text-primary">234</div>
                            <div class="stat-label">Functions Run</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-success">0.5s</div>
                            <div class="stat-label">Avg Execution</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number text-info">96%</div>
                            <div class="stat-label">Success Rate</div>
                        </div>
                    </div>

                    <div class="config-section">
                        <h6 class="mb-3">Configuration</h6>
                        <div class="config-item">
                            <span>Timeout (seconds)</span>
                            <input type="number" class="form-control form-control-sm" value="30" style="width: 80px;">
                        </div>
                        <div class="config-item">
                            <span>Error Logging</span>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="config-item">
                            <span>Debug Mode</span>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="testFunction()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewFunctionLogs()">
                            <i class="fas fa-file-alt me-1"></i> Logs
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="deployFunctions()">
                            <i class="fas fa-upload me-1"></i> Deploy
                        </button>
                    </div>
                </div>
            </div>

            <!-- Visualization Machine -->
            <div class="col-lg-12">
                <div class="machine-card machine-{{ $machines['visualization']['status'] ?? 'maintenance' }} animate__animated animate__fadeInUp">
                    <div class="machine-header">
                        <div class="machine-icon machine-visualization">
                            <i class="fas fa-chart-bar"></i>
                            <div class="status-indicator status-{{ $machines['visualization']['status'] ?? 'maintenance' }}"></div>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">Visualization Engine</h4>
                            <p class="text-muted mb-0">Chart generation, data visualization, and report creation</p>
                            <div class="d-flex align-items-center mt-2">
                                <span class="badge bg-{{ $machines['visualization']['status'] === 'online' ? 'success' : ($machines['visualization']['status'] === 'maintenance' ? 'warning' : 'danger') }} me-2">
                                    {{ ucfirst($machines['visualization']['status'] ?? 'maintenance') }}
                                </span>
                                <small class="text-muted">Health: {{ $machines['visualization']['health'] ?? 78 }}%</small>
                                @if($machines['visualization']['status'] === 'maintenance')
                                    <span class="badge bg-info ms-2">Scheduled maintenance until 2:00 PM</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="health-bar">
                        <div class="health-fill health-{{ $machines['visualization']['health'] >= 90 ? 'excellent' : ($machines['visualization']['health'] >= 70 ? 'good' : ($machines['visualization']['health'] >= 50 ? 'warning' : 'critical')) }}" 
                             style="width: {{ $machines['visualization']['health'] ?? 78 }}%"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="machine-stats">
                                <div class="stat-item">
                                    <div class="stat-number text-primary">89</div>
                                    <div class="stat-label">Charts Generated</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number text-success">2.1s</div>
                                    <div class="stat-label">Avg Render Time</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-number text-warning">78%</div>
                                    <div class="stat-label">Success Rate</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="config-section">
                                <h6 class="mb-3">Configuration</h6>
                                <div class="config-item">
                                    <span>Chart Library</span>
                                    <select class="form-select form-select-sm" style="width: auto;">
                                        <option value="chartjs" selected>Chart.js</option>
                                        <option value="d3">D3.js</option>
                                        <option value="plotly">Plotly</option>
                                    </select>
                                </div>
                                <div class="config-item">
                                    <span>Image Caching</span>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary" onclick="testVisualization()">
                            <i class="fas fa-play me-1"></i> Test
                        </button>
                        <button class="btn btn-sm btn-outline-info" onclick="viewVisualizationLogs()">
                            <i class="fas fa-file-alt me-1"></i> Logs
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="completeMaintenance()">
                            <i class="fas fa-check me-1"></i> Complete Maintenance
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
    $(document).ready(function() {
        startRealTimeMonitoring();
    });

    function startRealTimeMonitoring() {
        setInterval(() => {
            updateHealthMetrics();
        }, 5000);
    }

    function updateHealthMetrics() {
        // Simulate health metric updates
        document.querySelectorAll('.health-fill').forEach(fill => {
            const currentWidth = parseInt(fill.style.width);
            const variation = Math.floor(Math.random() * 6) - 3; // -3 to +3
            const newWidth = Math.max(60, Math.min(100, currentWidth + variation));
            fill.style.width = `${newWidth}%`;
            
            // Update health class
            fill.className = `health-fill health-${newWidth >= 90 ? 'excellent' : (newWidth >= 70 ? 'good' : (newWidth >= 50 ? 'warning' : 'critical'))}`;
        });
    }

    function refreshAllMachines() {
        Swal.fire({
            title: 'Refreshing Machines...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        setTimeout(() => {
            Swal.fire('Refreshed!', 'All machine statuses updated successfully!', 'success');
            location.reload();
        }, 2000);
    }

    function runDiagnostics() {
        Swal.fire({
            title: 'Running System Diagnostics',
            html: `
                <div class="text-start">
                    <div id="diagnostic-progress">
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                <span>Checking AI Engine...</span>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false
        });

        const tests = [
            'Checking AI Engine...',
            'Testing Template System...',
            'Validating Database Connections...',
            'Verifying Function Engine...',
            'Analyzing Visualization Engine...',
            'Generating Report...'
        ];

        let currentTest = 0;
        const testInterval = setInterval(() => {
            const progress = document.getElementById('diagnostic-progress');
            if (currentTest < tests.length) {
                progress.innerHTML += `
                    <div class="mb-2">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                            <span>${tests[currentTest]}</span>
                        </div>
                    </div>
                `;
                currentTest++;
            } else {
                clearInterval(testInterval);
                setTimeout(() => {
                    Swal.fire({
                        title: 'Diagnostics Complete',
                        html: `
                            <div class="text-start">
                                <div class="alert alert-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    All systems are operating normally
                                </div>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="h4 text-success">5/5</div>
                                        <small>Machines Online</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="h4 text-primary">0</div>
                                        <small>Critical Issues</small>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <strong>Recommendations:</strong>
                                    <ul class="list-unstyled mt-2">
                                        <li>• Complete visualization engine maintenance</li>
                                        <li>• Monitor database query performance</li>
                                        <li>• Update AI model configurations</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Close'
                    });
                }, 1000);
            }
        }, 800);
    }

    function showSystemLogs() {
        Swal.fire({
            title: 'System Logs',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <select class="form-select form-select-sm" id="log-filter">
                            <option value="all">All Logs</option>
                            <option value="ai">AI Engine</option>
                            <option value="template">Template Engine</option>
                            <option value="database">Database</option>
                            <option value="function">Function Engine</option>
                            <option value="visualization">Visualization</option>
                        </select>
                    </div>
                    <div class="log-container" style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 1rem; border-radius: 5px;">
                        <div class="log-entry">
                            <span class="text-success">[INFO]</span> 
                            <span class="text-muted">2024-01-20 14:32:15</span> 
                            AI Engine: Successfully processed 234 requests
                        </div>
                        <div class="log-entry">
                            <span class="text-warning">[WARN]</span> 
                            <span class="text-muted">2024-01-20 14:30:42</span> 
                            Visualization: Maintenance mode activated
                        </div>
                        <div class="log-entry">
                            <span class="text-success">[INFO]</span> 
                            <span class="text-muted">2024-01-20 14:28:33</span> 
                            Database: Query cache hit rate 98%
                        </div>
                        <div class="log-entry">
                            <span class="text-primary">[DEBUG]</span> 
                            <span class="text-muted">2024-01-20 14:25:17</span> 
                            Function Engine: Deployed new business logic functions
                        </div>
                    </div>
                </div>
            `,
            width: 700,
            confirmButtonText: 'Close'
        });
    }

    // Individual machine test functions
    function testAI() {
        runMachineTest('AI Engine', 'Testing intent analysis and response generation...');
    }

    function testTemplate() {
        runMachineTest('Template Engine', 'Testing message template processing...');
    }

    function testDatabase() {
        runMachineTest('Database Engine', 'Testing query performance and connections...');
    }

    function testFunction() {
        runMachineTest('Function Engine', 'Testing custom business logic execution...');
    }

    function testVisualization() {
        runMachineTest('Visualization Engine', 'Testing chart generation and rendering...');
    }

    function runMachineTest(machineName, testDescription) {
        Swal.fire({
            title: `Testing ${machineName}`,
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                            <span>${testDescription}</span>
                        </div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false
        });

        setTimeout(() => {
            const success = Math.random() > 0.1; // 90% success rate
            Swal.fire({
                title: success ? 'Test Passed' : 'Test Failed',
                html: `
                    <div class="text-start">
                        <div class="alert alert-${success ? 'success' : 'danger'} mb-3">
                            <i class="fas fa-${success ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                            ${machineName} ${success ? 'is working correctly' : 'encountered an error'}
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="h5">${success ? '100%' : '0%'}</div>
                                <small>Success Rate</small>
                            </div>
                            <div class="col-4">
                                <div class="h5">${(Math.random() * 2 + 0.5).toFixed(1)}s</div>
                                <small>Response Time</small>
                            </div>
                            <div class="col-4">
                                <div class="h5">${success ? 'Pass' : 'Fail'}</div>
                                <small>Status</small>
                            </div>
                        </div>
                    </div>
                `,
                icon: success ? 'success' : 'error',
                confirmButtonText: 'Close'
            });
        }, 2000);
    }

    function completeMaintenance() {
        Swal.fire({
            title: 'Complete Maintenance?',
            text: 'This will bring the Visualization Engine back online.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Complete Maintenance',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Maintenance Complete!', 'Visualization Engine is now online.', 'success');
                setTimeout(() => location.reload(), 1500);
            }
        });
    }

    // Log viewing functions
    function viewAILogs() { showMachineLogs('AI Engine'); }
    function viewTemplateLogs() { showMachineLogs('Template Engine'); }
    function viewDBLogs() { showMachineLogs('Database Engine'); }
    function viewFunctionLogs() { showMachineLogs('Function Engine'); }
    function viewVisualizationLogs() { showMachineLogs('Visualization Engine'); }

    function showMachineLogs(machineName) {
        Swal.fire({
            title: `${machineName} Logs`,
            html: `
                <div class="text-start">
                    <div class="log-container" style="max-height: 400px; overflow-y: auto; background: #f8f9fa; padding: 1rem; border-radius: 5px; font-family: monospace; font-size: 0.8rem;">
                        <div class="mb-1"><span class="text-success">[INFO]</span> ${new Date().toISOString()} - ${machineName} started successfully</div>
                        <div class="mb-1"><span class="text-primary">[DEBUG]</span> ${new Date().toISOString()} - Processing request batch #234</div>
                        <div class="mb-1"><span class="text-success">[INFO]</span> ${new Date().toISOString()} - Request completed in 1.2s</div>
                        <div class="mb-1"><span class="text-warning">[WARN]</span> ${new Date().toISOString()} - High memory usage detected: 78%</div>
                        <div class="mb-1"><span class="text-success">[INFO]</span> ${new Date().toISOString()} - Cache cleared automatically</div>
                    </div>
                </div>
            `,
            width: 800,
            confirmButtonText: 'Close'
        });
    }

    // Additional functions
    function restartAI() {
        confirmRestart('AI Engine');
    }

    function clearTemplateCache() {
        Swal.fire({
            title: 'Clear Template Cache?',
            text: 'This will remove all cached templates and may temporarily slow response times.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Clear Cache',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Cache Cleared!', 'Template cache has been cleared successfully.', 'success');
            }
        });
    }

    function optimizeDB() {
        Swal.fire({
            title: 'Optimize Database?',
            text: 'This will rebuild indexes and optimize query performance.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Optimize',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Optimizing...', 'Database optimization in progress...', 'info');
                setTimeout(() => {
                    Swal.fire('Optimized!', 'Database has been optimized successfully.', 'success');
                }, 3000);
            }
        });
    }

    function deployFunctions() {
        Swal.fire({
            title: 'Deploy Functions?',
            text: 'This will deploy the latest function configurations.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Deploy',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Deploying...', 'Function deployment in progress...', 'info');
                setTimeout(() => {
                    Swal.fire('Deployed!', 'Functions have been deployed successfully.', 'success');
                }, 2500);
            }
        });
    }

    function confirmRestart(machineName) {
        Swal.fire({
            title: `Restart ${machineName}?`,
            text: 'This will temporarily interrupt service for this machine.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Restart',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire('Restarting...', `${machineName} is restarting...`, 'info');
                setTimeout(() => {
                    Swal.fire('Restarted!', `${machineName} has been restarted successfully.`, 'success');
                }, 3000);
            }
        });
    }
</script>
@endpush
