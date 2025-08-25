@extends('../doctor.master')

@section('title', 'Wallet - Doctor Dashboard')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
<style>
    :root {
        --primary-color: #383F45;
        --secondary-color: #E7A51B;
        --background-color: #f5f5f5;
        --surface-color: #ffffff;
        --text-primary: #212121;
        --text-secondary: #757575;
        --shadow-color: rgba(0, 0, 0, 0.12);
        --accent-color: #F7C873;
        --success-color: #4CAF50;
        --danger-color: #E53935;
        --border-radius: 16px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: var(--background-color);
        margin: 0;
        padding: 0;
    }

    .professional-container {
        min-height: 100vh;
        padding: 0;
        margin-top: 80px; /* Add top margin to avoid header overlap */
        position: relative;
        z-index: 1; /* Ensure content stays below header */
    }

    .professional-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
        padding: 3rem 2rem 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-color) 0 4px 20px;
    }

    .professional-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        opacity: 0.3;
    }

    .professional-header .content {
        position: relative;
        z-index: 2;
        text-align: center;
        max-width: 1200px;
        margin: 0 auto;
    }

    .professional-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .professional-header .subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .earnings-highlight {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: var(--primary-color);
        padding: 1rem 2rem;
        border-radius: var(--border-radius);
        font-weight: 700;
        font-size: 1.125rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-color) 0 4px 15px;
    }

    .main-content {
        max-width: 1200px;
        margin: -1rem auto 0;
        padding: 0 2rem 4rem;
        position: relative;
        z-index: 2;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-color) 0 2px 8px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-color) 0 8px 25px;
        border-color: var(--secondary-color);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--surface-color);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #212121 !important;
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 1rem;
        color: #757575 !important;
        font-weight: 500;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #212121 !important;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--secondary-color);
    }

    .withdrawal-card {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-color) 0 2px 8px;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #212121 !important;
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--surface-color);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .btn {
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
        color: #ffffff !important;
        box-shadow: var(--shadow-color) 0 4px 15px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-color) 0 6px 20px;
    }

    .btn-secondary {
        background: #e0e0e0;
        color: var(--text-primary);
    }

    .btn-secondary:hover {
        background: var(--text-secondary);
        color: var(--surface-color);
    }

    .earnings-breakdown {
        background: var(--surface-color);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-color) 0 2px 8px;
        margin-bottom: 2rem;
    }

    .breakdown-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .breakdown-item {
        padding: 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(231, 165, 27, 0.05) 0%, rgba(247, 200, 115, 0.02) 100%);
        border: 1px solid rgba(231, 165, 27, 0.1);
    }

    .breakdown-label {
        font-size: 0.875rem;
        color: #757575 !important;
        margin-bottom: 0.5rem;
    }

    .breakdown-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212121 !important;
    }

    .commission-badge {
        background: rgba(231, 165, 27, 0.1);
        color: var(--secondary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }

    .withdrawal-info {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.05) 0%, rgba(76, 175, 80, 0.02) 100%);
        border: 1px solid rgba(76, 175, 80, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .withdrawal-info h4 {
        color: var(--success-color);
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .withdrawal-info p {
        color: #757575 !important;
        margin: 0;
        font-size: 0.875rem;
    }

    .text-muted {
        color: #757575 !important;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .professional-container {
            margin-top: 70px; /* Adjust for mobile header */
        }
        
        .professional-header {
            padding: 2rem 1rem;
        }
        
        .professional-header h1 {
            font-size: 2.5rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .main-content {
            padding: 0 1rem 2rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .breakdown-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="professional-container">
    <!-- Header Section -->
    <div class="professional-header">
        <div class="content">
            <h1>
                <i class="fas fa-wallet"></i>
                Doctor Wallet
            </h1>
            <p class="subtitle">Manage your earnings and withdrawals</p>
            <div class="earnings-highlight">
                <i class="fas fa-rupee-sign"></i>
                Available Balance: ₹{{ number_format($availableBalance, 2) }}
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">₹{{ number_format($totalEarnings, 2) }}</div>
                <div class="stat-label">Total Earnings</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--success-color), #66BB6A);">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
                <div class="stat-value">₹{{ number_format($availableBalance, 2) }}</div>
                <div class="stat-label">Available Balance</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--danger-color), #EF5350);">
                        <i class="fas fa-download"></i>
                    </div>
                </div>
                <div class="stat-value">₹{{ number_format($withdrawnAmount, 2) }}</div>
                <div class="stat-label">Total Withdrawn</div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon" style="background: linear-gradient(135deg, var(--primary-color), #5A6067);">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">₹{{ number_format($minimumWithdrawal, 2) }}</div>
                <div class="stat-label">Minimum Withdrawal</div>
            </div>
        </div>

        <!-- Earnings Breakdown -->
        <div class="earnings-breakdown">
            <h2 class="section-title">
                <i class="fas fa-chart-pie"></i>
                Earnings Breakdown
            </h2>
            
            <div class="breakdown-grid">
                <div class="breakdown-item">
                    <div class="breakdown-label">Registration Earnings</div>
                    <div class="breakdown-value">
                        ₹{{ number_format($earningsBreakdown['registration_earnings'], 2) }}
                        <span class="commission-badge">{{ $registrationCommission }}% commission deducted</span>
                    </div>
                    <small class="text-muted">
                        From {{ $earningsBreakdown['total_registrations'] }} registrations 
                        (Total: ₹{{ number_format($earningsBreakdown['registration_amount'], 2) }})
                    </small>
                </div>

                <div class="breakdown-item">
                    <div class="breakdown-label">Sponsor Earnings</div>
                    <div class="breakdown-value">
                        ₹{{ number_format($earningsBreakdown['sponsor_earnings'], 2) }}
                        <span class="commission-badge">{{ $sponsorCommission }}% commission deducted</span>
                    </div>
                    <small class="text-muted">
                        From {{ $earningsBreakdown['total_sponsors'] }} sponsors 
                        (Total: ₹{{ number_format($earningsBreakdown['sponsor_amount'], 2) }})
                    </small>
                </div>

                <div class="breakdown-item">
                    <div class="breakdown-label">Total Commission Deducted</div>
                    <div class="breakdown-value">
                        ₹{{ number_format($earningsBreakdown['registration_commission'] + $earningsBreakdown['sponsor_commission'], 2) }}
                    </div>
                    <small class="text-muted">Platform commission</small>
                </div>
            </div>
        </div>

        <!-- Withdrawal Section -->
        <div class="withdrawal-card">
            <h2 class="section-title">
                <i class="fas fa-download"></i>
                Withdraw Funds
            </h2>

            <div class="withdrawal-info">
                <h4><i class="fas fa-info-circle"></i> Withdrawal Information</h4>
                <p>• Minimum withdrawal amount: ₹{{ number_format($minimumWithdrawal, 2) }}</p>
                <p>• Withdrawals are processed within 24 hours</p>
                <p>• You will receive a notification once the amount is credited to your bank account</p>
            </div>

            @if($availableBalance >= $minimumWithdrawal)
                <form id="withdrawalForm">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Withdrawal Amount (₹)</label>
                        <input type="number" id="amount" name="amount" min="{{ $minimumWithdrawal }}" max="{{ $availableBalance }}" step="0.01" required>
                        <small class="text-muted">Available: ₹{{ number_format($availableBalance, 2) }}</small>
                    </div>

                    <div class="form-group">
                        <label for="bank_name">Bank Name</label>
                        <input type="text" id="bank_name" name="bank_name" value="{{ $doctor->bank_name ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="account_holder_name">Account Holder Name</label>
                        <input type="text" id="account_holder_name" name="account_holder_name" value="{{ $doctor->account_holder_name ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="account_number">Account Number</label>
                        <input type="text" id="account_number" name="account_number" value="{{ $doctor->account_number ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="ifsc_code">IFSC Code</label>
                        <input type="text" id="ifsc_code" name="ifsc_code" value="{{ $doctor->ifsc_code ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="account_holder_name">Account Holder Name</label>
                        <input type="text" id="account_holder_name" name="account_holder_name" value="{{ $doctor->account_holder_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-download"></i>
                        Submit Withdrawal Request
                    </button>
                </form>
            @else
                <div class="withdrawal-info" style="background: linear-gradient(135deg, rgba(229, 57, 53, 0.05) 0%, rgba(229, 57, 53, 0.02) 100%); border-color: rgba(229, 57, 53, 0.2);">
                    <h4 style="color: var(--danger-color);"><i class="fas fa-exclamation-triangle"></i> Insufficient Balance</h4>
                    <p>Your current balance (₹{{ number_format($availableBalance, 2) }}) is below the minimum withdrawal amount of ₹{{ number_format($minimumWithdrawal, 2) }}.</p>
                    <p>Continue earning from campaigns and sponsors to reach the minimum withdrawal threshold.</p>
                </div>
            @endif
        </div>

        <!-- Withdrawal History Section -->
        <div class="withdrawal-card">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h3>Withdrawal History</h3>
            </div>
            
            @if($recentWithdrawals && $recentWithdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Bank Details</th>
                                <th>Order ID</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentWithdrawals as $withdrawal)
                                <tr>
                                    <td>
                                        <div class="date-info">
                                            <div class="date">{{ $withdrawal['created_at']->format('M d, Y') }}</div>
                                            <div class="time">{{ $withdrawal['created_at']->format('h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="amount-info">
                                            <div class="amount">₹{{ number_format($withdrawal['amount'], 2) }}</div>
                                            <div class="type">
                                                @if($withdrawal['status'] === 'completed' || $withdrawal['status'] === 'success')
                                                    Transfer Completed
                                                @elseif($withdrawal['status'] === 'processing')
                                                    Transfer Processing
                                                @else
                                                    Transfer Request
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bank-details">
                                            <div class="bank-name">{{ $withdrawal['bank_name'] }}</div>
                                            <div class="account-info">
                                                <span class="account">****{{ substr($withdrawal['account_number'], -4) }}</span>
                                                <span class="ifsc">{{ $withdrawal['ifsc_code'] }}</span>
                                            </div>
                                            <div class="holder-name">{{ $withdrawal['account_holder_name'] }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-info">
                                            @if($withdrawal['order_id'])
                                                <div class="order-id">{{ substr($withdrawal['order_id'], 0, 20) }}...</div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                            @if($withdrawal['payment_id'])
                                                <div class="payment-id">{{ substr($withdrawal['payment_id'], 0, 15) }}...</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $withdrawal['status'] }}">
                                            @switch($withdrawal['status'])
                                                @case('pending')
                                                    <i class="fas fa-clock"></i> Pending
                                                    @break
                                                @case('processing')
                                                    <i class="fas fa-cog fa-spin"></i> Processing
                                                    @break
                                                @case('completed')
                                                @case('success')
                                                    <i class="fas fa-check-circle"></i> Completed
                                                    @break
                                                @case('failed')
                                                    <i class="fas fa-times-circle"></i> Failed
                                                    @break
                                                @default
                                                    <i class="fas fa-question-circle"></i> {{ ucfirst($withdrawal['status']) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($withdrawal['receipt_number'])
                                                <button class="btn-action btn-receipt" onclick="downloadReceipt('{{ $withdrawal['receipt_number'] }}')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                            <button class="btn-action btn-details" onclick="viewDetails({{ json_encode($withdrawal) }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h4>No Withdrawal History</h4>
                    <p>You haven't made any withdrawal requests yet. Once you make your first withdrawal, it will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--surface-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-color) 0 2px 12px;
    }
    
    .modern-table thead {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4a5259 100%);
        color: var(--surface-color);
    }
    
    .modern-table th,
    .modern-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .modern-table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modern-table tbody tr:hover {
        background: #f8f9fa;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }
    
    .date-info .date {
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .date-info .time {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }
    
    .amount-info .amount {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--danger-color);
    }
    
    .amount-info .type {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
    }
    
    .bank-details .bank-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }
    
    .bank-details .account-info {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 0.25rem;
    }
    
    .bank-details .account-info .account {
        background: #f0f0f0;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        margin-right: 0.5rem;
    }
    
    .bank-details .holder-name {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-style: italic;
    }
    
    .order-info .order-id {
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        color: var(--text-primary);
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
    }
    
    .order-info .payment-id {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #f57c00;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .status-processing {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
        border: 1px solid rgba(33, 150, 243, 0.3);
    }
    
    .status-completed,
    .status-success {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
        border: 1px solid rgba(76, 175, 80, 0.3);
    }
    
    .status-failed {
        background: rgba(244, 67, 54, 0.1);
        color: #d32f2f;
        border: 1px solid rgba(244, 67, 54, 0.3);
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-action {
        width: 2.5rem;
        height: 2.5rem;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }
    
    .btn-receipt {
        background: rgba(76, 175, 80, 0.1);
        color: #388e3c;
    }
    
    .btn-receipt:hover {
        background: #388e3c;
        color: white;
        transform: scale(1.1);
    }
    
    .btn-details {
        background: rgba(33, 150, 243, 0.1);
        color: #1976d2;
    }
    
    .btn-details:hover {
        background: #1976d2;
        color: white;
        transform: scale(1.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }
    
    .table-responsive {
        overflow-x: auto;
        margin: 1rem 0;
        -webkit-overflow-scrolling: touch;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-color) 0 2px 8px;
    }
    
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: var(--primary-dark);
    }
    
    @media (max-width: 768px) {
        .modern-table {
            min-width: 800px; /* Force horizontal scroll on mobile */
        }
        
        .modern-table th,
        .modern-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        
        .bank-details .bank-name {
            font-size: 0.875rem;
        }
        
        .action-buttons {
            flex-direction: row;
            justify-content: center;
        }
        
        .btn-action {
            min-width: 2rem;
            min-height: 2rem;
        }
        
        .date-info,
        .amount-info,
        .bank-details,
        .order-info {
            min-width: 120px;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }
    }
</style>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    // Handle withdrawal form submission
    $('#withdrawalForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Withdrawal',
            text: `Are you sure you want to withdraw ₹${$('#amount').val()}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#E7A51B',
            cancelButtonColor: '#757575',
            confirmButtonText: 'Yes, Withdraw',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we process your withdrawal request.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit withdrawal request
                $.ajax({
                    url: '{{ route("doctor.wallet.withdraw") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#E7A51B'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#E7A51B'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonColor: '#E7A51B'
                        });
                    }
                });
            }
        });
    });
    
    // Auto-calculate maximum withdrawal
    $('#amount').on('input', function() {
        let amount = parseFloat($(this).val());
        let maxAmount = {{ $availableBalance }};
        
        if (amount > maxAmount) {
            $(this).val(maxAmount.toFixed(2));
            
            Swal.fire({
                title: 'Maximum Amount Exceeded',
                text: `Maximum available balance is ₹${maxAmount.toFixed(2)}`,
                icon: 'warning',
                confirmButtonColor: '#E7A51B',
                timer: 3000
            });
        }
    });
});

// Function to view withdrawal details
function viewDetails(withdrawal) {
    let bankDetails = `
        <div style="text-align: left; margin: 1rem 0;">
            <p><strong>Bank Name:</strong> ${withdrawal.bank_name}</p>
            <p><strong>Account Number:</strong> ${withdrawal.account_number}</p>
            <p><strong>IFSC Code:</strong> ${withdrawal.ifsc_code}</p>
            <p><strong>Account Holder:</strong> ${withdrawal.account_holder_name}</p>
            ${withdrawal.order_id ? `<p><strong>Order ID:</strong> ${withdrawal.order_id}</p>` : ''}
            ${withdrawal.payment_id ? `<p><strong>Payment ID:</strong> ${withdrawal.payment_id}</p>` : ''}
            ${withdrawal.receipt_number ? `<p><strong>Receipt:</strong> ${withdrawal.receipt_number}</p>` : ''}
        </div>
    `;
    
    Swal.fire({
        title: `Withdrawal Details - ₹${Number(withdrawal.amount).toLocaleString()}`,
        html: bankDetails,
        icon: 'info',
        confirmButtonColor: '#E7A51B',
        confirmButtonText: 'Close'
    });
}

// Function to download receipt
function downloadReceipt(receiptNumber) {
    Swal.fire({
        title: 'Download Receipt',
        text: `Receipt Number: ${receiptNumber}`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#E7A51B',
        cancelButtonColor: '#757575',
        confirmButtonText: 'Download',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you can implement actual receipt download functionality
            // For now, we'll show a message
            Swal.fire({
                title: 'Receipt Download',
                text: 'Receipt download functionality will be implemented soon.',
                icon: 'info',
                confirmButtonColor: '#E7A51B'
            });
        }
    });
}
</script>
@endpush
