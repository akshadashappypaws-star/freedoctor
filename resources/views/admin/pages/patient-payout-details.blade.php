@extends('admin.master')

@section('title', 'Patient Payout Details')
@section('page-title', 'Patient Payout Details')

@section('content')
<div class="modern-payout-container">
    <!-- Header Section -->
    <div class="payout-header">
        <div class="header-content">
            <div class="header-left">
                <div class="payout-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="header-info">
                    <h1 class="payout-title">Patient Payout Details</h1>
                    <p class="payout-subtitle">Payment ID: #{{ $payment->id }}</p>
                </div>
            </div>
            <div class="header-right">
                <div class="status-badge-container">
                    @php
                        $status = $payment->payment_status ?? $payment->status ?? 'pending';
                        $statusConfig = [
                            'pending' => ['icon' => 'clock', 'color' => 'warning', 'label' => 'Pending'],
                            'processing' => ['icon' => 'spinner fa-spin', 'color' => 'info', 'label' => 'Processing'],
                            'completed' => ['icon' => 'check-circle', 'color' => 'success', 'label' => 'Completed'],
                            'failed' => ['icon' => 'times-circle', 'color' => 'danger', 'label' => 'Failed'],
                            'cancelled' => ['icon' => 'ban', 'color' => 'secondary', 'label' => 'Cancelled']
                        ];
                        $config = $statusConfig[$status] ?? $statusConfig['pending'];
                    @endphp
                    <div class="status-badge status-{{ $config['color'] }}">
                        <i class="fas fa-{{ $config['icon'] }}"></i>
                        <span>{{ $config['label'] }}</span>
                    </div>
                </div>
                <div class="amount-display">
                    <span class="currency">₹</span>
                    <span class="amount">{{ number_format($payment->amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Payment Overview Card -->
        <div class="overview-card">
            <div class="card-header">
                <h3><i class="fas fa-credit-card"></i> Payment Overview</h3>
            </div>
            <div class="card-content">
                <div class="overview-stats">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Payment ID</span>
                            <span class="stat-value">#{{ $payment->id }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Created</span>
                            <span class="stat-value">{{ $payment->created_at ? $payment->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Method</span>
                            <span class="stat-value">{{ $payment->payment_method ?? 'Bank Transfer' }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-label">Type</span>
                            <span class="stat-value">{{ ucfirst($payment->type ?? 'withdrawal') }}</span>
                        </div>
                    </div>
                </div>
                
                @if($payment->razorpay_payment_id || $payment->razorpay_payout_id)
                <div class="razorpay-info">
                    <h4><i class="fab fa-razorpay"></i> Razorpay Information</h4>
                    <div class="razorpay-details">
                        @if($payment->razorpay_payment_id)
                        <div class="detail-row">
                            <span class="label">Payment ID:</span>
                            <span class="value">{{ $payment->razorpay_payment_id }}</span>
                        </div>
                        @endif
                        @if($payment->razorpay_payout_id)
                        <div class="detail-row">
                            <span class="label">Payout ID:</span>
                            <span class="value">{{ $payment->razorpay_payout_id }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- User Information Card -->
        <div class="user-info-card">
            <div class="card-header">
                <h3><i class="fas fa-user-circle"></i> User Information</h3>
            </div>
            <div class="card-content">
                @if($payment->patientRegistration && $payment->patientRegistration->user)
                <div class="user-profile">
                    <div class="user-avatar">
                        @if($payment->patientRegistration->user->avatar)
                            <img src="{{ $payment->patientRegistration->user->avatar }}" alt="User Avatar">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($payment->patientRegistration->patient_name ?: $payment->patientRegistration->user->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="user-details">
                        <h4>{{ $payment->patientRegistration->patient_name ?: $payment->patientRegistration->user->name }}</h4>
                        <p class="user-id">Patient ID: #{{ $payment->patientRegistration->id }} | User ID: #{{ $payment->patientRegistration->user->id }}</p>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $payment->patientRegistration->email ?: $payment->patientRegistration->user->email }}</span>
                            </div>
                            @if($payment->patientRegistration->phone_number ?: $payment->patientRegistration->user->phone)
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>{{ $payment->patientRegistration->phone_number ?: $payment->patientRegistration->user->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="user-financial-info">
                    <div class="financial-stat">
                        <div class="stat-icon earnings">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Payment Amount</span>
                            <span class="stat-amount">₹{{ number_format(abs($payment->amount), 2) }}</span>
                        </div>
                    </div>
                    
                    <div class="financial-stat">
                        <div class="stat-icon balance">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Registration Date</span>
                            <span class="stat-amount">{{ $payment->patientRegistration->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="financial-stat">
                        <div class="stat-icon withdraw">
                            <i class="fas fa-university"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-label">Refund Status</span>
                            <span class="stat-amount status-{{ strtolower($payment->status) }}">{{ ucfirst($payment->status) }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="no-data">
                    <i class="fas fa-user-slash"></i>
                    <p>Patient registration information not available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Bank Details Card -->
        @if($payment->bank_details)
        <div class="bank-details-card">
            <div class="card-header">
                <h3><i class="fas fa-university"></i> Bank Details</h3>
                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Secured</span>
                </div>
            </div>
            <div class="card-content">
                @php
                    $bankDetails = is_array($payment->bank_details) ? $payment->bank_details : json_decode($payment->bank_details, true);
                @endphp
                
                @if($bankDetails)
                <div class="bank-info-grid">
                    <div class="bank-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Account Holder</span>
                            <span class="detail-value">{{ $bankDetails['account_holder_name'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="bank-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Bank Name</span>
                            <span class="detail-value">{{ $bankDetails['bank_name'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="bank-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">Account Number</span>
                            <span class="detail-value masked-account">
                                {{ isset($bankDetails['account_number']) ? '••••••••••••' . substr($bankDetails['account_number'], -4) : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="bank-detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="detail-content">
                            <span class="detail-label">IFSC Code</span>
                            <span class="detail-value">{{ $bankDetails['ifsc_code'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="no-data">
                    <i class="fas fa-university"></i>
                    <p>Bank details not available</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Timeline Card -->
        <div class="timeline-card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Payment Timeline</h3>
            </div>
            <div class="card-content">
                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="timeline-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Payment Created</h4>
                            <p>{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i:s') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    @if($payment->processed_at)
                    <div class="timeline-item completed">
                        <div class="timeline-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Payment Processed</h4>
                            <p>{{ $payment->processed_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($status === 'completed')
                    <div class="timeline-item completed">
                        <div class="timeline-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Payment Completed</h4>
                            <p>Successfully transferred to user account</p>
                        </div>
                    </div>
                    @elseif($status === 'failed')
                    <div class="timeline-item failed">
                        <div class="timeline-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>Payment Failed</h4>
                            <p>Payment could not be completed</p>
                        </div>
                    </div>
                    @else
                    <div class="timeline-item pending">
                        <div class="timeline-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="timeline-content">
                            <h4>{{ ucfirst($status) }}</h4>
                            <p>Payment is currently {{ $status }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes Card -->
        @if($payment->notes)
        <div class="notes-card">
            <div class="card-header">
                <h3><i class="fas fa-sticky-note"></i> Notes</h3>
            </div>
            <div class="card-content">
                <div class="notes-content">
                    <p>{{ $payment->notes }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div class="action-left">
            <a href="{{ route('admin.patientPayouts') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                <span>Back to Payouts</span>
            </a>
        </div>
        
        <div class="action-right">
            @if($status === 'pending')
            <button class="btn btn-primary" onclick="processPayment({{ $payment->id }})">
                <i class="fas fa-play"></i>
                <span>Process Payment</span>
            </button>
            @endif
            
            @if($payment->razorpay_payout_id)
            <button class="btn btn-info" onclick="checkPaymentStatus({{ $payment->id }})">
                <i class="fas fa-sync"></i>
                <span>Check Status</span>
            </button>
            @endif
            
            <button class="btn btn-success" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Print Details</span>
            </button>
        </div>
    </div>
</div>

<!-- Enhanced Process Payment Modal -->
<div id="processModal" class="modern-modal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-play-circle"></i> Process Payment</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-content">
            <div class="confirmation-details">
                <div class="confirmation-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="confirmation-text">
                    <h4>Confirm Payment Processing</h4>
                    <p>Are you sure you want to process this payment? This action cannot be undone.</p>
                </div>
            </div>
            
            <div class="payment-summary">
                <div class="summary-row">
                    <span class="label">Amount:</span>
                    <span class="value">₹{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">User:</span>
                    <span class="value">{{ $payment->patientRegistration->patient_name ?? ($payment->patientRegistration->user->name ?? 'N/A') }}</span>
                </div>
                <div class="summary-row">
                    <span class="label">Payment ID:</span>
                    <span class="value">#{{ $payment->id }}</span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
            <button class="btn btn-primary" onclick="confirmProcessPayment()">
                <i class="fas fa-check"></i>
                <span>Process Payment</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentPaymentId = null;


function processPayment(paymentId) {
    currentPaymentId = paymentId;
    var modal = document.getElementById('processModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function closeModal() {
    var modal = document.getElementById('processModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function confirmProcessPayment() {
    if (!currentPaymentId) return;
    
    // Show loading state
    const button = document.querySelector('#processModal .btn-primary');
    if (!button) return;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
    button.disabled = true;
    
    $.ajax({
        url: `/admin/patient-payouts/${currentPaymentId}/process`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showNotification('Payment processed successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error: ' + (response.message || 'Failed to process payment'), 'error');
            }
        },
        error: function(xhr) {
            showNotification('Error processing payment', 'error');
        },
        complete: function() {
            button.innerHTML = originalText;
            button.disabled = false;
            closeModal();
        }
    });
}

function checkPaymentStatus(paymentId) {
    const button = event.target.closest('.btn');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Checking...</span>';
    button.disabled = true;
    
    $.ajax({
        url: `/admin/patient-payouts/${paymentId}/status`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                showNotification('Current status: ' + response.status, 'info');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Error checking status: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            showNotification('Error checking payment status', 'error');
        },
        complete: function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Close modal with escape key
if (typeof document !== 'undefined') {
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}
</script>
@endpush

@push('styles')
<style>
/* Modern Payout Details Styling */
* {
    box-sizing: border-box;
}

.modern-payout-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header Section */
.payout-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.payout-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.header-info h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.payout-subtitle {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 16px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 20px;
}

.status-badge-container {
    display: flex;
    align-items: center;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.status-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    color: white;
}

.status-danger {
    background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
    color: white;
}

.status-secondary {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
}

.amount-display {
    text-align: right;
}

.currency {
    font-size: 20px;
    color: #666;
    font-weight: 500;
}

.amount {
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-left: 5px;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 25px;
}

/* Card Styles */
.overview-card,
.user-info-card,
.bank-details-card,
.timeline-card,
.notes-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.overview-card:hover,
.user-info-card:hover,
.bank-details-card:hover,
.timeline-card:hover,
.notes-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: white;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.security-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 12px;
    color: white;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-content {
    padding: 25px;
}

/* Overview Stats */
.overview-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border-radius: 15px;
    border-left: 4px solid #667eea;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.stat-details {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.stat-value {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

/* Razorpay Info */
.razorpay-info {
    border-top: 1px solid #eee;
    padding-top: 20px;
}

.razorpay-info h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.razorpay-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    color: #666;
    font-weight: 500;
}

.detail-row .value {
    color: #2c3e50;
    font-weight: 600;
    font-family: monospace;
}

/* User Info */
.user-profile {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.user-avatar {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    font-weight: 700;
}

.user-details h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
}

.user-id {
    margin: 0 0 15px 0;
    color: #666;
    font-size: 14px;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #666;
    font-size: 14px;
}

.contact-item i {
    width: 16px;
    color: #667eea;
}

/* Financial Info */
.user-financial-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.financial-stat {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border-radius: 15px;
}

.financial-stat .stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
}

.financial-stat .stat-icon.earnings {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.financial-stat .stat-icon.balance {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.financial-stat .stat-icon.member {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}

.stat-amount {
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.stat-date {
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
}

/* Bank Details */
.bank-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.bank-detail-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    border-radius: 15px;
    border: 1px solid #e8ecff;
}

.detail-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.masked-account {
    font-family: monospace;
    letter-spacing: 1px;
}

/* Timeline */
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #667eea, #764ba2);
}

.timeline-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
    padding-left: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-icon {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
    z-index: 2;
}

.timeline-item.completed .timeline-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.timeline-item.pending .timeline-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.timeline-item.failed .timeline-icon {
    background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
}

.timeline-content h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 16px;
    font-weight: 600;
}

.timeline-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

/* Notes */
.notes-content {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    padding: 20px;
    border-radius: 15px;
    border-left: 4px solid #667eea;
}

.notes-content p {
    margin: 0;
    color: #2c3e50;
    font-size: 16px;
    line-height: 1.6;
}

/* No Data */
.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.no-data i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.no-data p {
    margin: 0;
    font-size: 16px;
}

/* Action Bar */
.action-bar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.action-left,
.action-right {
    display: flex;
    gap: 15px;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    color: white;
    text-decoration: none;
}

.btn-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 172, 254, 0.6);
    color: white;
    text-decoration: none;
}

.btn-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(54, 209, 220, 0.4);
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(54, 209, 220, 0.6);
    color: white;
    text-decoration: none;
}

.btn-outline {
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.btn-outline:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    text-decoration: none;
}

/* Modern Modal */
.modern-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-container {
    position: relative;
    background: white;
    border-radius: 20px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: white;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.modal-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-content {
    padding: 25px;
}

.confirmation-details {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 25px;
}

.confirmation-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.confirmation-text h4 {
    margin: 0 0 8px 0;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
}

.confirmation-text p {
    margin: 0;
    color: #666;
    font-size: 14px;
    line-height: 1.5;
}

.payment-summary {
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    padding: 20px;
    border-radius: 15px;
    border-left: 4px solid #667eea;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e8ecff;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row .label {
    color: #666;
    font-weight: 500;
}

.summary-row .value {
    color: #2c3e50;
    font-weight: 600;
}

.modal-footer {
    padding: 20px 25px;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: flex-end;
    gap: 15px;
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: -400px;
    background: white;
    border-radius: 15px;
    padding: 15px 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    z-index: 2000;
    transition: right 0.3s ease;
    max-width: 350px;
}

.notification.show {
    right: 20px;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-success {
    border-left: 4px solid #4facfe;
}

.notification-error {
    border-left: 4px solid #ff4757;
}

.notification-info {
    border-left: 4px solid #36d1dc;
}

.notification-close {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    padding: 5px;
    border-radius: 3px;
    transition: color 0.3s ease;
}

.notification-close:hover {
    color: #666;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modern-payout-container {
        padding: 15px;
    }
    
    .payout-header {
        padding: 20px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .overview-stats {
        grid-template-columns: 1fr;
    }
    
    .user-financial-info {
        grid-template-columns: 1fr;
    }
    
    .bank-info-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
        text-align: center;
    }
    
    .action-left,
    .action-right {
        width: 100%;
        justify-content: center;
    }
    
    .amount {
        font-size: 28px;
    }
    
    .modal-container {
        margin: 20px;
        width: calc(100% - 40px);
    }
}

/* Print Styles */
@media print {
    .modern-payout-container {
        background: white !important;
        padding: 0 !important;
    }
    
    .payout-header,
    .overview-card,
    .user-info-card,
    .bank-details-card,
    .timeline-card,
    .notes-card {
        background: white !important;
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        break-inside: avoid;
    }
    
    .action-bar,
    .btn,
    .modern-modal {
        display: none !important;
    }
    
    .status-badge {
        background: #f8f9fa !important;
        color: #000 !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush
@endsection
