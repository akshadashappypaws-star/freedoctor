@extends('admin.master')

@section('title', 'Doctor Payout Details')
@section('page-title', 'Doctor Payout Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-md mr-2"></i>
                        Doctor Payout Details - #{{ $payment->id }}
                    </h4>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Payment Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Payment Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Payment ID:</strong></td>
                                            <td>#{{ $payment->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Amount:</strong></td>
                                            <td>₹{{ number_format($payment->amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'processing' => 'info',
                                                        'completed' => 'success',
                                                        'success' => 'success',
                                                        'failed' => 'danger',
                                                        'cancelled' => 'secondary'
                                                    ];
                                                    $status = $payment->payment_status ?? 'pending';
                                                    $color = $statusColors[$status] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-{{ $color }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment ID:</strong></td>
                                            <td>{{ $payment->payment_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Order ID:</strong></td>
                                            <td>{{ $payment->order_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Receipt Number:</strong></td>
                                            <td>{{ $payment->receipt_number ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Payment Date:</strong></td>
                                            <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Doctor Information</h5>
                                </div>
                                <div class="card-body">
                                    @if($payment->doctor)
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>Name:</strong></td>
                                            <td>Dr. {{ $payment->doctor->doctor_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $payment->doctor->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $payment->doctor->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Doctor ID:</strong></td>
                                            <td>#{{ $payment->doctor->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Specialty:</strong></td>
                                            <td>{{ $payment->doctor->specialty->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Experience:</strong></td>
                                            <td>{{ $payment->doctor->experience ?? 'N/A' }} years</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Registration Date:</strong></td>
                                            <td>{{ $payment->doctor->created_at ? $payment->doctor->created_at->format('M d, Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($payment->doctor->approved_by_admin)
                                                    <span class="badge badge-success">Approved</span>
                                                @else
                                                    <span class="badge badge-warning">Pending Approval</span>
                                                @endif
                                                
                                                @if($payment->doctor->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                    @else
                                    <p class="text-muted">Doctor information not available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    @if($payment->payment_details)
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Payment Details</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : json_decode($payment->payment_details, true);
                                    @endphp
                                    
                                    @if($paymentDetails)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Banking Information:</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Account Holder:</strong></td>
                                                    <td>{{ $paymentDetails['account_holder_name'] ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Bank Name:</strong></td>
                                                    <td>{{ $paymentDetails['bank_name'] ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Account Number:</strong></td>
                                                    <td>{{ isset($paymentDetails['account_number']) ? '****' . substr($paymentDetails['account_number'], -4) : 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>IFSC Code:</strong></td>
                                                    <td>{{ $paymentDetails['ifsc_code'] ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Transaction Information:</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Razorpay Payout ID:</strong></td>
                                                    <td>{{ $paymentDetails['razorpay_payout_id'] ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Payout Status:</strong></td>
                                                    <td>{{ $paymentDetails['payout_status'] ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>UTR Number:</strong></td>
                                                    <td>{{ $paymentDetails['utr'] ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Processed At:</strong></td>
                                                    <td>{{ $paymentDetails['processed_at'] ?? 'N/A' }}</td>
                                                </tr>
                                                @if(isset($paymentDetails['failure_reason']))
                                                <tr>
                                                    <td><strong>Failure Reason:</strong></td>
                                                    <td class="text-danger">{{ $paymentDetails['failure_reason'] }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                    @else
                                    <p class="text-muted">Payment details not available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Description -->
                    @if($payment->description)
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">Description</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $payment->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.doctorPayouts') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Doctor Payouts
                                </a>
                                
                                <div>
                                    @if($payment->payment_status === 'pending')
                                    <button class="btn btn-primary mr-2" onclick="processDoctorPayout({{ $payment->id }})">
                                        <i class="fas fa-play mr-2"></i>Process Payout
                                    </button>
                                    @endif
                                    
                                    @if(isset($paymentDetails['razorpay_payout_id']))
                                    <button class="btn btn-info mr-2" onclick="checkDoctorPaymentStatus({{ $payment->id }})">
                                        <i class="fas fa-sync mr-2"></i>Check Status
                                    </button>
                                    @endif
                                    
                                    <a href="{{ route('admin.doctorPayouts.receipt', $payment->id) }}" class="btn btn-success mr-2" target="_blank">
                                        <i class="fas fa-receipt mr-2"></i>Download Receipt
                                    </a>
                                    
                                    <button class="btn btn-success" onclick="window.print()">
                                        <i class="fas fa-print mr-2"></i>Print Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Process Payout Modal -->
<div id="processDoctorModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Doctor Payout</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to process this doctor payout?</p>
                <p><strong>Amount:</strong> ₹{{ number_format($payment->amount, 2) }}</p>
                <p><strong>Doctor:</strong> Dr. {{ $payment->doctor->doctor_name ?? 'N/A' }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmProcessDoctorPayout()">Process Payout</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentDoctorPaymentId = null;

function processDoctorPayout(paymentId) {
    currentDoctorPaymentId = paymentId;
    $('#processDoctorModal').modal('show');
}

function confirmProcessDoctorPayout() {
    if (!currentDoctorPaymentId) return;
    
    $.ajax({
        url: `/admin/doctor-payouts/${currentDoctorPaymentId}/process`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Doctor payout processed successfully!');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Failed to process payout'));
            }
        },
        error: function(xhr) {
            alert('Error processing doctor payout');
        },
        complete: function() {
            $('#processDoctorModal').modal('hide');
        }
    });
}

function checkDoctorPaymentStatus(paymentId) {
    $.ajax({
        url: `/admin/doctor-payouts/${paymentId}/status`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                alert('Current status: ' + response.status);
                location.reload();
            } else {
                alert('Error checking status: ' + response.message);
            }
        },
        error: function(xhr) {
            alert('Error checking payment status');
        }
    });
}

// Print styles
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.btn').forEach(btn => btn.style.display = 'none');
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.btn').forEach(btn => btn.style.display = 'inline-block');
});
</script>
@endpush

@push('styles')
<style>
/* Enhanced Doctor Payout Details Styling */
.container-fluid {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 20px;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.card-header:hover::before {
    left: 100%;
}

.card-header h4 {
    color: white;
    font-weight: 600;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.card-header h5 {
    color: white;
    font-weight: 500;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.card-header.bg-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
}

.card-header.bg-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card-header.bg-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.card-body {
    padding: 25px;
    background: white;
}

.table {
    margin-bottom: 0;
}

.table td {
    padding: 12px 8px;
    border: none;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.table tr:last-child td {
    border-bottom: none;
}

.table strong {
    color: #2c3e50;
    font-weight: 600;
}

.badge {
    padding: 8px 15px;
    font-size: 12px;
    font-weight: 500;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
}

.badge-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
}

.badge-danger {
    background: linear-gradient(135deg, #ff4757 0%, #ff3742 100%);
    border: none;
}

.badge-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    border: none;
}

.btn {
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    margin: 5px;
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

.btn-success {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(79, 172, 254, 0.6);
}

.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(240, 147, 251, 0.6);
}

.btn-info {
    background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
    box-shadow: 0 4px 15px rgba(54, 209, 220, 0.4);
}

.btn-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(54, 209, 220, 0.6);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
}

.btn-secondary {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    box-shadow: 0 4px 15px rgba(149, 165, 166, 0.4);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(149, 165, 166, 0.6);
}

/* Status indicators with animation */
.status-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.status-indicator::before {
    content: '';
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* Modal enhancements */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-title {
    font-weight: 600;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border: none;
    padding: 20px 25px;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table td {
        padding: 8px 4px;
        font-size: 14px;
    }
    
    .btn {
        padding: 10px 20px;
        font-size: 14px;
        margin: 2px;
    }
}

/* Print styles */
@media print {
    .btn, .modal, .card-header .btn {
        display: none !important;
    }
    
    .container-fluid {
        background: white !important;
        padding: 0 !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        page-break-inside: avoid;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    .badge {
        border: 1px solid #dee2e6 !important;
        background: #f8f9fa !important;
        color: #000 !important;
    }
}
</style>
@endpush
@endsection
