@extends('../admin.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-bell mr-2"></i>Admin Notifications
                        </h4>
                        <div>
                            @if($notifications->where('read', false)->count() > 0)
                                <button id="markAllReadBtn" class="btn btn-light btn-sm">
                                    <i class="fas fa-check-double mr-1"></i>Mark All as Read
                                </button>
                            @endif
                            <button class="btn btn-light btn-sm" onclick="location.reload()">
                                <i class="fas fa-sync-alt mr-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ !$notification->read ? 'bg-light border-left-primary' : '' }}" 
                                     data-notification-id="{{ $notification->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar-sm">
                                                @switch($notification->type)
                                                    @case('proposal')
                                                        <div class="avatar-title bg-warning text-white rounded-circle">
                                                            <i class="fas fa-file-alt"></i>
                                                        </div>
                                                    @break
                                                    @case('approval')
                                                        <div class="avatar-title bg-success text-white rounded-circle">
                                                            <i class="fas fa-check-circle"></i>
                                                        </div>
                                                    @break
                                                    @case('rejection')
                                                        <div class="avatar-title bg-danger text-white rounded-circle">
                                                            <i class="fas fa-times-circle"></i>
                                                        </div>
                                                    @break
                                                    @default
                                                        <div class="avatar-title bg-info text-white rounded-circle">
                                                            <i class="fas fa-bell"></i>
                                                        </div>
                                                @endswitch
                                            </div>
                                        </div>
                                        
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1 {{ !$notification->read ? 'font-weight-bold' : '' }}">
                                                        {{ ucfirst($notification->type) }} Notification
                                                        @if(!$notification->read)
                                                            <span class="badge badge-danger badge-pill ml-2">New</span>
                                                        @endif
                                                    </h6>
                                                    <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                                    
                                                    @if($notification->data)
                                                        <div class="mt-2">
                                                            @if(isset($notification->data['doctor_name']))
                                                                <small class="text-info">
                                                                    <i class="fas fa-user-md mr-1"></i>
                                                                    Doctor: {{ $notification->data['doctor_name'] }}
                                                                    @if(isset($notification->data['doctor_email']))
                                                                        ({{ $notification->data['doctor_email'] }})
                                                                    @endif
                                                                </small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <div class="text-right">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                    <div class="mt-2">
                                                        @if(!$notification->read)
                                                            <button class="btn btn-sm btn-outline-primary mark-read-btn" 
                                                                    data-id="{{ $notification->id }}">
                                                                <i class="fas fa-check mr-1"></i>Mark as Read
                                                            </button>
                                                        @endif
                                                        
                                                        @if($notification->type === 'proposal' && isset($notification->data['proposal_id']))
                                                            <a href="{{ route('admin.doctor-proposals.index') }}" 
                                                               class="btn btn-sm btn-primary ml-1">
                                                                <i class="fas fa-eye mr-1"></i>View Proposal
                                                            </a>
                                                        @endif
                                                        
                                                        <button class="btn btn-sm btn-outline-danger delete-notification-btn ml-1" 
                                                                data-id="{{ $notification->id }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if($notifications->hasPages())
                            <div class="card-footer">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bell-slash text-muted" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-muted">You'll see system notifications and alerts here when they arrive.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    height: 2.5rem;
    width: 2.5rem;
}

.avatar-title {
    align-items: center;
    display: flex;
    font-size: 1rem;
    font-weight: 600;
    height: 100%;
    justify-content: center;
    width: 100%;
}

.border-left-primary {
    border-left: 0.25rem solid #007bff !important;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>

@push('scripts')
<script>
$(document).ready(function() {
    // Mark single notification as read
    $('.mark-read-btn').on('click', function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        const notificationItem = button.closest('[data-notification-id]');
        
        button.prop('disabled', true);
        
        $.ajax({
            url: `/admin/notifications/${notificationId}/mark-read`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    // Remove the "New" badge and background highlight
                    notificationItem.removeClass('bg-light border-left-primary');
                    notificationItem.find('.badge-danger').remove();
                    notificationItem.find('.font-weight-bold').removeClass('font-weight-bold');
                    button.remove();
                    
                    // Show success message
                    showToast('Notification marked as read', 'success');
                }
            },
            error: function() {
                button.prop('disabled', false);
                showToast('Error marking notification as read', 'error');
            }
        });
    });

    // Mark all as read
    $('#markAllReadBtn').on('click', function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("admin.notifications.mark-all-read") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.success) {
                    // Remove all "New" badges and background highlights
                    $('.bg-light.border-left-primary').removeClass('bg-light border-left-primary');
                    $('.badge-danger').remove();
                    $('.font-weight-bold').removeClass('font-weight-bold');
                    $('.mark-read-btn').remove();
                    button.remove();
                    
                    showToast('All notifications marked as read', 'success');
                }
            },
            error: function() {
                button.prop('disabled', false);
                showToast('Error marking notifications as read', 'error');
            }
        });
    });

    // Delete notification
    $('.delete-notification-btn').on('click', function() {
        const notificationId = $(this).data('id');
        const notificationItem = $(this).closest('[data-notification-id]');
        
        if(confirm('Are you sure you want to delete this notification?')) {
            $.ajax({
                url: `/admin/notifications/${notificationId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.success) {
                        notificationItem.fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if no notifications left
                            if($('[data-notification-id]').length === 0) {
                                location.reload();
                            }
                        });
                        
                        showToast('Notification deleted successfully', 'success');
                    }
                },
                error: function() {
                    showToast('Error deleting notification', 'error');
                }
            });
        }
    });
});

// Helper function to show toast notifications
function showToast(message, type = 'info') {
    const bgColor = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
    
    const toast = $(`
        <div class="admin-toast-notification position-fixed ${bgColor} text-white px-4 py-3 rounded shadow-lg" 
             style="top: 20px; right: 20px; z-index: 9999; transform: translateX(100%); min-width: 300px;">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `);
    
    $('body').append(toast);
    
    setTimeout(() => toast.css('transform', 'translateX(0)'), 100);
    setTimeout(() => {
        toast.css('transform', 'translateX(100%)');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Auto-refresh every 60 seconds
setInterval(function() {
    // Simple check for new notifications without full page reload
    $.get('{{ route("admin.notifications.count") }}', function(data) {
        if(data.unread_count > $('.badge-danger').length) {
            location.reload();
        }
    });
}, 60000);
</script>
@endpush
@endsection
