@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'Workflow Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="whatsapp-card mb-3">
        <div class="whatsapp-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">
                        <i class="fas fa-project-diagram me-2"></i>
                        Workflow Management
                    </h4>
                    <small class="opacity-90">
                        Create and manage automated conversation workflows
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-primary btn-sm" onclick="createNewWorkflow()">
                        <i class="fas fa-plus me-1"></i>
                        New Workflow
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-3">
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['active_workflows'] ?? 5 }}</div>
                <p class="stat-label">Active Workflows</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $stats['pending_workflows'] ?? 2 }}</div>
                <p class="stat-label">Pending</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number">{{ $stats['completed_today'] ?? 47 }}</div>
                <p class="stat-label">Completed Today</p>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="compact-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number">95%</div>
                <p class="stat-label">Success Rate</p>
            </div>
        </div>
    </div>

    <!-- Workflows Grid -->
    <div class="whatsapp-card">
        <div class="card-header bg-light border-bottom">
            <h6 class="mb-0">
                <i class="fas fa-list text-primary me-2"></i>
                Active Workflows
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Doctor Search Workflow -->
                <div class="col-md-6 mb-3">
                    <div class="workflow-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="workflow-icon bg-success text-white">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <span class="compact-badge bg-success text-white">
                                <span class="live-indicator"></span>
                                ACTIVE
                            </span>
                        </div>
                        <h6 class="workflow-title">Doctor Search Flow</h6>
                        <p class="workflow-description">Automated doctor search and booking workflow</p>
                        <div class="workflow-stats">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>24 runs today
                                <i class="fas fa-check-circle ms-2 me-1"></i>98% success
                            </small>
                        </div>
                        <div class="workflow-actions mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="editWorkflow(1)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="viewWorkflowStats(1)">
                                <i class="fas fa-chart-bar"></i> Stats
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="pauseWorkflow(1)">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Appointment Booking Workflow -->
                <div class="col-md-6 mb-3">
                    <div class="workflow-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="workflow-icon bg-primary text-white">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="compact-badge bg-success text-white">
                                <span class="live-indicator"></span>
                                ACTIVE
                            </span>
                        </div>
                        <h6 class="workflow-title">Appointment Booking</h6>
                        <p class="workflow-description">Complete appointment scheduling system</p>
                        <div class="workflow-stats">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>18 runs today
                                <i class="fas fa-check-circle ms-2 me-1"></i>94% success
                            </small>
                        </div>
                        <div class="workflow-actions mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="editWorkflow(2)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="viewWorkflowStats(2)">
                                <i class="fas fa-chart-bar"></i> Stats
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="pauseWorkflow(2)">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Emergency Response Workflow -->
                <div class="col-md-6 mb-3">
                    <div class="workflow-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="workflow-icon bg-danger text-white">
                                <i class="fas fa-ambulance"></i>
                            </div>
                            <span class="compact-badge bg-success text-white">
                                <span class="live-indicator"></span>
                                ACTIVE
                            </span>
                        </div>
                        <h6 class="workflow-title">Emergency Response</h6>
                        <p class="workflow-description">Urgent medical assistance workflow</p>
                        <div class="workflow-stats">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>7 runs today
                                <i class="fas fa-check-circle ms-2 me-1"></i>100% success
                            </small>
                        </div>
                        <div class="workflow-actions mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="editWorkflow(3)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="viewWorkflowStats(3)">
                                <i class="fas fa-chart-bar"></i> Stats
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" onclick="pauseWorkflow(3)">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                        </div>
                    </div>
                </div>

                <!-- General Inquiry Workflow -->
                <div class="col-md-6 mb-3">
                    <div class="workflow-item">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="workflow-icon bg-info text-white">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span class="compact-badge bg-warning text-dark">PAUSED</span>
                        </div>
                        <h6 class="workflow-title">General Inquiry</h6>
                        <p class="workflow-description">Handle general medical questions</p>
                        <div class="workflow-stats">
                            <small class="text-muted">
                                <i class="fas fa-pause me-1"></i>Paused
                                <i class="fas fa-clock ms-2 me-1"></i>Last run: 2h ago
                            </small>
                        </div>
                        <div class="workflow-actions mt-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="editWorkflow(4)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="viewWorkflowStats(4)">
                                <i class="fas fa-chart-bar"></i> Stats
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="resumeWorkflow(4)">
                                <i class="fas fa-play"></i> Resume
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="whatsapp-card mt-3">
        <div class="card-header bg-light border-bottom">
            <h6 class="mb-0">
                <i class="fas fa-history text-primary me-2"></i>
                Recent Workflow Activity
            </h6>
        </div>
        <div class="card-body">
            <div class="activity-feed">
                <div class="activity-item">
                    <div class="activity-icon bg-success text-white">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="activity-message">Doctor Search workflow completed successfully</div>
                        <div class="activity-time">2 minutes ago</div>
                    </div>
                    <span class="compact-badge bg-success text-white">Completed</span>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon bg-primary text-white">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="activity-message">Appointment Booking workflow started</div>
                        <div class="activity-time">5 minutes ago</div>
                    </div>
                    <span class="compact-badge bg-primary text-white">Running</span>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon bg-warning text-white">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="activity-message">General Inquiry workflow paused by admin</div>
                        <div class="activity-time">1 hour ago</div>
                    </div>
                    <span class="compact-badge bg-warning text-dark">Paused</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createNewWorkflow() {
    Swal.fire({
        title: 'Create New Workflow',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Workflow Name</label>
                    <input type="text" class="form-control" id="workflowName" placeholder="Enter workflow name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" id="workflowDescription" rows="3" placeholder="Describe this workflow"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trigger Type</label>
                    <select class="form-select" id="triggerType">
                        <option value="keyword">Keyword Trigger</option>
                        <option value="intent">AI Intent Detection</option>
                        <option value="time">Time-based</option>
                        <option value="event">Event-based</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Create Workflow',
        cancelButtonText: 'Cancel',
        width: 600
    }).then((result) => {
        if (result.isConfirmed) {
            const name = document.getElementById('workflowName').value;
            const description = document.getElementById('workflowDescription').value;
            const triggerType = document.getElementById('triggerType').value;
            
            if (name && description) {
                Swal.fire({
                    title: 'Creating Workflow...',
                    text: 'Setting up your new workflow',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => Swal.showLoading()
                });
                
                setTimeout(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Workflow Created!',
                        text: `"${name}" workflow has been created successfully`,
                        timer: 2000
                    });
                }, 2000);
            } else {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
            }
        }
    });
}

function editWorkflow(id) {
    Swal.fire({
        title: 'Edit Workflow',
        text: `Opening workflow editor for workflow ID: ${id}`,
        icon: 'info',
        timer: 1500
    });
}

function viewWorkflowStats(id) {
    Swal.fire({
        title: 'Workflow Statistics',
        html: `
            <div class="text-start">
                <h6>Performance Metrics:</h6>
                <ul class="small">
                    <li>Total Executions: 247</li>
                    <li>Success Rate: 96%</li>
                    <li>Avg Response Time: 1.2s</li>
                    <li>User Satisfaction: 4.8/5</li>
                </ul>
            </div>
        `,
        confirmButtonText: 'Close'
    });
}

function pauseWorkflow(id) {
    Swal.fire({
        title: 'Pause Workflow?',
        text: 'This will temporarily stop the workflow from executing',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Pause',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Paused!', 'Workflow has been paused successfully', 'success');
        }
    });
}

function resumeWorkflow(id) {
    Swal.fire({
        title: 'Resume Workflow?',
        text: 'This will reactivate the workflow',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Resume',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('Resumed!', 'Workflow is now active', 'success');
        }
    });
}

console.log('✅ Workflow Management loaded successfully');
</script>
@endpush
