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
            <h1 class="text-3xl font-bold text-white">Doctor Payments</h1>
            <p class="text-gray-300 mt-2">Manage doctor registration payments and approvals</p>
        </div>
        <div class="flex space-x-3">
            <button id="exportBtn" class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white px-6 py-3 rounded-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <i class="fas fa-download mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total Payments</p>
                    <p class="text-3xl font-bold">{{ $payments->total() }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-credit-card text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Successful Payments</p>
                    <p class="text-3xl font-bold">{{ $payments->where('payment_status', 'success')->count() }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>



        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold">₹{{ number_format($payments->where('payment_status', 'success')->sum('amount'), 2) }}</p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-lg p-3">
                    <i class="fas fa-rupee-sign text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-filter mr-2"></i>Advanced Filters
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Search Doctor</label>
                <input type="text" id="searchInput" placeholder="Search by name or email..." 
                       class="w-full px-4 py-2 bg-slate-600 border border-slate-500 text-white placeholder-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button id="resetFilters" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-refresh mr-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-slate-700 border border-slate-600 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table id="paymentsTable" class="min-w-full divide-y divide-slate-600">
                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Payment Status</th>
                       
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Admin Status</th>
                    </tr>
                </thead>
                <tbody class="bg-slate-700 divide-y divide-slate-600">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-slate-600 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">{{ substr($payment->doctor->doctor_name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-white">{{ $payment->doctor->doctor_name }}</div>
                                    <div class="text-sm text-gray-300">{{ $payment->doctor->email }}</div>
                                    @if($payment->doctor->specialty)
                                        <div class="text-xs text-blue-400">{{ $payment->doctor->specialty->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-green-400">₹{{ number_format($payment->amount, 2) }}</div>
                            <div class="text-xs text-gray-400">{{ $payment->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->payment_status === 'success') bg-green-100 text-green-800
                                @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($payment->payment_status === 'success')
                                    <i class="fas fa-check-circle mr-1"></i>
                                @elseif($payment->payment_status === 'pending')
                                    <i class="fas fa-clock mr-1"></i>
                                @else
                                    <i class="fas fa-times-circle mr-1"></i>
                                @endif
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>
                       
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->doctor->approved_by_admin)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i>Pending Approval
                                </span>
                            @endif
                        </td>
                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<!-- View Payment Modal -->
<div id="viewPaymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[9997]">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Patient Details</h3>
                <button onclick="closeModal('viewPaymentModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="paymentDetails" class="space-y-4 text-gray-900">
                <!-- Patient details will be loaded here -->
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
    var table = $('#paymentsTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: ['excel', 'csv'],
        pageLength: 10,
        order: [[3, 'desc']], // Sort by payment date
        columnDefs: [
            { orderable: false, targets: [5] } // Disable ordering on actions column
        ]
    });

    // Filter by status
    $('#statusFilter').on('change', function() {
        table.column(2).search(this.value).draw();
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

function viewPayment(paymentId) {
    // Show loading
    document.getElementById('paymentDetails').innerHTML = '<div class="text-center py-4 text-gray-900"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    document.getElementById('viewPaymentModal').classList.remove('hidden');
    
    // In a real implementation, you would fetch payment details via AJAX
    // For now, showing placeholder
    setTimeout(() => {
        document.getElementById('paymentDetails').innerHTML = `
            <div class="space-y-3 text-gray-900">
                <div class="flex justify-between"><span class="font-medium text-gray-900">Payment ID:</span><span class="font-mono text-sm text-gray-800">PAY_${paymentId}</span></div>
                <div class="flex justify-between"><span class="font-medium text-gray-900">Amount:</span><span class="text-green-600 font-semibold">₹500.00</span></div>
                <div class="flex justify-between"><span class="font-medium text-gray-900">Status:</span><span class="text-green-600">Success</span></div>
                <div class="flex justify-between"><span class="font-medium text-gray-900">Gateway:</span><span class="text-gray-800">Razorpay</span></div>
            </div>
        `;
    }, 500);
}

function approveDoctor(paymentId) {
    if (confirm('Are you sure you want to approve this doctor?')) {
        fetch(`/admin/doctor-payments/${paymentId}/approve`, {
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
            alert('An error occurred while approving the doctor.');
        });
    }
}

function downloadReceipt(paymentId) {
    window.open(`/admin/doctor-payments/${paymentId}/receipt`, '_blank');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection