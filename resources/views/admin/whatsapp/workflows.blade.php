@extends('admin.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/whatsapp-layout.css') }}">
@endpush

@section('title', 'WhatsApp Workflows')

@section('content')
<div class="container-fluid">
    <div class="whatsapp-content">
        <!-- Page Title -->
        <div class="page-title-box">
            <h4 class="gradient-text-green">WhatsApp Workflows</h4>
            <p style="color: #6b7280; margin: 0.5rem 0 0 0; font-size: 0.9rem;">Create and manage automated conversation workflows</p>
        </div>

        <!-- Workflow Stats -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="stat-number">{{ $totalWorkflows ?? 0 }}</div>
                    <p class="stat-label">Total Workflows</p>
                    <small style="color: #6b7280; font-size: 0.75rem;">All created workflows</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="stat-number">{{ $activeWorkflows ?? 0 }}</div>
                    <p class="stat-label">Active Workflows</p>
                    <small style="color: #10b981; font-size: 0.75rem;">
                        <span class="live-indicator"></span>Currently running
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ $completedWorkflows ?? 0 }}</div>
                    <p class="stat-label">Completed</p>
                    <small style="color: #06b6d4; font-size: 0.75rem;">
                        Successfully executed
                    </small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="compact-stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-number">{{ $successRate ?? 0 }}%</div>
                    <p class="stat-label">Success Rate</p>
                    <small style="color: #8b5cf6; font-size: 0.75rem;">
                        Overall efficiency
                    </small>
                </div>
            </div>
        </div>

        <!-- Create New Workflow -->
        <div class="whatsapp-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Create New Workflow</h6>
                        <small class="text-muted">Build automated conversation flows for better user experience</small>
                    </div>
                    <a href="{{ route('admin.whatsapp.workflows.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Workflow
                    </a>
                </div>
            </div>
        </div>

        <!-- Workflows List -->
        <div class="whatsapp-card">
            <div class="whatsapp-header">
                <h4><i class="fas fa-sitemap me-2"></i>Workflow Scenarios</h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-light btn-sm" onclick="refreshWorkflows()">
                        <i class="fas fa-sync-alt me-1"></i>Refresh
                    </button>
                    <button class="btn btn-light btn-sm" onclick="exportWorkflows()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($workflows) && $workflows->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="padding: 1rem;">Workflow</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Intent</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($workflows as $workflow)
                                <tr onclick="window.location.href='{{ route('admin.whatsapp.workflows.show', $workflow) }}'" style="cursor: pointer;">
                                    <td style="padding: 1rem;">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon me-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                                <i class="fas fa-project-diagram"></i>
                                            </div>
                                            <div>
                                                <div style="font-weight: 600; color: #374151;">{{ $workflow->name }}</div>
                                                <small style="color: #6b7280;">ID: {{ $workflow->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $workflow->status === 'completed' ? 'status-active' : ($workflow->status === 'failed' ? 'status-offline' : ($workflow->status === 'running' ? 'status-warning' : 'status-offline')) }}">
                                            @if($workflow->status === 'running')
                                                <span class="live-indicator"></span>
                                            @endif
                                            {{ ucfirst($workflow->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress mb-1" style="height: 8px; background: #f1f5f9;">
                                            <div class="progress-bar" style="background: linear-gradient(90deg, #25d366, #128c7e); width: {{ $workflow->getProgressPercentage() }}%;"></div>
                                        </div>
                                        <small style="color: #6b7280;">{{ $workflow->current_step }}/{{ $workflow->total_steps }} steps</small>
                                    </td>
                                    <td>
                                        <span style="color: #374151; font-size: 0.9rem;">{{ $workflow->intent ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span style="color: #6b7280; font-size: 0.85rem;">{{ $workflow->created_at->format('M j, Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.whatsapp.workflows.show', $workflow) }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.whatsapp.workflows.edit', $workflow) }}" class="btn btn-outline-warning btn-sm" onclick="event.stopPropagation();">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($workflow->status === 'failed')
                                                <form action="{{ route('admin.whatsapp.workflows.retry', $workflow) }}" method="POST" style="display: inline;" onclick="event.stopPropagation();">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($workflows->hasPages())
                    <div class="d-flex justify-content-center p-3">
                        {{ $workflows->links() }}
                    </div>
                    @endif
                @else
                    <div class="text-center p-5">
                        <div class="stat-icon mx-auto mb-3" style="background: linear-gradient(135deg, #6b7280 0%, #374151 100%);">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h6 class="text-muted mb-2">No Workflows Created</h6>
                        <p class="text-muted mb-3" style="font-size: 0.9rem;">Create your first WhatsApp workflow scenario to get started with automation</p>
                        <a href="{{ route('admin.whatsapp.workflows.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Create First Workflow
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshWorkflows() {
    Swal.fire({
        title: 'Refreshing...',
        text: 'Loading latest workflows',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    setTimeout(() => {
        location.reload();
    }, 1500);
}

function exportWorkflows() {
    Swal.fire({
        title: 'Export Workflows',
        text: 'Choose export format',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'JSON Export',
        cancelButtonText: 'CSV Export',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Export JSON
            window.open('/admin/whatsapp/workflows/export?format=json', '_blank');
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Export CSV
            window.open('/admin/whatsapp/workflows/export?format=csv', '_blank');
        }
    });
}

// Auto-refresh every 30 seconds for live updates
setInterval(function() {
    if (!document.querySelector('.modal') || !document.querySelector('.modal').classList.contains('show')) {
        // Only refresh stats, not full page
        fetch('/admin/whatsapp/workflows/stats')
            .then(response => response.json())
            .then(data => {
                if (data.stats) {
                    // Update stat numbers
                    const statNumbers = document.querySelectorAll('.stat-number');
                    if (statNumbers[0]) statNumbers[0].textContent = data.stats.total || 0;
                    if (statNumbers[1]) statNumbers[1].textContent = data.stats.active || 0;
                    if (statNumbers[2]) statNumbers[2].textContent = data.stats.completed || 0;
                    if (statNumbers[3]) statNumbers[3].textContent = (data.stats.success_rate || 0) + '%';
                }
            })
            .catch(error => console.log('Stats refresh failed:', error));
    }
}, 30000);
</script>
@endpush

    <!-- Workflows Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Workflow Scenarios</h4>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.whatsapp.workflows.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create New Workflow
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(isset($workflows) && $workflows->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Intent</th>
                                        <th>Progress</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workflows as $workflow)
                                        <tr>
                                            <td>{{ $workflow->id }}</td>
                                            <td>{{ $workflow->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $workflow->status === 'completed' ? 'success' : ($workflow->status === 'failed' ? 'danger' : ($workflow->status === 'running' ? 'warning' : 'secondary')) }}">
                                                    {{ ucfirst($workflow->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $workflow->intent ?? 'N/A' }}</td>
                                            <td>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $workflow->getProgressPercentage() }}%" aria-valuenow="{{ $workflow->getProgressPercentage() }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small>{{ $workflow->current_step }}/{{ $workflow->total_steps }} steps</small>
                                            </td>
                                            <td>{{ $workflow->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.whatsapp.workflows.show', $workflow) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.whatsapp.workflows.edit', $workflow) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @if($workflow->status === 'failed')
                                                        <form action="{{ route('admin.whatsapp.workflows.retry', $workflow) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-redo"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $workflows->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                            <h5>No workflows found</h5>
                            <p class="text-muted">Create your first WhatsApp workflow scenario to get started.</p>
                            <a href="{{ route('admin.whatsapp.workflows.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Workflow
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-refresh every 30 seconds for live updates
    setInterval(function() {
        if (!$('.modal').hasClass('show')) {
            location.reload();
        }
    }, 30000);
});
</script>
@endsection
