@extends('../admin.dashboard')

@section('content')
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Patient Registrations</h1>
            <p class="text-gray-300 mt-2">Manage campaign patient registrations</p>
        </div>
        <div class="flex space-x-3">
            <button id="exportBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-download mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-filter mr-2"></i>Advanced Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Location</label>
                <select id="locationFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Locations</option>
                    @foreach($locations as $location)
                        <option value="{{ $location }}">{{ $location }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Campaign</label>
                <select id="campaignFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="yet_to_came">Yet to Come</option>
                    <option value="came">Came</option>
                    <option value="not_came">Not Came</option>
                </select>
            </div>
            <div class="flex items-end">
                <button id="applyFilters" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
            </div>
        </div>
        
        <!-- Results Count -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>
                    Showing <span id="recordCount" class="font-semibold">{{ is_countable($patientRegistrations) ? count($patientRegistrations) : $patientRegistrations->count() }}</span> records
                </span>
                <button id="clearFilters" class="text-sm text-blue-600 hover:text-blue-800 transition">
                    <i class="fas fa-times mr-1"></i>Clear All Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table id="patientsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Patient Details
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Contact
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i>Campaign
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-clipboard-check mr-2"></i>Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-clock mr-2"></i>Registered
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cogs mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="patientsTableBody">
                    @forelse($patientRegistrations as $patient)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 patient-row" 
                            data-location="{{ $patient->campaign->location ?? '' }}"
                            data-campaign="{{ $patient->campaign_id }}"
                            data-status="{{ $patient->status }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-lg">
                                            {{ strtoupper(substr($patient->name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $patient->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone text-blue-500 mr-2"></i>{{ $patient->phone_number }}
                            </div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>{{ $patient->address }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $patient->campaign->title ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $patient->campaign->location ?? 'N/A' }}
                            </div>
                            @if($patient->campaign)
                                <div class="text-xs text-blue-600">
                                    {{ \Carbon\Carbon::parse($patient->campaign->start_date)->format('M j') }} - 
                                    {{ \Carbon\Carbon::parse($patient->campaign->end_date)->format('M j, Y') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($patient->getStatusWithLogic()) {
                                    'yet_to_came' => 'bg-yellow-100 text-yellow-800',
                                    'came' => 'bg-green-100 text-green-800',
                                    'not_came' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusIcon = match($patient->getStatusWithLogic()) {
                                    'yet_to_came' => 'fas fa-clock',
                                    'came' => 'fas fa-check-circle',
                                    'not_came' => 'fas fa-times-circle',
                                    default => 'fas fa-question-circle'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                <i class="{{ $statusIcon }} mr-1"></i>
                                {{ $patient->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                {{ $patient->created_at->format('M j, Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $patient->created_at->format('g:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="view-patient-btn text-blue-600 hover:text-blue-900 transition" 
                                        data-id="{{ $patient->id }}" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <div class="relative">
                                    <select class="status-update-select text-xs border rounded px-2 py-1 bg-white" 
                                            data-id="{{ $patient->id }}" data-current="{{ $patient->status }}">
                                        <option value="yet_to_came" {{ $patient->status === 'yet_to_came' ? 'selected' : '' }}>Yet to Come</option>
                                        <option value="came" {{ $patient->status === 'came' ? 'selected' : '' }}>Came</option>
                                        <option value="not_came" {{ $patient->status === 'not_came' ? 'selected' : '' }}>Not Came</option>
                                    </select>
                                </div>
                                <button class="delete-patient-btn text-red-600 hover:text-red-900 transition" 
                                        data-id="{{ $patient->id }}" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Patient Registrations</h3>
                                    <p class="text-gray-500">No patient registrations found. Patients will appear here once they register for campaigns.</p>
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
<div id="patientDetailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl shadow-xl max-h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-circle mr-2 text-blue-500"></i>Patient Details
            </h2>
            <button id="closePatientModal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div id="patientDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Include DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    let table = $('#patientsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[4, 'desc']], // Sort by registration date desc
        columnDefs: [
            { orderable: false, targets: [5] } // Disable sorting for actions column
        ],
        language: {
            search: "Search patients:",
            lengthMenu: "Show _MENU_ patients per page",
            info: "Showing _START_ to _END_ of _TOTAL_ patients",
            infoEmpty: "No patients found",
            infoFiltered: "(filtered from _MAX_ total patients)"
        }
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-blue-800 mb-2">Personal Information</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Name:</span> ${patient.name}</p>
                                <p><span class="font-medium">Email:</span> ${patient.email}</p>
                                <p><span class="font-medium">Phone:</span> ${patient.phone_number}</p>
                                <p><span class="font-medium">Address:</span> ${patient.address}</p>
                            </div>
                        </div>
                        
                        ${patient.description ? `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-gray-800 mb-2">Additional Information</h3>
                            <p class="text-gray-700">${patient.description}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="space-y-4">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-800 mb-2">Campaign Details</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Campaign:</span> ${patient.campaign.title}</p>
                                <p><span class="font-medium">Location:</span> ${patient.campaign.location}</p>
                                <p><span class="font-medium">Date Range:</span> ${patient.campaign.start_date} to ${patient.campaign.end_date}</p>
                                <p><span class="font-medium">Doctor:</span> Dr. ${patient.campaign.doctor.doctor_name}</p>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-yellow-800 mb-2">Registration Info</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Status:</span> 
                                    <span class="px-2 py-1 rounded text-xs ${patient.status === 'came' ? 'bg-green-200 text-green-800' : patient.status === 'not_came' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800'}">
                                        ${patient.status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}
                                    </span>
                                </p>
                                <p><span class="font-medium">Registered:</span> ${new Date(patient.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#patientDetailContent').html(content);
            $('#patientDetailModal').removeClass('hidden');
        });
    });

    // Close Patient Modal
    $('#closePatientModal, #patientDetailModal').on('click', function(e) {
        if (e.target === this) {
            $('#patientDetailModal').addClass('hidden');
        }
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
