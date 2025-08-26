@extends('doctor.master')

@section('title', 'My Patients - FreeDoctor')

@section('content')
<!-- Include DataTables CSS - Bootstrap 4 for consistency -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

<!-- Modern Patients Dashboard -->
<div class="min-h-screen space-y-8">
    @if(session('error'))
        <div class="alert-error animate-fade-in">
            <div class="d-flex align-items-center">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <span class="fw-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header-card animate-fade-in">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">
            <div class="d-flex align-items-center gap-4">
                <div class="page-header-icon">
                    <i class="fas fa-users text-white" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h1 class="page-title gradient-text">
                        My Patient Registrations
                    </h1>
                    <p class="page-subtitle">Patients registered for your medical camps</p>
                    <p class="page-meta">
                        Total patients: <span class="highlight">{{ $patientRegistrations->count() }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="stats-grid animate-fade-in">
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="stats-label-modern">Total Registrations</p>
                    <p class="stats-value-modern">{{ $patientRegistrations->count() }}</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="stats-label-modern">Active Campaigns</p>
                    <p class="stats-value-modern">{{ $patientRegistrations->pluck('campaign_id')->unique()->count() }}</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="stats-label-modern">This Month</p>
                    <p class="stats-value-modern">{{ $patientRegistrations->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card-modern">
            <div class="stats-content">
                <div class="stats-text">
                    <p class="small text-slate-300">Gross: ₹{{ number_format(\App\Models\PatientPayment::sum('amount')) }}</p>
                    <p class="stats-value-modern">Net: ₹{{ number_format($finalAmount) }}</p>
                </div>
                <div class="stats-icon-modern">
                    <i class="fas fa-rupee-sign"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="filter-section animate-fade-in">
        <div class="filter-header">
            <div class="filter-icon">
                <i class="fas fa-filter"></i>
            </div>
            <h3 class="filter-title">Advanced Filters</h3>
        </div>
        <div class="filter-grid">
            <div class="filter-group">
                <label class="filter-label">Filter by Location</label>
                <select id="locationFilter" class="filter-select">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Filter by Campaign</label>
                <select id="campaignFilter" class="filter-select">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Filter by Status</label>
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="yet_to_came">Yet to Come</option>
                    <option value="came">Came</option>
                    <option value="not_came">Not Came</option>
                </select>
            </div>
            <div class="d-flex align-items-end">
                <button id="applyFilters" class="filter-button w-100">
                    <i class="fas fa-search"></i>Apply Filters
                </button>
            </div>
        </div>
        
        <!-- Results Count -->
        <div class="filter-results">
            <div class="filter-results-content">
                <div class="filter-results-info">
                    <div class="filter-results-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <span class="filter-results-text">
                        Showing <span id="recordCount" class="filter-results-count">{{ is_countable($patientRegistrations) ? count($patientRegistrations) : $patientRegistrations->count() }}</span> records
                    </span>
                </div>
                <button id="clearFilters" class="filter-clear">
                    <i class="fas fa-times me-1"></i>Clear All Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="table-container">
        <div class="table-responsive">
            <table id="patientsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <i class="fas fa-user me-2"></i>Patient Details
                        </th>
                        <th>
                            <i class="fas fa-phone me-2"></i>Contact
                        </th>
                        <th>
                            <i class="fas fa-calendar-alt me-2"></i>Campaign
                        </th>
                        <th>
                            <i class="fas fa-clock me-2"></i>Registered
                        </th>
                    </tr>
                </thead>
                <tbody id="patientsTableBody">
                    @forelse($patientRegistrations as $patient)
                        <tr class="patient-row" 
                            data-location="{{ $patient->campaign->location ?? '' }}"
                            data-campaign="{{ $patient->campaign_id }}"
                            data-status="{{ $patient->status }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 48px; height: 48px; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                                            <span class="text-white fw-bold">
                                                {{ strtoupper(substr($patient->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $patient->name }}</div>
                                        <div class="text-slate-300 small">{{ $patient->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <i class="fas fa-phone me-2" style="color: var(--primary-color);"></i>{{ $patient->phone_number }}
                                </div>
                                <div class="text-slate-300 small">
                                    <i class="fas fa-map-marker-alt me-2" style="color: var(--danger-color);"></i>{{ Str::limit($patient->address, 30) }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $patient->campaign->title ?? 'N/A' }}</div>
                                <div class="text-slate-300 small">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $patient->campaign->location ?? 'N/A' }}
                                </div>
                                @if($patient->campaign)
                                    <div class="small" style="color: var(--primary-color);">
                                        {{ \Carbon\Carbon::parse($patient->campaign->start_date)->format('M j') }} - 
                                        {{ \Carbon\Carbon::parse($patient->campaign->end_date)->format('M j, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar me-2" style="color: var(--success-color);"></i>
                                    <span>{{ $patient->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="text-slate-300 small">
                                    {{ $patient->created_at->format('g:i A') }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="mb-3">
                                        <i class="fas fa-users text-slate-300" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="mb-2">No Patient Registrations</h5>
                                    <p class="text-slate-300">Patient registrations will appear here once available.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Patient Detail Modal -->
<div id="patientDetailModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-professional">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-theme d-flex align-items-center justify-content-center bg-primary-theme" style="width: 3rem; height: 3rem;">
                        <i class="fas fa-user-circle text-white"></i>
                    </div>
                    <h2 class="modal-title">Patient Details</h2>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="patientDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables JS - Bootstrap 4 for consistency -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#patientsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[3, 'desc']], // Sort by registration date desc
        columnDefs: [
            { 
                targets: '_all', // Apply to all columns
                orderable: true
            }
        ],
        language: {
            search: "Search patients:",
            lengthMenu: "Show _MENU_ patients per page",
            info: "Showing _START_ to _END_ of _TOTAL_ patients",
            infoEmpty: "No patients found",
            infoFiltered: "(filtered from _MAX_ total patients)"
        },
        autoWidth: false,
        processing: false, // Disable processing indicator
        serverSide: false, // Client-side processing
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthChange: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>B',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-primary btn-sm me-1'
            },
            {
                extend: 'excel', 
                className: 'btn btn-success btn-sm me-1'
            },
            {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm'
            }
        ]
    });

    // Apply Filters
    $('#applyFilters').on('click', function() {
        const location = $('#locationFilter').val();
        const campaign = $('#campaignFilter').val();
        const status = $('#statusFilter').val();
        
        // Show/hide rows based on filters
        $('.patient-row').each(function() {
            const row = $(this);
            const rowLocation = row.data('location');
            const rowCampaign = row.data('campaign').toString();
            const rowStatus = row.data('status');
            
            let show = true;
            
            if (location && rowLocation !== location) show = false;
            if (campaign && rowCampaign !== campaign) show = false;
            if (status && rowStatus !== status) show = false;
            
            if (show) {
                row.show();
            } else {
                row.hide();
            }
        });
        
        // Update record count
        const visibleRows = $('.patient-row:visible').length;
        $('#recordCount').text(visibleRows);
        
        // Redraw DataTable
        table.draw();
    });

    // Clear Filters
    $('#clearFilters').on('click', function() {
        $('#locationFilter, #campaignFilter, #statusFilter').val('');
        $('.patient-row').show();
        $('#recordCount').text($('.patient-row').length);
        table.draw();
    });

    // Export functionality
    $('#exportBtn').on('click', function() {
        const location = $('#locationFilter').val();
        const campaign = $('#campaignFilter').val();
        const status = $('#statusFilter').val();
        
        let url = '/admin/patient-registrations/export?';
        const params = [];
        
        if (location) params.push(`location=${encodeURIComponent(location)}`);
        if (campaign) params.push(`campaign_id=${campaign}`);
        if (status) params.push(`status=${status}`);
        
        url += params.join('&');
        
        window.location.href = url;
    });

    // View Patient Details
    $(document).on('click', '.view-patient-btn', function() {
        const patientId = $(this).data('id');
        
        $.get(`/admin/patient-registrations/${patientId}`, function(patient) {
            const content = `
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="bg-surface-theme border-theme rounded-theme p-3">
                            <h5 class="text-primary-theme mb-3">Personal Information</h5>
                            <div class="space-y-2">
                                <p><span class="fw-medium">Name:</span> ${patient.name}</p>
                                <p><span class="fw-medium">Email:</span> ${patient.email}</p>
                                <p><span class="fw-medium">Phone:</span> ${patient.phone_number}</p>
                                <p><span class="fw-medium">Address:</span> ${patient.address}</p>
                            </div>
                        </div>
                        
                        ${patient.description ? `
                        <div class="bg-surface-theme border-theme rounded-theme p-3 mt-3">
                            <h5 class="text-secondary-theme mb-3">Additional Information</h5>
                            <p class="text-slate-300">${patient.description}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="col-md-6">
                        <div class="bg-surface-theme border-theme rounded-theme p-3">
                            <h5 class="text-success-theme mb-3">Campaign Details</h5>
                            <div class="space-y-2">
                                <p><span class="fw-medium">Campaign:</span> ${patient.campaign.title}</p>
                                <p><span class="fw-medium">Location:</span> ${patient.campaign.location}</p>
                                <p><span class="fw-medium">Date Range:</span> ${patient.campaign.start_date} to ${patient.campaign.end_date}</p>
                                <p><span class="fw-medium">Doctor:</span> Dr. ${patient.campaign.doctor.doctor_name}</p>
                            </div>
                        </div>
                        
                        <div class="bg-surface-theme border-theme rounded-theme p-3 mt-3">
                            <h5 class="text-secondary-theme mb-3">Registration Info</h5>
                            <div class="space-y-2">
                                <p><span class="fw-medium">Status:</span> 
                                    <span class="badge ${patient.status === 'came' ? 'badge-success' : patient.status === 'not_came' ? 'badge-danger' : 'badge-warning'}">
                                        ${patient.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                    </span>
                                </p>
                                <p><span class="fw-medium">Registered:</span> ${new Date(patient.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#patientDetailContent').html(content);
            const modal = new bootstrap.Modal(document.getElementById('patientDetailModal'));
            modal.show();
        });
    });

    // Update Patient Status
    $(document).on('change', '.status-update-select', function() {
        const patientId = $(this).data('id');
        const newStatus = $(this).val();
        const currentStatus = $(this).data('current');
        
        if (newStatus === currentStatus) return;
        
        $.ajax({
            url: `/admin/patient-registrations/${patientId}`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { status: newStatus },
            success: function(response) {
                if (response.success) {
                    location.reload(); // Reload to update status display
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                Swal.fire('Error!', 'Failed to update status.', 'error');
            }
        });
    });

    // Delete Patient Registration
    $(document).on('click', '.delete-patient-btn', function() {
        const patientId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "This patient registration will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/patient-registrations/${patientId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete registration.', 'error');
                    }
                });
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
