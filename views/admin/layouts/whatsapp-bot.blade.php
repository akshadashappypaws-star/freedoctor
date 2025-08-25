@extends('admin.master')

@section('title', 'WhatsApp Bot')

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
<style>
.nav-tabs .nav-item .nav-link {
    color: #6B7280;
    padding: 0.75rem 1rem;
    border: none;
    border-bottom: 2px solid transparent;
}

.nav-tabs .nav-item .nav-link.active {
    color: #1F2937;
    border-bottom: 2px solid #3B82F6;
    background: none;
}

.nav-tabs .nav-item .nav-link:hover {
    border-color: #3B82F6;
    color: #1F2937;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">WhatsApp Bot Management</h1>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.dashboard') }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot/bulk-messages*') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.bulk-messages') }}">
                <i class="fas fa-paper-plane mr-1"></i> Bulk Messages
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot/auto-replies*') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.auto-replies') }}">
                <i class="fas fa-reply-all mr-1"></i> Auto Replies
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot/templates*') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.templates') }}">
                <i class="fas fa-file-alt mr-1"></i> Templates
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot/conversation-history*') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.conversation-history') }}">
                <i class="fas fa-history mr-1"></i> Conversation History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/whatsapp-bot/settings*') ? 'active' : '' }}" 
               href="{{ route('admin.whatsapp.settings') }}">
                <i class="fas fa-cog mr-1"></i> Settings
            </a>
        </li>
    </ul>

    <!-- Content Area -->
    <div class="tab-content">
        @yield('tab-content')
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize datepickers
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    // Handle tab navigation without page reload
    $('.nav-tabs .nav-link').on('click', function(e) {
        e.preventDefault();
        window.location.href = $(this).attr('href');
    });
});
</script>
@endsection
