@extends('admin.master')

@section('title', 'Workflow Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Workflow Details</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.workflows') }}">Workflows</a></li>
                        <li class="breadcrumb-item active">{{ $workflow->name ?? 'Details' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if(isset($workflow))
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ $workflow->name }}</h4>
                </div>
                <div class="card-body">
                    <h6>Status: <span class="badge badge-{{ $workflow->status === 'completed' ? 'success' : ($workflow->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($workflow->status) }}</span></h6>
                    <p><strong>Intent:</strong> {{ $workflow->intent ?? 'N/A' }}</p>
                    <p><strong>Progress:</strong> {{ $workflow->current_step ?? 0 }}/{{ $workflow->total_steps ?? 0 }} steps</p>
                    <p><strong>Created:</strong> {{ $workflow->created_at ? $workflow->created_at->format('M j, Y H:i') : 'N/A' }}</p>
                    
                    @if(isset($timeline))
                    <h6>Timeline</h6>
                    <div class="timeline">
                        @foreach($timeline as $event)
                        <div class="timeline-item">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>{{ $event['title'] ?? 'Event' }}</h6>
                                <p>{{ $event['description'] ?? 'No description' }}</p>
                                <small class="text-muted">{{ $event['timestamp'] ?? 'Unknown time' }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Performance Metrics</h4>
                </div>
                <div class="card-body">
                    @if(isset($performanceMetrics))
                        @foreach($performanceMetrics as $key => $value)
                        <div class="d-flex justify-content-between">
                            <span>{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <strong>{{ $value }}</strong>
                        </div>
                        @endforeach
                    @else
                        <p>No performance metrics available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Workflow not found</h5>
                    <a href="{{ route('admin.whatsapp.workflows') }}" class="btn btn-primary">Back to Workflows</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
