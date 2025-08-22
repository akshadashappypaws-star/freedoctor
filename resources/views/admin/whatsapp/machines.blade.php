@extends('admin.pages.whatsapp.layouts.whatsapp')

@section('title', 'Machine Configurations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Machine Configurations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Machines</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">AI, Function, DataTable, Template & Visualization Machines</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(isset($machineInfo))
                            @foreach($machineInfo as $machineType => $info)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm rounded-circle bg-gradient-{{ $loop->index % 5 + 1 }} d-flex align-items-center justify-content-center">
                                                        <i class="{{ $info['icon'] }} text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="mb-1">{{ $info['name'] }}</h5>
                                                    <span class="badge bg-success-subtle text-success">
                                                        {{ isset($machineConfigs[$machineType]) ? count($machineConfigs[$machineType]) : 0 }} configs
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <p class="text-muted mb-3">{{ $info['description'] }}</p>
                                            
                                            <div class="mb-3">
                                                <h6 class="text-muted mb-2">Capabilities:</h6>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($info['capabilities'] as $capability)
                                                        <span class="badge bg-light text-dark">{{ $capability }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.whatsapp.machines.config', $machineType) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-cog"></i> Configure
                                                </a>
                                                <button class="btn btn-outline-secondary btn-sm" onclick="testMachine('{{ $machineType }}')">
                                                    <i class="fas fa-play"></i> Test
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach(['ai', 'function', 'datatable', 'template', 'visualization'] as $machineType)
                                <div class="col-md-4 mb-4">
                                    <div class="card border">
                                        <div class="card-header">
                                            <h5 class="mb-0">{{ ucfirst($machineType) }} Machine</h5>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Configure {{ $machineType }} machine settings</p>
                                            <a href="{{ route('admin.whatsapp.machines.config', $machineType) }}" class="btn btn-primary">
                                                <i class="fas fa-cog"></i> Configure
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function testMachine(machineType) {
    if (confirm('Are you sure you want to test the ' + machineType + ' machine?')) {
        // Here you would make an AJAX call to test the machine
        console.log('Testing machine:', machineType);
        
        // For now, just show a success message
        alert('Machine test started! Check the console for results. (This is a demo)');
    }
}

$(document).ready(function() {
    // Add some interactive features
    $('.card').hover(
        function() {
            $(this).addClass('shadow-lg').removeClass('shadow-sm');
        },
        function() {
            $(this).addClass('shadow-sm').removeClass('shadow-lg');
        }
    );
});
</script>
@endsection
