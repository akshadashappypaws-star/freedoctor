@extends('admin.master')

@section('title', 'Analytics & Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Analytics & Reports</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Overview -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-size-14 mb-1">Total Messages</h5>
                    <h4 class="font-size-24 mb-0">{{ $analytics['total_messages'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-size-14 mb-1">Success Rate</h5>
                    <h4 class="font-size-24 mb-0">{{ $analytics['success_rate'] ?? 0 }}%</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-size-14 mb-1">Avg Response Time</h5>
                    <h4 class="font-size-24 mb-0">{{ $analytics['avg_response_time'] ?? 0 }}ms</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-size-14 mb-1">Active Users</h5>
                    <h4 class="font-size-24 mb-0">{{ $analytics['active_users'] ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Performance Metrics</h4>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5>Analytics Dashboard</h5>
                        <p class="text-muted">Detailed performance metrics and reports will be displayed here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
