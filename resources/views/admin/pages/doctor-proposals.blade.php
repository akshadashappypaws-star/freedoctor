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
    
    .proposal-message {
        max-width: 400px;
        word-wrap: break-word;
    }
    
    .modal-content {
        background: #1e293b !important;
        color: white !important;
        border: 1px solid #475569 !important;
    }
    
    .form-control {
        background: #334155 !important;
        color: white !important;
        border: 1px solid #475569 !important;
    }
    
    .form-control:focus {
        background: #334155 !important;
        color: white !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
    }
</style>

<div style="padding:15px" class="p-6 rounded shadow bg-slate-800 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Doctor Proposals</h1>
            <p class="text-gray-300 mt-2">Manage business proposals submitted by doctors</p>
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
                    <p class="text-blue-100 text-sm font-medium">Total Proposals</p>
                    <p class="text-3xl font-bold">{{ $proposals->total() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-paper-plane text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Pending</p>
                    <p class="text-3xl font-bold">{{ $proposals->where('status', 'pending')->count() }}</p>
                </div>
                <div class="bg-yellow-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Approved</p>
                    <p class="text-3xl font-bold">{{ $proposals->where('status', 'approved')->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Rejected</p>
                    <p class="text-3xl font-bold">{{ $proposals->where('status', 'rejected')->count() }}</p>
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
                <label class="block text-sm font-medium text-gray-300 mb-2">Proposal Status</label>
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

    <!-- Proposals Table -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table id="proposalsTable" class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Business Request</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Proposal Message</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Submitted Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-700 divide-y divide-slate-600">
                    @foreach($proposals as $proposal)
                    <tr class="hover:bg-slate-600 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                            #{{ $proposal->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($proposal->doctor->doctor_name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $proposal->doctor->doctor_name }}</div>
                                    <div class="text-sm text-gray-300">{{ $proposal->doctor->email }}</div>
                                    @if($proposal->doctor->specialty)
                                        <div class="text-xs text-blue-400">{{ $proposal->doctor->specialty->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($proposal->businessOrganizationRequest)
                                <div class="text-sm font-medium text-white">{{ $proposal->businessOrganizationRequest->organization_name }}</div>
                                <div class="text-sm text-gray-300">{{ $proposal->businessOrganizationRequest->camp_request_type }} - {{ $proposal->businessOrganizationRequest->location }}</div>
                                <div class="text-xs text-blue-400">{{ $proposal->businessOrganizationRequest->number_of_people }} people</div>
                            @else
                                <span class="text-slate-500 italic">General Proposal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="proposal-message text-sm text-white">
                                {{ Str::limit($proposal->message, 100) }}
                                @if(strlen($proposal->message) > 100)
                                    <button class="text-blue-400 hover:text-blue-300 ml-2" onclick="viewProposal({{ $proposal->id }})">
                                        Read More
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <div>{{ $proposal->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $proposal->created_at->format('H:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($proposal->status === 'approved') bg-green-100 text-green-800
                                @elseif($proposal->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($proposal->status === 'approved')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($proposal->status === 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ ucfirst($proposal->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="viewProposal({{ $proposal->id }})" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            @if($proposal->status === 'pending')
                                <button onclick="approveProposal({{ $proposal->id }})" 
                                        class="text-green-600 hover:text-green-900 transition-colors">
                                    <i class="fas fa-check"></i>
                                </button>
                                
                                <button onclick="rejectProposal({{ $proposal->id }})" 
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
            {{ $proposals->links() }}
        </div>
    </div>
</div>

<!-- View Proposal Modal -->
<div class="modal fade" id="viewProposalModal" tabindex="-1" aria-labelledby="viewProposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="viewProposalModalLabel">Proposal Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="proposalDetails" class="space-y-4 text-white">
                    <!-- Proposal details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve/Reject Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="actionModalLabel">Review Proposal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="actionForm">
                <div class="modal-body">
                    <input type="hidden" id="proposalId" name="proposal_id">
                    <input type="hidden" id="actionType" name="action">
                    
                    <div class="mb-3">
                        <label for="adminRemarks" class="form-label text-white">Admin Remarks</label>
                        <textarea class="form-control" id="adminRemarks" name="admin_remarks" rows="4" 
                                  placeholder="Add your remarks about this proposal..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="submitActionBtn">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap JS (backup) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
const proposals = @json($proposals->items());

$(document).ready(function() {
    // Initialize DataTable
    var table = $('#proposalsTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['excel', 'csv'],
        pageLength: 10,
        order: [[3, 'desc']], // Sort by submitted date
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

function viewProposal(proposalId) {
    const proposal = proposals.find(p => p.id === proposalId);
    if (proposal) {
        let businessRequestSection = '';
        if (proposal.business_organization_request) {
            const request = proposal.business_organization_request;
            businessRequestSection = `
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Business Request Details:</strong>
                    <div class="mt-2 p-3 bg-slate-600 rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Organization:</strong> ${request.organization_name}<br>
                                <strong>Type:</strong> ${request.camp_request_type}<br>
                                <strong>Location:</strong> ${request.location}
                            </div>
                            <div class="col-md-6">
                                <strong>Participants:</strong> ${request.number_of_people} people<br>
                                <strong>Duration:</strong> ${new Date(request.date_from).toLocaleDateString()} - ${new Date(request.date_to).toLocaleDateString()}<br>
                                <strong>Contact:</strong> ${request.email}
                            </div>
                        </div>
                        ${request.description ? `<div class="mt-2"><strong>Description:</strong><br>${request.description}</div>` : ''}
                    </div>
                </div>
            </div>
            `;
        } else {
            businessRequestSection = `
            <div class="row mt-3">
                <div class="col-12">
                    <strong>Business Request:</strong> <span class="text-muted font-italic">General Proposal (No specific business request)</span>
                </div>
            </div>
            `;
        }
        
        document.getElementById('proposalDetails').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>Doctor:</strong> ${proposal.doctor.doctor_name}
                </div>
                <div class="col-md-6">
                    <strong>Email:</strong> ${proposal.doctor.email}
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <strong>Specialty:</strong> ${proposal.doctor.specialty ? proposal.doctor.specialty.name : 'N/A'}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong> 
                    <span class="badge ${proposal.status === 'approved' ? 'bg-success' : proposal.status === 'pending' ? 'bg-warning' : 'bg-danger'}">
                        ${proposal.status.charAt(0).toUpperCase() + proposal.status.slice(1)}
                    </span>
                </div>
            </div>
            ${businessRequestSection}
            <div class="mt-3">
                <strong>Proposal Message:</strong>
                <div class="mt-2 p-3 bg-slate-600 rounded">${proposal.message}</div>
            </div>
            ${proposal.admin_remarks ? `
            <div class="mt-3">
                <strong>Admin Remarks:</strong>
                <div class="mt-2 p-3 bg-slate-600 rounded">${proposal.admin_remarks}</div>
            </div>
            ` : ''}
            <div class="mt-3">
                <strong>Submitted:</strong> ${new Date(proposal.created_at).toLocaleString()}
            </div>
        `;
        
        // Check if Bootstrap is available
        if (typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(document.getElementById('viewProposalModal')).show();
        } else {
            // Fallback using jQuery if Bootstrap is not available
            $('#viewProposalModal').modal('show');
        }
    }
}

function approveProposal(proposalId) {
    document.getElementById('proposalId').value = proposalId;
    document.getElementById('actionType').value = 'approve';
    document.getElementById('actionModalLabel').textContent = 'Approve Proposal';
    document.getElementById('submitActionBtn').textContent = 'Approve';
    document.getElementById('submitActionBtn').className = 'btn btn-success';
    
    // Check if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(document.getElementById('actionModal')).show();
    } else {
        // Fallback using jQuery if Bootstrap is not available
        $('#actionModal').modal('show');
    }
}

function rejectProposal(proposalId) {
    document.getElementById('proposalId').value = proposalId;
    document.getElementById('actionType').value = 'reject';
    document.getElementById('actionModalLabel').textContent = 'Reject Proposal';
    document.getElementById('submitActionBtn').textContent = 'Reject';
    document.getElementById('submitActionBtn').className = 'btn btn-danger';
    
    // Check if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(document.getElementById('actionModal')).show();
    } else {
        // Fallback using jQuery if Bootstrap is not available
        $('#actionModal').modal('show');
    }
}

// Handle form submission
document.getElementById('actionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const proposalId = formData.get('proposal_id');
    const action = formData.get('action');
    const adminRemarks = formData.get('admin_remarks');
    
    fetch(`/admin/doctor-proposals/${proposalId}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            admin_remarks: adminRemarks
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal properly
            if (typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getInstance(document.getElementById('actionModal')).hide();
            } else {
                $('#actionModal').modal('hide');
            }
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing the proposal.');
    });
});
</script>
@endsection
