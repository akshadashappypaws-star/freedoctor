@extends('../admin.dashboard')

@section('title', 'Patient Payouts Management')
@section('page-title', 'Patient Payouts Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
    <!-- Header Section -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Patient Payouts</h1>
                        <p class="text-gray-600 mt-1">Manage patient withdrawal requests and process payouts</p>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="mt-6 lg:mt-0 flex flex-wrap gap-4">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Total Requests</div>
                        <div class="text-xl font-bold">{{ $patientPayments->total() }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Pending</div>
                        <div class="text-xl font-bold">{{ $patientPayments->where('status', 'pending')->count() }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Completed</div>
                        <div class="text-xl font-bold">{{ $patientPayments->where('status', 'completed')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Patient</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, email, or ID..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div class="min-w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.patientPayouts') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Payouts Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Patient Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bank Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Request Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patientPayments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $payment->user->name ?? 'Unknown User' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $payment->user->email ?? 'No Email' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            ID: #{{ str_pad($payment->user_id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">
                                    ₹{{ number_format($payment->amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ ucfirst($payment->payment_method ?? 'razorpay') }}
                                </div>
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($payment->bank_details)
                                    @php $bankDetails = is_array($payment->bank_details) ? $payment->bank_details : json_decode($payment->bank_details, true); @endphp
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $bankDetails['bank_name'] ?? 'N/A' }}</div>
                                        <div class="text-gray-500">{{ $bankDetails['account_number'] ?? 'N/A' }}</div>
                                        <div class="text-gray-500">{{ $bankDetails['ifsc_code'] ?? 'N/A' }}</div>
                                        <div class="text-gray-500">{{ $bankDetails['account_holder_name'] ?? 'N/A' }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">No bank details</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($payment->status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($payment->status === 'pending')
                                        <i class="fas fa-clock mr-1"></i>
                                    @elseif($payment->status === 'processing')
                                        <i class="fas fa-spinner mr-1"></i>
                                    @elseif($payment->status === 'completed')
                                        <i class="fas fa-check mr-1"></i>
                                    @elseif($payment->status === 'failed')
                                        <i class="fas fa-times mr-1"></i>
                                    @endif
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($payment->status === 'pending')
                                        <button onclick="processPayment({{ $payment->id }})" 
                                                class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 transition-colors">
                                            <i class="fas fa-check mr-1"></i>Process
                                        </button>
                                    @endif
                                    
                                    @if(in_array($payment->status, ['processing', 'completed']) && $payment->razorpay_payout_id)
                                        <button onclick="checkPaymentStatus({{ $payment->id }})" 
                                                class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-sync mr-1"></i>Check Status
                                        </button>
                                    @endif
                                    
                                    <button onclick="viewPaymentDetails({{ $payment->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-money-bill-wave text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Patient Payouts Found</h3>
                                    <p class="text-gray-500">There are no patient withdrawal requests at the moment.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($patientPayments->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $patientPayments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Payment Processing Modal -->
<div id="processModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9994]">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Process Payment</h3>
                <button onclick="closeModal('processModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to process this payment? This will initiate the payout via Razorpay.
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('processModal')" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmProcessPayment()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-2"></i>Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for enhanced UI */
.table-hover tr:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.status-badge {
    position: relative;
    overflow: hidden;
}

.status-badge::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.status-badge:hover::before {
    left: 100%;
}

/* Loading animation */
.loading-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive improvements */
@media (max-width: 768px) {
    .stats-cards {
        flex-direction: column;
    }
    
    .table-container {
        font-size: 0.875rem;
    }
}
</style>

<script>
let currentPaymentId = null;

function processPayment(paymentId) {
    currentPaymentId = paymentId;
    document.getElementById('processModal').classList.remove('hidden');
}

function confirmProcessPayment() {
    if (!currentPaymentId) return;
    
    // Show loading state
    const modal = document.getElementById('processModal');
    const button = modal.querySelector('button[onclick="confirmProcessPayment()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner loading-spinner mr-2"></i>Processing...';
    button.disabled = true;
    
    fetch(`/admin/patient-payouts/${currentPaymentId}/process`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Expected JSON but received:', text);
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 100));
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        if (data.success) {
            showNotification('Payment processed successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + (data.message || 'Failed to process payment'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while processing the payment.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        closeModal('processModal');
    });
}

function checkPaymentStatus(paymentId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner loading-spinner mr-1"></i>Checking...';
    button.disabled = true;
    
    fetch(`/admin/patient-payouts/${paymentId}/status`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status updated: ' + data.status, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Error checking status: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to check payment status.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function viewPaymentDetails(paymentId) {
    window.open(`/admin/patient-payouts/${paymentId}/details`, '_blank');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentPaymentId = null;
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Auto-refresh every 30 seconds for pending payments
setInterval(() => {
    const url = new URL(window.location);
    if (!url.searchParams.has('status') || url.searchParams.get('status') === 'pending') {
        // Only auto-refresh if we're viewing pending payments or all payments
        location.reload();
    }
}, 30000);
</script>
@endsection
