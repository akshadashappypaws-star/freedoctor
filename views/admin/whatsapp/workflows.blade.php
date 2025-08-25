@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Scenario Workflows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Scenario Workflows</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Workflows</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Total Workflows</h5>
                            <h4 class="font-size-24 mb-0">{{ $totalWorkflows ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded">
                                <i class="fas fa-project-diagram"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Active Workflows</h5>
                            <h4 class="font-size-24 mb-0">{{ $activeWorkflows ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success rounded">
                                <i class="fas fa-play"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Completed</h5>
                            <h4 class="font-size-24 mb-0">{{ $completedWorkflows ?? 0 }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info rounded">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-1">
                            <h5 class="font-size-14 mb-1">Success Rate</h5>
                            <h4 class="font-size-24 mb-0">{{ $successRate ?? 0 }}%</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning rounded">
                                <i class="fas fa-chart-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
