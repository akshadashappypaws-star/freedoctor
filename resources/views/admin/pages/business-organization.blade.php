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

<div style="padding:15px" class="min-h-screen p-6 rounded shadow bg-slate-800 border border-slate-700">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Business Organization Requests</h1>
            <p class="text-gray-300 mt-2">Manage business organization campaign requests</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="openAddModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>Add New Request
            </button>
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
                    <p class="text-blue-100 text-sm font-medium">Total Requests</p>
                    <p class="text-3xl font-bold">{{ $requests->total() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Doctor Hired</p>
                    <p class="text-3xl font-bold">{{ $requests->where('status', 'doctor_hired')->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-user-check text-2xl"></i>
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

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold">{{ $requests->where('status', 'completed')->count() }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table id="requestsTable" class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Organization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Campaign Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Hired Doctor</th>
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
                                        <span class="text-white font-semibold text-sm">{{ substr($request->organization_name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $request->organization_name }}</div>
                                    <div class="text-sm text-gray-300">{{ $request->location }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white">{{ $request->email }}</div>
                            <div class="text-sm text-gray-300">{{ $request->phone_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">{{ ucfirst($request->camp_request_type) }} Camp</div>
                            <div class="text-sm text-gray-300">{{ $request->specialty->name }}</div>
                            <div class="text-xs text-blue-400">{{ $request->number_of_people }} people</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            <div>{{ $request->date_from->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">to {{ $request->date_to->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->hiredDoctor)
                                <div class="text-sm font-medium text-green-400">{{ $request->hiredDoctor->doctor_name }}</div>
                                <div class="text-xs text-gray-400">{{ $request->hiredDoctor->specialty->name ?? 'N/A' }}</div>
                            @else
                                <span class="text-gray-500 text-sm">Not yet hired</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($request->status === 'completed') bg-green-100 text-green-800
                                @elseif($request->status === 'doctor_hired') bg-blue-100 text-blue-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if($request->status === 'completed')
                                    <i class="fas fa-check-double mr-1"></i>
                                @elseif($request->status === 'doctor_hired')
                                    <i class="fas fa-user-check mr-1"></i>
                                @else
                                    <i class="fas fa-clock mr-1"></i>
                                @endif
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="viewRequest({{ $request->id }})" 
                                    class="text-blue-600 hover:text-blue-900 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <button onclick="editRequest({{ $request->id }})" 
                                    class="text-green-600 hover:text-green-900 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <button onclick="deleteRequest({{ $request->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
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

<!-- Add/Edit Request Modal -->
<div id="requestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-slate-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-white" id="modalTitle">Add Business Organization Request</h3>
                <button onclick="closeModal('requestModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="requestForm" method="POST" action="{{ route('admin.business-organization.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Organization Name</label>
                        <input type="text" name="organization_name" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Camp Request Type</label>
                        <select name="camp_request_type" required 
                                class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Type</option>
                            <option value="medical">Medical</option>
                            <option value="surgical">Surgical</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Specialization</label>
                        <select name="specialty_id" required 
                                class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select Specialization</option>
                            @php
                                $specialties = \App\Models\Specialty::all();
                            @endphp
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Number of People</label>
                        <input type="number" name="number_of_people" min="1" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date From</label>
                        <input type="date" name="date_from" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date To</label>
                        <input type="date" name="date_to" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                        <input type="text" name="location" required 
                               class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="4" required 
                                  class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button type="button" onclick="closeModal('requestModal')" 
                            class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Save Request
                    </button>
                </div>
            </form>
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
        order: [[0, 'desc']], // Sort by organization name
        columnDefs: [
            { orderable: false, targets: [6] } // Disable ordering on actions column
        ]
    });

    // Export button
    $('#exportBtn').on('click', function() {
        table.button('.buttons-excel').trigger();
    });
});

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Business Organization Request';
    document.getElementById('requestForm').reset();
    document.getElementById('requestModal').classList.remove('hidden');
}

function editRequest(requestId) {
    // In a real implementation, you would fetch request details via AJAX
    document.getElementById('modalTitle').textContent = 'Edit Business Organization Request';
    document.getElementById('requestModal').classList.remove('hidden');
}

function viewRequest(requestId) {
    // Implementation for viewing request details
    alert('View request details functionality would be implemented here');
}

function deleteRequest(requestId) {
    if (confirm('Are you sure you want to delete this request?')) {
        // Implementation for delete request
        alert('Delete functionality would be implemented here');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
