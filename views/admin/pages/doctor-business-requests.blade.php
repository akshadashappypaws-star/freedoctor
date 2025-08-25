@extends('../admin.dashboard')

@section('content')
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

<!-- Dark Theme Override for DataTables -->
<style>
    .dataTables_wrapper {
        color: #fff !important;
    }
    .dataTables_filter input {
        background: #374151 !important;
        color: #fff !important;
        border: 1px solid #4B5563 !important;
    }
    .dataTables_length select {
        background: #374151 !important;
        color: #fff !important;
        border: 1px solid #4B5563 !important;
    }
    .dataTables_info {
        color: #9CA3AF !important;
    }
</style>

<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Doctor Business Requests</h1>
            <p class="text-gray-300 mt-2">Manage doctor applications for business organization campaigns</p>
        </div>
        <div class="flex space-x-3">
            <button id="exportBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-download mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Applications</p>
                    <p class="text-3xl font-bold">{{ $requests->total() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-file-medical text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Approved</p>
                    <p class="text-3xl font-bold">{{ $requests->where('status', 'approved')->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold">{{ $requests->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Rejected</p>
                    <p class="text-3xl font-bold">{{ $requests->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-filter mr-2"></i>Advanced Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Application Status</label>
                <select id="statusFilter" class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Search Doctor</label>
                <input type="text" id="searchInput" placeholder="Search by doctor name..." 
                       class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button id="resetFilters" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table id="requestsTable" class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Campaign Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Application Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-700 divide-y divide-slate-600">
                    @foreach($requests as $request)
                    <tr class="hover:bg-slate-600 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($request->doctor->doctor_name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $request->doctor->doctor_name }}</div>
                                    <div class="text-sm text-gray-300">{{ $request->doctor->email }}</div>
                                    @if($request->doctor->specialty)
                                        <div class="text-xs text-blue-400">{{ $request->doctor->specialty->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">{{ $request->businessOrganizationRequest->organization_name }}</div>
                            <div class="text-sm text-gray-300">{{ $request->businessOrganizationRequest->location }}</div>
                            <div class="text-xs text-gray-400">{{ $request->businessOrganizationRequest->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">{{ ucfirst($request->businessOrganizationRequest->camp_request_type) }} Camp</div>
                            <div class="text-sm text-gray-300">{{ $request->businessOrganizationRequest->specialty->name }}</div>
                            <div class="text-xs text-blue-400">{{ $request->businessOrganizationRequest->number_of_people }} people</div>
                            <div class="text-xs text-gray-400">{{ $request->businessOrganizationRequest->date_from->format('M d') }} - {{ $request->businessOrganizationRequest->date_to->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <div>{{ $request->applied_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $request->applied_at->format('H:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($request->status === 'approved') bg-green-100 text-green-800
                                @elseif($request->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($request->status === 'approved')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($request->status === 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="viewApplication({{ $request->id }})" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($request->status === 'pending')
                                <button onclick="approveApplication({{ $request->id }})" 
                                        class="text-green-600 hover:text-green-900 transition-colors">
                                    <i class="fas fa-check"></i>
                                </button>
                                
                                <button onclick="rejectApplication({{ $request->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-800 px-4 py-3 border-t border-slate-600 sm:px-6">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<!-- View Application Modal -->
<div id="viewApplicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-slate-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-white">Application Details</h3>
                <button onclick="closeModal('viewApplicationModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="applicationDetails" class="space-y-4 text-white">
                <!-- Application details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#requestsTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['excel', 'csv'],
        pageLength: 10,
        order: [[3, 'desc']], // Sort by application date
        columnDefs: [
            { orderable: false, targets: [5] } // Disable ordering on actions column
        ]
    });

    // Filter by status
    $('#statusFilter').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#statusFilter').val('');
        $('#searchInput').val('');
        table.search('').columns().search('').draw();
    });

    // Export button
    $('#exportBtn').on('click', function() {
        table.button('.buttons-excel').trigger();
    });
});

function viewApplication(requestId) {
    // Show loading
    document.getElementById('applicationDetails').innerHTML = '<div class="text-center py-4 text-white"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    document.getElementById('viewApplicationModal').classList.remove('hidden');
    
    // In a real implementation, you would fetch application details via AJAX
    setTimeout(() => {
        document.getElementById('applicationDetails').innerHTML = `
            <div class="space-y-3 text-white">
                <div class="flex justify-between"><span class="font-medium text-white">Application ID:</span><span class="text-gray-300">APP_${requestId}</span></div>
                <div class="flex justify-between"><span class="font-medium text-white">Doctor:</span><span class="text-gray-300">Dr. John Doe</span></div>
                <div class="flex justify-between"><span class="font-medium text-white">Specialty:</span><span class="text-gray-300">Cardiology</span></div>
                <div class="flex justify-between"><span class="font-medium text-white">Experience:</span><span class="text-gray-300">5 years</span></div>
                <div class="flex justify-between"><span class="font-medium text-white">Status:</span><span class="text-yellow-400">Pending</span></div>
            </div>
        `;
    }, 500);
}

function approveApplication(requestId) {
    if (confirm('Are you sure you want to approve this doctor application?')) {
        fetch(`/admin/doctor-business-requests/${requestId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while approving the application.');
        });
    }
}

function rejectApplication(requestId) {
    if (confirm('Are you sure you want to reject this doctor application?')) {
        // Implementation for reject application
        alert('Reject functionality would be implemented here');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
