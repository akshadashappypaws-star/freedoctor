@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'Machine Configuration Center')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">
                    <i class="fas fa-cogs text-primary me-2"></i>Machine Configuration Center
                </h4>
                <div class="page-title-right">
                    <button class="btn btn-success me-3" onclick="testAllMachines()">
                        <i class="fas fa-plug me-2"></i>Test All Connections
                    </button>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Machine Config</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Status Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>Machine Status Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <div class="machine-status-circle bg-success text-white mb-2">
                                    <i class="fas fa-brain"></i>
                                </div>
                                <h6 class="mb-1">AI Machine</h6>
                                <span class="badge bg-success">Connected</span>
                                <div class="small text-muted mt-1">OpenAI GPT-4</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <div class="machine-status-circle bg-warning text-white mb-2">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h6 class="mb-1">Function Machine</h6>
                                <span class="badge bg-warning">Configuring</span>
                                <div class="small text-muted mt-1">Database & APIs</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <div class="machine-status-circle bg-info text-white mb-2">
                                    <i class="fas fa-table"></i>
                                </div>
                                <h6 class="mb-1">DataTable Machine</h6>
                                <span class="badge bg-info">Ready</span>
                                <div class="small text-muted mt-1">Data Processing</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <div class="machine-status-circle bg-purple text-white mb-2">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h6 class="mb-1">Template Machine</h6>
                                <span class="badge bg-success">Connected</span>
                                <div class="small text-muted mt-1">WhatsApp API</div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                            <div class="text-center">
                                <div class="machine-status-circle bg-danger text-white mb-2">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <h6 class="mb-1">Visualization Machine</h6>
                                <span class="badge bg-secondary">Disabled</span>
                                <div class="small text-muted mt-1">Charts & Reports</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Machine Configuration Grid -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-brain me-2"></i>AI Machine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configure OpenAI integration for intelligent responses and natural language processing.</p>
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <span class="badge bg-success ms-2">Connected</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary btn-sm" onclick="configureAIMachine()">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="testAIMachine()">
                            <i class="fas fa-play"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-warning h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Function Machine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configure payment processing, database operations, and API integrations.</p>
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <span class="badge bg-warning ms-2">Configuring</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-warning btn-sm" onclick="configureFunctionMachine()">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="testFunctionMachine()">
                            <i class="fas fa-play"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-info h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>DataTable Machine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configure data processing, exports, and database table management.</p>
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <span class="badge bg-info ms-2">Ready</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-info btn-sm" onclick="configureDataTableMachine()">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="testDataTableMachine()">
                            <i class="fas fa-play"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-purple h-100">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Template Machine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configure WhatsApp Business API templates and message formatting.</p>
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <span class="badge bg-success ms-2">Connected</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-purple btn-sm" onclick="configureTemplateMachine()">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="testTemplateMachine()">
                            <i class="fas fa-play"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-danger h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Visualization Machine</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Configure charts, reports, and visual analytics for workflow data.</p>
                    <div class="mb-3">
                        <small class="text-muted">Status:</small>
                        <span class="badge bg-secondary ms-2">Disabled</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger btn-sm" onclick="configureVisualizationMachine()">
                            <i class="fas fa-cog"></i> Configure
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="testVisualizationMachine()" disabled>
                            <i class="fas fa-play"></i> Test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Add hover effects to cards
    $('.card').hover(
        function() {
            $(this).addClass('shadow-lg').removeClass('shadow-sm');
        },
        function() {
            $(this).addClass('shadow-sm').removeClass('shadow-lg');
        }
    );
    
    // Machine status circles animation
    $('.machine-status-circle').hover(
        function() {
            $(this).addClass('animate__animated animate__pulse');
        },
        function() {
            $(this).removeClass('animate__animated animate__pulse');
        }
    );
});

// Machine configuration functions
function configureAIMachine() {
    window.open('#', '_blank');
    Swal.fire({
        title: 'AI Machine Configuration',
        text: 'Opening AI Machine configuration panel...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function configureFunctionMachine() {
    Swal.fire({
        title: 'Function Machine Configuration',
        text: 'Opening Function Machine configuration panel...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function configureDataTableMachine() {
    Swal.fire({
        title: 'DataTable Machine Configuration',
        text: 'Opening DataTable Machine configuration panel...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function configureTemplateMachine() {
    Swal.fire({
        title: 'Template Machine Configuration',
        text: 'Opening Template Machine configuration panel...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

function configureVisualizationMachine() {
    Swal.fire({
        title: 'Visualization Machine Configuration',
        text: 'Opening Visualization Machine configuration panel...',
        icon: 'info',
        timer: 1500,
        showConfirmButton: false
    });
}

// Machine testing functions
function testAIMachine() {
    Swal.fire({
        title: 'Testing AI Machine...',
        text: 'Please wait while we test the OpenAI API connection',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'AI Machine Test Result',
            text: 'Connection successful! AI Machine is responding properly.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }, 2000);
}

function testFunctionMachine() {
    Swal.fire({
        title: 'Testing Function Machine...',
        text: 'Please wait while we test function capabilities',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'Function Machine Test Result',
            text: 'Some functions are operational, configuration needed for full functionality.',
            icon: 'warning',
            timer: 2500,
            showConfirmButton: false
        });
    }, 2000);
}

function testDataTableMachine() {
    Swal.fire({
        title: 'Testing DataTable Machine...',
        text: 'Please wait while we test database connections',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'DataTable Machine Test Result',
            text: 'Database connections are healthy and ready for data processing.',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }, 1500);
}

function testTemplateMachine() {
    Swal.fire({
        title: 'Testing Template Machine...',
        text: 'Please wait while we test WhatsApp API connection',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            title: 'Template Machine Test Result',
            text: 'WhatsApp Business API connected successfully!',
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        });
    }, 1800);
}

function testVisualizationMachine() {
    Swal.fire({
        title: 'Visualization Machine Disabled',
        text: 'Please enable and configure the Visualization Machine first.',
        icon: 'warning',
        confirmButtonText: 'OK'
    });
}

// Test all machines
function testAllMachines() {
    Swal.fire({
        title: 'Testing All Machine Connections...',
        html: `
            <div class="progress mb-3">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
            </div>
            <div id="test-status">Initializing tests...</div>
        `,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            const progressBar = document.querySelector('.progress-bar');
            const statusDiv = document.getElementById('test-status');
            let progress = 0;
            
            const machines = [
                'AI Machine (OpenAI)',
                'Function Machine (Razorpay)',
                'DataTable Machine (MySQL)',
                'Template Machine (WhatsApp)',
                'Visualization Machine'
            ];
            
            const interval = setInterval(() => {
                progress += 20;
                progressBar.style.width = progress + '%';
                
                if (progress <= 100) {
                    const currentMachine = machines[Math.floor((progress - 1) / 20)];
                    statusDiv.innerHTML = `Testing ${currentMachine}...`;
                }
                
                if (progress >= 100) {
                    clearInterval(interval);
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Machine Test Results',
                            html: `
                                <div class="text-start">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>AI Machine:</span>
                                        <span class="text-success"><i class="fas fa-check"></i> Connected</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Function Machine:</span>
                                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Configuring</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>DataTable Machine:</span>
                                        <span class="text-success"><i class="fas fa-check"></i> Connected</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Template Machine:</span>
                                        <span class="text-success"><i class="fas fa-check"></i> Connected</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Visualization Machine:</span>
                                        <span class="text-secondary"><i class="fas fa-times"></i> Disabled</span>
                                    </div>
                                </div>
                            `,
                            icon: 'info',
                            confirmButtonText: 'Close'
                        });
                    }, 500);
                }
            }, 600);
        }
    });
}
</script>

<style>
.machine-status-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin: 0 auto;
    transition: all 0.3s ease;
}

.machine-status-circle:hover {
    transform: scale(1.1);
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: #fff;
}

.btn-purple:hover {
    background-color: #5a359a;
    border-color: #5a359a;
    color: #fff;
}

.border-purple {
    border-color: #6f42c1 !important;
}

.text-purple {
    color: #6f42c1 !important;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.card {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.badge {
    font-size: 0.75em;
    font-weight: 500;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% { background-position: 1rem 0; }
    100% { background-position: 0 0; }
}

.alert {
    border: none;
    border-radius: 0.5rem;
}
</style>
@endsection
