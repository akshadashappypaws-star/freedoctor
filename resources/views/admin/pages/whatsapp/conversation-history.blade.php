@extends('admin.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">WhatsApp Conversation History</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.whatsapp.conversation-history') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ request('phone') }}" placeholder="Search by phone number">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from">From Date</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to">To Date</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="response_type">Response Type</label>
                                    <select class="form-control" id="response_type" name="response_type">
                                        <option value="">All Types</option>
                                        <option value="template" {{ request('response_type') == 'template' ? 'selected' : '' }}>Template</option>
                                        <option value="chatgpt" {{ request('response_type') == 'chatgpt' ? 'selected' : '' }}>ChatGPT</option>
                                        <option value="auto_reply" {{ request('response_type') == 'auto_reply' ? 'selected' : '' }}>Auto Reply</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.whatsapp.conversation-history') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Conversations Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th>Message</th>
                                    <th>Response Type</th>
                                    <th>Response</th>
                                    <th>Date/Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversations as $conversation)
                                <tr>
                                    <td>{{ $conversation->phone }}</td>
                                    <td>{{ $conversation->message }}</td>
                                    <td>
                                        @if($conversation->response_type)
                                            <span class="badge badge-{{ $conversation->response_type == 'template' ? 'primary' : ($conversation->response_type == 'chatgpt' ? 'success' : 'info') }}">
                                                {{ ucfirst($conversation->response_type) }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">None</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($conversation->template)
                                            Template: {{ $conversation->template->name }}
                                        @elseif($conversation->autoReply)
                                            Auto Reply: {{ $conversation->autoReply->name }}
                                        @elseif($conversation->chatgptPrompt)
                                            ChatGPT: {{ Str::limit($conversation->response_message, 50) }}
                                        @else
                                            {{ Str::limit($conversation->response_message ?? 'No response', 50) }}
                                        @endif
                                    </td>
                                    <td>{{ $conversation->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @if($conversation->is_responded)
                                            <span class="badge badge-success">Responded</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No conversations found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $conversations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Initialize any date pickers or other plugins if needed
        $('#date_from, #date_to').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });
</script>
@endpush
