@extends('admin.master')

@section('title', 'Live Conversations')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Live Conversations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.whatsapp.dashboard') }}">WhatsApp</a></li>
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Active WhatsApp Conversations</h4>
                </div>
                <div class="card-body">
                    @if(isset($conversations) && $conversations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Contact</th>
                                        <th>Last Message</th>
                                        <th>Status</th>
                                        <th>Workflow</th>
                                        <th>Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conversations as $conversation)
                                        <tr>
                                            <td>{{ $conversation->whatsapp_number ?? 'Unknown' }}</td>
                                            <td>{{ $conversation->message_content ?? 'No messages' }}</td>
                                            <td>
                                                <span class="badge badge-success">Active</span>
                                            </td>
                                            <td>{{ $conversation->workflow->name ?? 'N/A' }}</td>
                                            <td>{{ $conversation->created_at ? $conversation->created_at->diffForHumans() : 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">View</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5>No active conversations</h5>
                            <p class="text-muted">WhatsApp conversations will appear here when users interact with your bot.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
