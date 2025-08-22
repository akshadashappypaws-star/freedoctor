@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Automation Rules')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Automation Rules</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Automation</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Auto-Response Rules</h4>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRuleModal">
                                <i class="fas fa-plus"></i> Create Rule
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['total_rules'] ?? 0 }}</h4>
                                            <p class="mb-0">Total Rules</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-cogs fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['active_rules'] ?? 0 }}</h4>
                                            <p class="mb-0">Active Rules</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-play fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['triggered_today'] ?? 0 }}</h4>
                                            <p class="mb-0">Triggered Today</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-fire fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $statistics['success_rate'] ?? 0 }}%</h4>
                                            <p class="mb-0">Success Rate</p>
                                        </div>
                                        <div>
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($ruleTemplates) && count($ruleTemplates) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rule Name</th>
                                        <th>Description</th>
                                        <th>Trigger Type</th>
                                        <th>Machine</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ruleTemplates as $key => $rule)
                                        <tr>
                                            <td><strong>{{ $rule['name'] ?? 'Unnamed Rule' }}</strong></td>
                                            <td>{{ $rule['description'] ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $rule['trigger_type'] ?? 'N/A')) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($rule['machine'] ?? 'N/A') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Template</span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="activateRule('{{ $key }}')">
                                                    <i class="fas fa-play"></i> Activate
                                                </button>
                                                <button class="btn btn-sm btn-info" onclick="viewRule('{{ $key }}')">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-robot fa-3x text-muted mb-3"></i>
                            <h5>No automation rules</h5>
                            <p class="text-muted">Create automation rules to automatically respond to user messages.</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRuleModal">
                                <i class="fas fa-plus"></i> Create First Rule
                            </button>
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
function activateRule(ruleKey) {
    if (confirm('Are you sure you want to activate this automation rule?')) {
        // Here you would make an AJAX call to activate the rule
        console.log('Activating rule:', ruleKey);
        
        // For now, just show a success message
        alert('Rule activated successfully! (This is a demo)');
    }
}

function viewRule(ruleKey) {
    // Here you would show a modal with rule details
    console.log('Viewing rule:', ruleKey);
    alert('Rule details would be shown here (This is a demo)');
}

$(document).ready(function() {
    // Auto-refresh every 30 seconds
    setInterval(function() {
        if (!$('.modal').hasClass('show')) {
            location.reload();
        }
    }, 30000);
});
</script>
@endsection
