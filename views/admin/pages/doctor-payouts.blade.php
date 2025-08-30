@extends('admin.master')

@section('title', 'Doctor Payouts Management')
@section('page-title', 'Doctor Payouts Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-emerald-50">
    <!-- Header Section -->
    <div class="bg-white shadow-lg border-b border-gray-200">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg">
                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Doctor Payouts</h1>
                        <p class="text-gray-600 mt-1">Manage doctor withdrawal requests and process earnings payouts</p>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="mt-6 lg:mt-0 flex flex-wrap gap-4">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Total Requests</div>
                        <div class="text-xl font-bold">{{ $doctorPayments->total() }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Pending</div>
                        <div class="text-xl font-bold">{{ $doctorPayments->where('payment_status', 'pending')->count() }}</div>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg px-4 py-3 text-white">
                        <div class="text-sm opacity-90">Processing</div>
                        <div class="text-xl font-bold">{{ $doctorPayments->where('payment_status', 'processing')->count() }}</div>
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
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Doctor</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by doctor name, email, or ID..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <div class="min-w-48">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('admin.doctorPayouts') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Doctor Payouts Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Doctor Details
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Withdrawal Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Earnings Breakdown
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
                        @forelse($doctorPayments as $payment)
                        @php
                            $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : json_decode($payment->payment_details, true);
                            $bankDetails = $paymentDetails['bank_name'] ?? null;
                            $earningsBreakdown = $paymentDetails['earnings_breakdown'] ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 flex items-center justify-center">
                                            <span class="text-white font-medium text-sm">
                                                {{ strtoupper(substr($payment->doctor->doctor_name ?? 'D', 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $payment->doctor->doctor_name ?? 'Unknown Doctor' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $payment->doctor->email ?? 'No Email' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            Dr. ID: #{{ str_pad($payment->doctor_id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                        @if($payment->doctor->specialty)
                                        <div class="text-xs text-blue-600">
                                            {{ $payment->doctor->specialty->name }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">
                                    ₹{{ number_format($payment->amount, 2) }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    Withdrawal Request
                                </div>
                                @if(isset($paymentDetails['previous_withdrawn']))
                                <div class="text-xs text-gray-400">
                                    Previously withdrawn: ₹{{ number_format($paymentDetails['previous_withdrawn'], 2) }}
                                </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($earningsBreakdown)
                                <div class="text-sm space-y-1">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Registration:</span>
                                        <span class="font-medium">₹{{ number_format($earningsBreakdown['registration_earnings'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Sponsors:</span>
                                        <span class="font-medium">₹{{ number_format($earningsBreakdown['sponsor_earnings'] ?? 0, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-1">
                                        <span class="text-gray-800 font-medium">Total Earned:</span>
                                        <span class="font-bold text-emerald-600">₹{{ number_format($earningsBreakdown['total_earnings'] ?? 0, 2) }}</span>
                                    </div>
                                    @if(isset($earningsBreakdown['available_balance']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Available:</span>
                                        <span class="font-medium text-blue-600">₹{{ number_format($earningsBreakdown['available_balance'], 2) }}</span>
                                    </div>
                                    @endif
                                </div>
                                @else
                                <span class="text-gray-400 italic">No breakdown available</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4">
                                @if($bankDetails)
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $paymentDetails['bank_name'] }}</div>
                                    <div class="text-gray-500">{{ $paymentDetails['account_number'] }}</div>
                                    <div class="text-gray-500">{{ $paymentDetails['ifsc_code'] }}</div>
                                    <div class="text-gray-500">{{ $paymentDetails['account_holder_name'] }}</div>
                                </div>
                                @else
                                <span class="text-gray-400 italic">No bank details</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->payment_status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($payment->payment_status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($payment->payment_status === 'pending')
                                        <i class="fas fa-clock mr-1"></i>
                                    @elseif($payment->payment_status === 'processing')
                                        <i class="fas fa-spinner mr-1"></i>
                                    @elseif($payment->payment_status === 'completed')
                                        <i class="fas fa-check mr-1"></i>
                                    @elseif($payment->payment_status === 'failed')
                                        <i class="fas fa-times mr-1"></i>
                                    @endif
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                                
                                @if($payment->order_id)
                                <div class="text-xs text-gray-400 mt-1">
                                    Order: {{ $payment->order_id }}
                                </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $payment->created_at->format('h:i A') }}</div>
                                @if(isset($paymentDetails['withdrawal_request_time']))
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($paymentDetails['withdrawal_request_time'])->diffForHumans() }}
                                </div>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @if($payment->payment_status === 'pending')
                                        <button onclick="processDoctorPayout({{ $payment->id }})" 
                                                class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white text-xs rounded-md hover:bg-emerald-700 transition-colors">
                                            <i class="fas fa-credit-card mr-1"></i>Process Payout
                                        </button>
                                    @endif
                                    
                                    @if(in_array($payment->payment_status, ['processing', 'completed']) && $payment->payment_id)
                                        <button onclick="checkDoctorPaymentStatus({{ $payment->id }})" 
                                                class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-sync mr-1"></i>Check Status
                                        </button>
                                    @endif
                                    
                                    <button onclick="viewDoctorPaymentDetails({{ $payment->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-gray-600 text-white text-xs rounded-md hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-eye mr-1"></i>View Details
                                    </button>
                                    
                                    @if($payment->payment_status === 'completed')
                                        <button onclick="downloadPayoutReceipt({{ $payment->id }})" 
                                                class="inline-flex items-center px-3 py-1 bg-purple-600 text-white text-xs rounded-md hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-download mr-1"></i>Receipt
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-hand-holding-usd text-gray-400 text-4xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Doctor Payouts Found</h3>
                                    <p class="text-gray-500">There are no doctor withdrawal requests at the moment.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($doctorPayments->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $doctorPayments->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Doctor Payout Processing Modal -->
<div id="processDoctorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[9990]">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Process Doctor Payout</h3>
                <button onclick="closeModal('processDoctorModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    Are you sure you want to process this doctor payout? This will initiate the withdrawal via Razorpay.
                </p>
                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <div class="flex">
                        <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                        <div class="text-sm text-yellow-800">
                            <p><strong>Important:</strong> Ensure bank details are verified before processing.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button onclick="closeModal('processDoctorModal')" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button onclick="confirmProcessDoctorPayout()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-credit-card mr-2"></i>Process Payout
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced styling for doctor payouts */
.earnings-breakdown {
    background: linear-gradient(135deg, #f0f9ff 0%, #ecfdf5 100%);
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    padding: 12px;
}

.bank-details-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px;
}

.status-processing {
    animation: pulse-blue 2s infinite;
}

@keyframes pulse-blue {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.earnings-item {
    display: flex;
    justify-content: space-between;
    padding: 2px 0;
}

.earnings-total {
    border-top: 1px solid #d1d5db;
    padding-top: 4px;
    margin-top: 4px;
    font-weight: 600;
}

/* Hover effects for action buttons */
.action-button {
    transition: all 0.2s ease;
}

.action-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Loading state for buttons */
.button-loading {
    opacity: 0.7;
    cursor: not-allowed;
}

.button-loading .fas {
    animation: spin 1s linear infinite;
}

/* Responsive table */
@media (max-width: 1024px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .table-responsive th,
    .table-responsive td {
        padding: 8px 12px;
    }
}

/* Status badge animations */
.status-badge {
    position: relative;
    overflow: hidden;
}

.status-badge.status-pending {
    animation: subtle-pulse 3s ease-in-out infinite;
}

@keyframes subtle-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
</style>

<script>
let currentDoctorPaymentId = null;

function processDoctorPayout(paymentId) {
    currentDoctorPaymentId = paymentId;
    document.getElementById('processDoctorModal').classList.remove('hidden');
}

function confirmProcessDoctorPayout() {
    if (!currentDoctorPaymentId) return;
    
    // Show loading state
    const modal = document.getElementById('processDoctorModal');
    const button = modal.querySelector('button[onclick="confirmProcessDoctorPayout()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner loading-spinner mr-2"></i>Processing Payout...';
    button.disabled = true;
    button.classList.add('button-loading');
    
    fetch(`/admin/doctor-payouts/${currentDoctorPaymentId}/process`, {
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
            showNotification('Doctor payout processed successfully!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Error: ' + (data.message || 'Failed to process doctor payout'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred while processing the doctor payout.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        button.classList.remove('button-loading');
        closeModal('processDoctorModal');
    });
}

function checkDoctorPaymentStatus(paymentId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner loading-spinner mr-1"></i>Checking...';
    button.disabled = true;
    button.classList.add('button-loading');
    
    fetch(`/admin/doctor-payouts/${paymentId}/status`, {
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
        showNotification('Failed to check doctor payment status.', 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        button.classList.remove('button-loading');
    });
}

function viewDoctorPaymentDetails(paymentId) {
    window.open(`/admin/doctor-payouts/${paymentId}/details`, '_blank');
}

function downloadPayoutReceipt(paymentId) {
    window.open(`/admin/doctor-payouts/${paymentId}/receipt`, '_blank');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    currentDoctorPaymentId = null;
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notif => notif.remove());
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-emerald-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Enhanced auto-refresh for doctor payouts
let autoRefreshInterval;

function startAutoRefresh() {
    // Auto-refresh every 45 seconds for pending/processing payouts
    autoRefreshInterval = setInterval(() => {
        const url = new URL(window.location);
        const status = url.searchParams.get('status');
        
        if (!status || ['pending', 'processing'].includes(status)) {
            // Show subtle loading indicator
            const refreshIndicator = document.createElement('div');
            refreshIndicator.className = 'fixed top-2 right-2 bg-blue-500 text-white px-2 py-1 rounded text-xs z-40';
            refreshIndicator.innerHTML = '<i class="fas fa-sync loading-spinner mr-1"></i>Refreshing...';
            document.body.appendChild(refreshIndicator);
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }, 45000);
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
});

// Stop auto-refresh when user is actively interacting
let userInteractionTimer;
document.addEventListener('click', function() {
    clearInterval(autoRefreshInterval);
    clearTimeout(userInteractionTimer);
    
    userInteractionTimer = setTimeout(() => {
        startAutoRefresh();
    }, 60000); // Resume auto-refresh after 1 minute of no interaction
});

// Handle visibility change (stop refresh when tab is not active)
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearInterval(autoRefreshInterval);
    } else {
        startAutoRefresh();
    }
});
</script>
@endsection
