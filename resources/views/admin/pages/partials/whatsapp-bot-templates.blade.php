<!-- Templates Management -->
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Message Templates</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newTemplateModal">
            <i class="fas fa-plus"></i> New Template
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Message</th>
                        <th>Variables</th>
                        <th>Last Used</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates ?? [] as $template)
                    <tr>
                        <td>{{ $template->name }}</td>
                        <td>{{ Str::limit($template->message, 50) }}</td>
                        <td>
                            @foreach($template->variables as $var)
                                <span class="badge bg-info">{{ $var }}</span>
                            @endforeach
                        </td>
                        <td>{{ $template->last_used_at ? $template->last_used_at->diffForHumans() : 'Never' }}</td>
                        <td>
                            <span class="badge {{ $template->approved ? 'bg-success' : 'bg-warning' }}">
                                {{ $template->approved ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info edit-template" data-id="{{ $template->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-template" data-id="{{ $template->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No templates found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Template Modal -->
<div class="modal fade" id="newTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newTemplateForm" action="{{ route('admin.whatsapp.templates') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="templateName">Template Name</label>
                        <input type="text" class="form-control" id="templateName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="templateMessage">Message</label>
                        <textarea class="form-control" id="templateMessage" name="message" rows="4" required></textarea>
                        <small class="text-muted">Available variables: {doctor_name}, {specialty}, {clinic_name}</small>
                    </div>
                    <div class="mb-3">
                        <label>Header (Optional)</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="header_type" id="headerNone" value="none" checked>
                            <label class="form-check-label" for="headerNone">
                                No Header
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="header_type" id="headerText" value="text">
                            <label class="form-check-label" for="headerText">
                                Text Header
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="header_type" id="headerImage" value="image">
                            <label class="form-check-label" for="headerImage">
                                Image Header
                            </label>
                        </div>
                    </div>
                    <div id="headerContent" class="mb-3" style="display: none;">
                        <label for="headerValue">Header Content</label>
                        <input type="text" class="form-control" id="headerValue" name="header_value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Template</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Template Modal -->
<div class="modal fade" id="editTemplateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Similar structure to new template modal -->
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle header content input based on header type selection
    $('input[name="header_type"]').change(function() {
        if (this.value === 'none') {
            $('#headerContent').slideUp();
        } else {
            $('#headerContent').slideDown();
            if (this.value === 'image') {
                $('#headerValue').attr('type', 'file').attr('accept', 'image/*');
            } else {
                $('#headerValue').attr('type', 'text').removeAttr('accept');
            }
        }
    });

    // Handle template deletion
    $('.delete-template').click(function() {
        const templateId = $(this).data('id');
        
        Swal.fire({
            title: 'Delete Template?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/admin/whatsapp/templates/${templateId}`, {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                })
                .done(function() {
                    Swal.fire('Deleted!', 'Template has been deleted.', 'success')
                    .then(() => location.reload());
                })
                .fail(function() {
                    Swal.fire('Error!', 'Failed to delete template.', 'error');
                });
            }
        });
    });

    // Handle template edit
    $('.edit-template').click(function() {
        const templateId = $(this).data('id');
        // Add your code to load and show template data in edit modal
    });
});
</script>
@endpush
