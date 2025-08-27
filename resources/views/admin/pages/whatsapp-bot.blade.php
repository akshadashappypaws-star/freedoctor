@extends('admin.master')


@section('title', 'WhatsApp Bot Management')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2 class="mb-0">WhatsApp Bot Management</h2>
    </div>
    
    <!-- Status Overview -->
    <div class="row mb-4">
        <!-- Messages Today -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Messages Today</h6>
                            <h2 class="mb-0">{{ $stats->messages_today ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-paper-plane fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Rate -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Success Rate</h6>
                            <h2 class="mb-0">{{ $stats->success_rate ?? '0%' }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Templates -->
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Active Templates</h6>
                            <h2 class="mb-0">{{ $stats->active_templates ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-file-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Status -->
        <div class="col-xl-3 col-md-6">
            <div class="card {{ $stats->api_status ? 'bg-success' : 'bg-danger' }} text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">API Status</h6>
                            <h2 class="mb-0">{{ $stats->api_status ? 'Connected' : 'Disconnected' }}</h2>
                        </div>
                        <i class="fas fa-plug fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-4" id="whatsappTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="bulk-message-tab" data-bs-toggle="tab" href="#bulk-message" role="tab">
                <i class="fas fa-paper-plane"></i> Bulk Messages
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="templates-tab" data-bs-toggle="tab" href="#templates" role="tab">
                <i class="fas fa-file-alt"></i> Templates
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="flow-data-tab" data-bs-toggle="tab" href="#flow-data" role="tab">
                <i class="fas fa-project-diagram"></i> Flow Data
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="auto-reply-tab" data-bs-toggle="tab" href="#auto-reply" role="tab">
                <i class="fas fa-robot"></i> Auto Reply
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="settings-tab" data-bs-toggle="tab" href="#settings" role="tab">
                <i class="fas fa-cog"></i> Settings
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="whatsappTabsContent">
        <!-- Bulk Message Tab -->
        <div class="tab-pane fade show active" id="bulk-message" role="tabpanel">
            @include('admin.pages.partials.whatsapp-bot-bulk-message')
        </div>

        <!-- Templates Tab -->
        <div class="tab-pane fade" id="templates" role="tabpanel">
            @include('admin.pages.partials.whatsapp-bot-templates')
        </div>

        <!-- Flow Data Tab -->
        <div class="tab-pane fade" id="flow-data" role="tabpanel">
            @include('admin.pages.partials.whatsapp-bot-flow-data')
        </div>

        <!-- Auto Reply Tab -->
        <div class="tab-pane fade" id="auto-reply" role="tabpanel">
            @include('admin.pages.partials.whatsapp-bot-auto-reply')
        </div>

        <!-- Settings Tab -->
        <div class="tab-pane fade" id="settings" role="tabpanel">
            @include('admin.pages.partials.whatsapp-bot-settings')
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    border: none;
    box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
}
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tab-content {
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 20px;
}
.nav-tabs .nav-link {
    color: #495057;
}
.nav-tabs .nav-link.active {
    color: #0d6efd;
    font-weight: 500;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Preserve active tab on page reload
    var hash = window.location.hash;
    if (hash) {
        $('#whatsappTabs a[href="' + hash + '"]').tab('show');
    }

    // Update URL hash when tab changes
    $('#whatsappTabs a').on('click', function (e) {
        $(this).tab('show');
        window.location.hash = $(this).attr('href');
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
