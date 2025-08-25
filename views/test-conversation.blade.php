<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Conversation - {{ $whatsappNumber }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">
                    <i class="fab fa-whatsapp text-success me-2"></i>
                    Conversation: {{ $whatsappNumber }}
                </h2>
                
                <div class="row">
                    <!-- Messages Column -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-comments me-2"></i>
                                    Messages ({{ $stats['total_messages'] ?? 0 }})
                                </h5>
                            </div>
                            <div class="card-body" style="height: 600px; overflow-y: auto;">
                                <div class="messages-container" id="messagesContainer">
                                    @forelse($messages as $message)
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="card bg-light">
                                                        <div class="card-body py-2">
                                                            <p class="mb-1">{{ $message->message ?? 'No message content' }}</p>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ $message->created_at->format('M d, Y h:i A') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($message->reply)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start justify-content-end">
                                                    <div class="flex-grow-1 text-end me-3">
                                                        <div class="card bg-primary text-white d-inline-block" style="max-width: 80%;">
                                                            <div class="card-body py-2">
                                                                <p class="mb-1">{{ $message->reply }}</p>
                                                                <small class="text-light">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    {{ $message->updated_at->format('M d, Y h:i A') }}
                                                                    @if($message->reply_type)
                                                                        <span class="badge bg-light text-primary ms-2">
                                                                            {{ ucfirst($message->reply_type) }}
                                                                        </span>
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-robot"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-5">
                                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No messages yet in this conversation</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Column -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>
                                    Conversation Stats
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-primary">{{ $stats['total_messages'] ?? 0 }}</h4>
                                        <small class="text-muted">Total Messages</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success">{{ $stats['replied_messages'] ?? 0 }}</h4>
                                        <small class="text-muted">Replied</small>
                                    </div>
                                </div>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <h4 class="text-warning">{{ $stats['pending_messages'] ?? 0 }}</h4>
                                        <small class="text-muted">Pending</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-info">{{ $stats['lead_status'] ?? 'New' }}</h4>
                                        <small class="text-muted">Lead Status</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>Debug Information:</h6>
                            <ul class="list-group">
                                <li class="list-group-item">Phone: {{ $whatsappNumber }}</li>
                                <li class="list-group-item">Total Messages: {{ $messages->count() }}</li>
                                <li class="list-group-item">Messages with content: {{ $messages->whereNotNull('message')->count() }}</li>
                                <li class="list-group-item">Messages with replies: {{ $messages->whereNotNull('reply')->count() }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
