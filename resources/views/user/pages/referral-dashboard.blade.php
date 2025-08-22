@extends('../user.master')

@section('title', 'Referral Dashboard - FreeDoctor')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">
<style>
    :root {
        --primary-color: #E7A51B;
        --primary-dark: #c5901a;
        --secondary-color: #263345;
        --accent-color: #FFFFFF;
        --text-primary: #263345;
        --text-secondary: #6c7b8a;
        --border-color: #e5e7eb;
        --shadow-light: 0 2px 8px rgba(38, 51, 69, 0.08);
        --shadow-medium: 0 4px 20px rgba(38, 51, 69, 0.12);
        --shadow-heavy: 0 8px 30px rgba(38, 51, 69, 0.16);
        --gradient-primary: linear-gradient(135deg, #E7A51B 0%, #c5901a 100%);
        --gradient-secondary: linear-gradient(135deg, #263345 0%, #384553 100%);
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 20%, #e2e8f0 100%);
        margin: 0;
        padding: 0;
    }

    .professional-container {
        min-height: 100vh;
        padding: 0;
    }
    

    .professional-header {
        background: var(--gradient-secondary);
        color: var(--accent-color);
        padding: 4rem 2rem 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-medium);
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
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .professional-header .subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .earnings-highlight {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.125rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(231, 165, 27, 0.3);
    }

    .professional-card {
        background: var(--accent-color);
        border-radius: 20px;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 2rem;
    }

    .professional-card:hover {
        box-shadow: var(--shadow-heavy);
        transform: translateY(-4px);
    }

    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .stat-card {
        background: var(--accent-color);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        border: 1px solid var(--border-color);
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
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow-medium);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 80px;
        height: 80px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: var(--secondary-color);
        box-shadow: 0 4px 15px rgba(231, 165, 27, 0.3);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-secondary);
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .stat-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .earning-infrastructure {
        background: var(--accent-color);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-medium);
    }

    .section-header {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 1.5rem 2rem;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-radius: 16px 16px 0 0;
        margin: -2rem -2rem 2rem -2rem;
    }

    .withdrawal-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #0ea5e9;
        border-radius: 16px;
        padding: 2rem;
        margin: 2rem 0;
        text-align: center;
    }

    .withdrawal-amount {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .minimum-threshold {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .withdraw-btn {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 1rem 2rem;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.125rem;
        box-shadow: 0 4px 15px rgba(231, 165, 27, 0.3);
    }

    .withdraw-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(231, 165, 27, 0.4);
        text-decoration: none;
        color: var(--secondary-color);
    }

    .withdraw-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .referral-table {
        background: var(--accent-color);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
    }

    .table-header {
        background: var(--gradient-secondary);
        color: var(--accent-color);
        padding: 1.5rem 2rem;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }


/* Style for the icon */
.table-header i {
    font-size: 2rem; /* Icon size */
  
}

/* Style for the text */
.table-header h3 {
    margin: 0;
    font-size: 1.5rem;
    
}

.table-header p {
    margin: 0;
    font-size: 1rem;
   
}

/* ===========================================
   Mobile view (screens smaller than 768px)
   =========================================== */
@media (max-width: 768px) {
    .table-header {
        flex-direction: column; /* Stack vertically on smaller screens */
        align-items: flex-start; /* Align text to the left */
        gap: 10px; /* Adjust spacing */
    }

    .table-header i {
        font-size: 1.8rem; /* Smaller icon on mobile */
    }

    .table-header h3 {
        font-size: 1.25rem; /* Adjust header size on mobile */
    }

    .table-header p {
        font-size: 0.9rem; /* Adjust paragraph size on mobile */
    }
}

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-medium);
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

    .professional-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px; /* Force horizontal scroll on smaller screens */
    }

    .professional-table th {
        background: rgba(38, 51, 69, 0.05);
        padding: 1.5rem 1rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        border-bottom: 2px solid var(--border-color);
    }

    .professional-table td {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .professional-table tr:hover {
        background: rgba(231, 165, 27, 0.05);
    }

    .badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-primary {
        background: rgba(231, 165, 27, 0.1);
        color: #c5901a;
        border: 1px solid rgba(231, 165, 27, 0.3);
    }

    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .btn-action {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .btn-action:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        text-decoration: none;
        color: var(--secondary-color);
    }

    /* Withdrawal Table Specific Styles */
    .withdrawal-amount {
        color: var(--danger-color) !important;
        font-weight: 700;
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
        margin-bottom: 0.25rem;
    }

    .amount-info .type {
        font-size: 0.75rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
        font-family: 'Courier New', monospace;
    }

    .bank-details .account-info .ifsc {
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    .bank-details .holder-name {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-style: italic;
    }

    .transaction-info .order-id,
    .transaction-info .payment-id,
    .transaction-info .receipt-number {
        font-family: 'Courier New', monospace;
        font-size: 0.8rem;
        color: var(--text-primary);
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0.25rem;
        display: block;
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
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-processing {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-completed,
    .status-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-failed {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action.btn-receipt,
    .btn-action.btn-details {
        width: 2.5rem;
        height: 2.5rem;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
    }

    .btn-receipt {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .btn-receipt:hover {
        background: #059669;
        color: white;
        transform: scale(1.1);
    }

    .btn-details {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .btn-details:hover {
        background: #2563eb;
        color: white;
        transform: scale(1.1);
    }

    /* Bank Details Management Styles */
    .bank-management-container {
        padding: 1.5rem;
    }

    .current-bank-details {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(16, 185, 129, 0.02) 100%);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: var(--border-radius);
        padding: 1.5rem;
    }

    .current-bank-details h4 {
        color: var(--success-color);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .bank-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .bank-info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .bank-info-item label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bank-info-item span {
        font-size: 1rem;
        color: var(--text-primary);
        font-weight: 500;
        background: white;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border-color);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: rgba(231, 165, 27, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        color: var(--primary-color);
        font-size: 3rem;
    }

    .how-it-works {
        background: var(--accent-color);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-medium);
    }

    .steps-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .step-card {
        text-align: center;
        padding: 2rem 1rem;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .step-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-light);
        border-color: var(--primary-color);
    }

    .step-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        color: var(--secondary-color);
        font-size: 1.5rem;
    }

    .step-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .step-description {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .progress-bar {
        background: #e5e7eb;
        border-radius: 10px;
        height: 12px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-fill {
        background: var(--gradient-primary);
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: var(--accent-color);
        border-radius: 20px;
        padding: 2rem;
        margin: 1rem;
        max-width: 500px;
        width: 100%;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-heavy);
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(231, 165, 27, 0.1);
        color: var(--primary-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--accent-color);
        color: var(--text-primary);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
    }

    .social-share {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .social-btn {
        flex: 1;
        padding: 0.75rem 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 600;
        color: white;
    }

    .social-btn.whatsapp {
        background: #25d366;
    }

    .social-btn.whatsapp:hover {
        background: #1da851;
    }

    .social-btn.twitter {
        background: #1da1f2;
    }

    .social-btn.twitter:hover {
        background: #0d8bd9;
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .notification {
        position: fixed;
        top: 2rem;
        right: 2rem;
        background: var(--success-color);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: var(--shadow-medium);
        z-index: 1001;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    }

    .notification.show {
        opacity: 1;
        transform: translateX(0);
    }

    @media (max-width: 768px) {
        .professional-header {
            padding: 2rem 1rem;
        }
        
        .professional-header h1 {
            font-size: 2.5rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .steps-container {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .professional-table th,
        .professional-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
            white-space: nowrap;
        }
        
        .date-info,
        .amount-info,
        .bank-details,
        .transaction-info {
            min-width: 120px;
        }
        
        .action-buttons {
            flex-direction: row;
            justify-content: center;
            gap: 0.25rem;
        }
        
        .btn-action.btn-receipt,
        .btn-action.btn-details {
            min-width: 2rem;
            min-height: 2rem;
            font-size: 0.75rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.5rem;
        }

        .withdrawal-amount {
            font-size: 2rem;
        }
        
        .table-responsive {
            margin: 0 -1rem;
        }
        
        .professional-table th,
        .professional-table td {
            padding: 1rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .social-share {
            flex-direction: column;
        }
        
        .modal-content {
            margin: 1rem;
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .professional-header h1 {
            font-size: 2rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .stat-number {
            font-size: 1.75rem;
        }
        
        .withdrawal-amount {
            font-size: 1.75rem;
        }
    }
</style>
@endpush

@section('content')
@auth('user')
<div class="professional-container">
    <!-- Professional Header -->
    <div class="professional-header">
        <div class="content">
            <h1>
                <i class="fas fa-hand-holding-usd"></i>
                Referral & Earnings Dashboard
            </h1>
            <p class="subtitle">Professional Healthcare Referral Management System</p>
            <div class="earnings-highlight">
                <i class="fas fa-trophy"></i>
                Total Lifetime Earnings: ₹{{ number_format($totalEarnings, 2) }}
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 max-w-7xl">
        <!-- Enhanced Statistics Dashboard -->
        <div class="stats-container">
            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $totalReferrals }}</div>
                <div class="stat-label">Total Referrals</div>
                <div class="stat-description">Successful healthcare registrations</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="stat-number">₹{{ number_format($totalEarnings, 2) }}</div>
                <div class="stat-label">Total Earnings</div>
                <div class="stat-description">Lifetime referral income</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="stat-number">₹{{ number_format($withdrawnAmount, 2) }}</div>
                <div class="stat-label">Withdrawn Amount</div>
                <div class="stat-description">Successfully withdrawn funds</div>
            </div>

            <div class="stat-card fade-in">
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-number">₹{{ number_format($availableBalance, 2) }}</div>
                <div class="stat-label">Available Balance</div>
                <div class="stat-description">
                    @if($canWithdraw)
                        Ready for withdrawal
                    @else
                        ₹{{ number_format($minimumWithdrawal - $availableBalance, 2) }} more needed
                    @endif
                </div>
            </div>
        </div>

        <!-- One-Click Withdrawal Section -->
        <div class="withdrawal-section fade-in">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center justify-center gap-3">
                <i class="fas fa-credit-card text-blue-600"></i>
                One-Click Withdrawal
            </h2>
            
            <div class="withdrawal-amount">₹{{ number_format($availableBalance, 2) }}</div>
            
            <div class="minimum-threshold">
                <i class="fas fa-info-circle"></i>
                Minimum Withdrawal: ₹{{ number_format($minimumWithdrawal, 2) }}
            </div>
            
            @php
                $progressToMinimum = min(100, ($availableBalance / $minimumWithdrawal) * 100);
            @endphp
            
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progressToMinimum }}%"></div>
            </div>
            
            <p class="text-gray-600 mb-4">
                @if($canWithdraw)
                    <i class="fas fa-check-circle text-green-600"></i>
                    Ready for instant withdrawal via Razorpay!
                @else
                    Progress to minimum withdrawal: {{ round($progressToMinimum) }}%
                @endif
            </p>

            @if(auth('user')->user()->bank_account_number)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-green-700">
                        <i class="fas fa-check-circle mr-1"></i>
                        Bank Account: {{ substr(auth('user')->user()->bank_account_number, -4) ? '****' . substr(auth('user')->user()->bank_account_number, -4) : 'Setup Required' }}
                        ({{ auth('user')->user()->bank_name }})
                    </p>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Demo Mode: Withdrawals are currently simulated for testing purposes
                    </p>
                </div>
                
                <button class="withdraw-btn" 
                        onclick="processOneClickWithdrawal()" 
                        {{ !$canWithdraw ? 'disabled' : '' }}>
                    <i class="fas fa-zap"></i>
                    @if($canWithdraw)
                        Instant Withdrawal (Demo)
                    @else
                        Withdrawal Unavailable
                    @endif
                </button>
            @else
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-orange-700">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Please setup your bank account details first
                    </p>
                </div>
                
                <button class="withdraw-btn" onclick="setupBankAccount()">
                    <i class="fas fa-university"></i>
                    Setup Bank Account
                </button>
            @endif

            <!-- Additional earnings info -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="text-lg font-bold text-blue-800">₹{{ number_format($totalEarnings, 2) }}</div>
                    <div class="text-sm text-blue-600">Total Earned</div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="text-lg font-bold text-green-800">₹{{ number_format($withdrawnAmount, 2) }}</div>
                    <div class="text-sm text-green-600">Already Withdrawn</div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="text-lg font-bold text-yellow-800">₹{{ number_format($availableBalance, 2) }}</div>
                    <div class="text-sm text-yellow-600">Available Now</div>
                </div>
            </div>
        </div>

        <!-- Enhanced Referral Table -->
        <div class="referral-table fade-in">
            <div class="table-header">
                <i class="fas fa-chart-bar"></i>
                Campaign Referral Performance
            </div>

            @if($referralsByCampaign->count() > 0)
            <div class="table-responsive"  style="padding-left: 20px;">
                <table class="professional-table">
                    <thead>
                        <tr>
                            <th>Campaign Details</th>
                            <th class="text-center">Referrals</th>
                            <th class="text-center">Rate per Referral</th>
                            <th class="text-center">Total Earned</th>
                       
                   
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referralsByCampaign as $campaignData)
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-800">{{ $campaignData['campaign_name'] }}</div>
                                <div class="text-sm text-gray-500">ID: {{ $campaignData['campaign_id'] }}</div>
                                <div class="text-sm text-gray-500">Healthcare Initiative</div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-primary">
                                    <i class="fas fa-users"></i>
                                    {{ $campaignData['total_referrals'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="font-semibold text-green-600">₹{{ number_format($campaignData['per_refer_cost'], 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="font-bold text-gray-800">₹{{ number_format($campaignData['total_amount'], 2) }}</span>
                            </td>
                          
                           
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">No Referral Activity Yet</h3>
                <p class="text-gray-600 mb-6">Start your healthcare referral journey and earn with every successful registration!</p>
                <a href="{{ route('user.dashboard') }}" class="btn-action">
                    <i class="fas fa-search"></i>
                    Browse Healthcare Campaigns
                </a>
            </div>
            @endif
        </div>

        <!-- Bank Details Management Section -->
        <div class="referral-table fade-in">
            <div class="table-header">
                <i class="fas fa-university"></i>
                <h3>Bank Account Details</h3>
                <p>Manage your bank account information for withdrawals</p>
            </div>
            
            <div class="bank-management-container">
                @if(Auth::guard('user')->user()->bank_account_number)
                    <!-- Display Current Bank Details -->
                    <div class="current-bank-details">
                        <h4><i class="fas fa-check-circle"></i> Current Bank Account</h4>
                        <div class="bank-info-grid">
                            <div class="bank-info-item">
                                <label>Bank Name</label>
                                <span>{{ Auth::guard('user')->user()->bank_name ?? 'Not specified' }}</span>
                            </div>
                            <div class="bank-info-item">
                                <label>Account Number</label>
                                <span>****{{ substr(Auth::guard('user')->user()->bank_account_number, -4) }}</span>
                            </div>
                            <div class="bank-info-item">
                                <label>IFSC Code</label>
                                <span>{{ Auth::guard('user')->user()->bank_ifsc_code }}</span>
                            </div>
                            <div class="bank-info-item">
                                <label>Account Holder</label>
                                <span>{{ Auth::guard('user')->user()->account_holder_name ?? Auth::guard('user')->user()->username }}</span>
                            </div>
                        </div>
                        <button class="btn-action" onclick="editBankDetails()">
                            <i class="fas fa-edit"></i>
                            Edit Bank Details
                        </button>
                    </div>
                @else
                    <!-- Add Bank Details -->
                    <div class="empty-state ">
                        <div class="empty-state-icon">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3>Add Your Bank Account Details</h3>
                        <p>You need to add your bank account information to receive withdrawal payments.</p>
                        <button class="btn-action" onclick="addBankDetails()">
                            <i class="fas fa-plus"></i>
                            Add Bank Account
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Withdrawal History Table -->
        <div class="referral-table fade-in">
            <div class="table-header">
                <i class="fas fa-credit-card"></i>
                <h3>Payment & Withdrawal History</h3>
                <p>Track your referral earnings and withdrawal requests</p>
            </div>
            
            @if($withdrawalRequests && $withdrawalRequests->count() > 0)
                <div class="table-responsive" style="padding-left: 20px;">
                    <table class="professional-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Bank Details</th>
                                <th>Transaction Info</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawalRequests as $withdrawal)
                                <tr>
                                    <td>
                                        <div class="date-info">
                                            <div class="date">{{ $withdrawal['created_at']->format('M d, Y') }}</div>
                                            <div class="time">{{ $withdrawal['created_at']->format('h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="amount-info">
                                            <div class="amount withdrawal-amount">₹{{ number_format($withdrawal['amount'], 2) }}</div>
                                            <div class="type">{{ $withdrawal['type'] }}</div>
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
                                        <div class="transaction-info">
                                            @if($withdrawal['order_id'])
                                                <div class="order-id">{{ substr($withdrawal['order_id'], 0, 20) }}...</div>
                                            @endif
                                            @if($withdrawal['payment_id'])
                                                <div class="payment-id">{{ substr($withdrawal['payment_id'], 0, 15) }}...</div>
                                            @endif
                                            @if($withdrawal['receipt_number'])
                                                <div class="receipt-number">{{ $withdrawal['receipt_number'] }}</div>
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
                                                <button class="btn-action btn-receipt" onclick="downloadWithdrawalReceipt('{{ $withdrawal['receipt_number'] }}')">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                            <button class="btn-action btn-details" onclick="viewWithdrawalDetails({{ json_encode($withdrawal) }})">
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
                    <div class="empty-state-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">No Payment History</h3>
                    <p class="text-gray-600 mb-6">Your withdrawal and payment history will appear here once you make your first withdrawal request.</p>
                    @if($availableBalance >= $minimumWithdrawal)
                        <button class="btn-action" onclick="openWithdrawalModal()">
                            <i class="fas fa-money-bill-wave"></i>
                            Make Withdrawal Request
                        </button>
                    @else
                        <div class="text-center">
                            <p class="text-sm text-gray-500">Minimum withdrawal: ₹{{ number_format($minimumWithdrawal, 2) }}</p>
                            <p class="text-sm text-gray-500">Current balance: ₹{{ number_format($availableBalance, 2) }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- How Referrals Work -->
        <div class="how-it-works fade-in">
            <div class="section-header">
                <i class="fas fa-lightbulb"></i>
                Professional Referral Infrastructure
            </div>
            
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="step-title">1. Generate Referral Link</div>
                    <div class="step-description">Get your unique tracking link for any healthcare campaign with professional analytics</div>
                </div>
                
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="step-title">2. Share & Track</div>
                    <div class="step-description">Share via social media, email, or messaging. Monitor real-time performance metrics</div>
                </div>
                
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="step-title">3. Earn on Registration</div>
                    <div class="step-description">Receive instant earnings when someone registers through your referral link</div>
                </div>
                
                <div class="step-card">
                    <div class="step-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="step-title">4. Withdraw Earnings</div>
                    <div class="step-description">Withdraw your earnings once you reach the minimum threshold of ₹1,000</div>
                </div>
            </div>
        </div>

        <!-- Earning Tips -->
        <div class="earning-infrastructure">
            <div class="section-header">
                <i class="fas fa-rocket"></i>
                Maximize Your Earning Potential
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-target text-blue-600"></i>
                        Target Healthcare Communities
                    </h4>
                    <p class="text-blue-700 text-sm">Focus on healthcare professionals, medical students, and health-conscious individuals for higher conversion rates.</p>
                </div>
                
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-share text-green-600"></i>
                        Social Media Strategy
                    </h4>
                    <p class="text-green-700 text-sm">Share on LinkedIn, Facebook groups, and healthcare forums. Use compelling healthcare statistics and benefits.</p>
                </div>
                
                <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <h4 class="font-semibold text-purple-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-clock text-purple-600"></i>
                        Optimal Timing
                    </h4>
                    <p class="text-purple-700 text-sm">Share during healthcare awareness weeks, medical conferences, or health-related events for maximum impact.</p>
                </div>
                
                <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <h4 class="font-semibold text-orange-800 mb-2 flex items-center gap-2">
                        <i class="fas fa-users text-orange-600"></i>
                        Personal Network
                    </h4>
                    <p class="text-orange-700 text-sm">Start with family, friends, and colleagues. Personal recommendations have higher trust and conversion rates.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<!-- Not authenticated - redirect to login -->
<div class="min-h-screen flex items-center justify-center">
    <div class="professional-card rounded-2xl p-8 text-center max-w-md">
        <div class="empty-state-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h3 class="text-2xl font-semibold text-gray-800 mb-2">Access Restricted</h3>
        <p class="text-gray-600 mb-6">Please log in to view your referral dashboard.</p>
        <a href="{{ route('user.login') }}" class="btn-action">
            <i class="fas fa-sign-in-alt"></i>
            Login to Continue
        </a>
    </div>
</div>
@endauth

@push('scripts')
<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
<script>
// Set global variables for PHP to JavaScript communication
const currentUserId = {{ auth('user')->id() ?? 'null' }};
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Professional Referral Dashboard Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize fade-in animations
    const fadeElements = document.querySelectorAll('.fade-in');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    fadeElements.forEach(el => observer.observe(el));

    // Update progress bar animation
    setTimeout(() => {
        const progressFills = document.querySelectorAll('.progress-fill');
        progressFills.forEach(fill => {
            fill.style.transition = 'width 2s ease-in-out';
        });
    }, 500);
});

// One-Click Withdrawal System
function processOneClickWithdrawal() {
    const availableBalance = parseFloat('{{ $availableBalance ?? 0 }}');
    const minimumWithdrawal = parseFloat('{{ $minimumWithdrawal ?? 100 }}');
    
    if (availableBalance < minimumWithdrawal) {
        Swal.fire({
            icon: 'warning',
            title: 'Insufficient Balance',
            text: `Minimum withdrawal amount is ₹${minimumWithdrawal}`,
            confirmButtonColor: '#E7A51B'
        });
        return;
    }

    // Confirm withdrawal
    Swal.fire({
        title: 'Confirm Withdrawal',
        html: `
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-4">₹${availableBalance.toFixed(2)}</div>
                <p class="text-gray-600 mb-4">Amount will be transferred to your bank account</p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-zap mr-1"></i>
                        Instant transfer via Razorpay • No fees • 24/7 processing
                    </p>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Withdraw Now',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6B7280'
    }).then((result) => {
        if (result.isConfirmed) {
            executeWithdrawal();
        }
    });
}

function executeWithdrawal() {
    // Show processing
    Swal.fire({
        title: 'Processing Withdrawal',
        html: `
            <div class="text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-green-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Processing via Razorpay...</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    // Get values
    const availableBalance = parseFloat('{{ $availableBalance ?? 0 }}');
    const withdrawalUrl = '{{ route('user.withdrawal.process') }}';
    
    console.log('Starting withdrawal process:', {
        amount: availableBalance,
        url: withdrawalUrl,
        csrfToken: csrfToken
    });

    // Use jQuery AJAX instead of fetch for better compatibility
    $.ajax({
        url: withdrawalUrl,
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            amount: availableBalance
        }),
        timeout: 30000, // 30 seconds timeout
        success: function(data) {
            console.log('Withdrawal response:', data);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Withdrawal Successful!',
                    html: `
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 mb-3">₹${data.amount}</div>
                            <p class="text-gray-600 mb-4">Successfully transferred to your account</p>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-sm text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Transaction ID: ${data.payout_id || 'N/A'}
                                </p>
                            </div>
                        </div>
                    `,
                    confirmButtonColor: '#10B981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Withdrawal Failed',
                    text: data.message || 'An error occurred during withdrawal',
                    confirmButtonColor: '#EF4444'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Withdrawal error details:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            
            let errorMessage = 'Please check your connection and try again';
            
            if (xhr.status === 419) {
                errorMessage = 'Session expired. Please refresh the page and try again.';
            } else if (xhr.status === 403) {
                errorMessage = 'Access denied. Please login again.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please try again later.';
            } else if (xhr.status === 0) {
                errorMessage = 'Network connection failed. Please check your internet connection.';
            } else if (status === 'timeout') {
                errorMessage = 'Request timed out. Please try again.';
            }
            
            // Try to parse error response
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.message) {
                    errorMessage = response.message;
                }
            } catch (e) {
                // Keep default error message
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Withdrawal Failed',
                html: `
                    <div class="text-center">
                        <p class="text-gray-600 mb-4">${errorMessage}</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Error Code: ${xhr.status} - ${status}
                            </p>
                        </div>
                    </div>
                `,
                confirmButtonColor: '#EF4444'
            });
        }
    });
}

function setupBankAccount() {
    Swal.fire({
        title: 'Setup Bank Account',
        html: `
            <form id="bankAccountForm" class="space-y-4">
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Account Holder Name
                    </label>
                    <input type="text" id="accountHolder" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Account Number
                    </label>
                    <input type="text" id="accountNumber" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        IFSC Code
                    </label>
                    <input type="text" id="ifscCode" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
                
                <div class="text-left">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name
                    </label>
                    <input type="text" id="bankName" class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Details',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3B82F6',
        preConfirm: () => {
            const accountHolder = document.getElementById('accountHolder').value;
            const accountNumber = document.getElementById('accountNumber').value;
            const ifscCode = document.getElementById('ifscCode').value;
            const bankName = document.getElementById('bankName').value;

            if (!accountHolder || !accountNumber || !ifscCode || !bankName) {
                Swal.showValidationMessage('Please fill all fields');
                return false;
            }

            return { accountHolder, accountNumber, ifscCode, bankName };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            saveBankDetails(result.value);
        }
    });
}

function saveBankDetails(details) {
    Swal.fire({
        title: 'Saving Details',
        html: '<div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>',
        showConfirmButton: false,
        allowOutsideClick: false
    });

    const saveUrl = '{{ route('user.withdrawal.account-details') }}';
    
    console.log('Saving bank details:', {
        url: saveUrl,
        details: details
    });

    // Use jQuery AJAX for bank details saving
    $.ajax({
        url: saveUrl,
        type: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        data: JSON.stringify({
            account_holder_name: details.accountHolder,
            bank_account_number: details.accountNumber,
            bank_ifsc_code: details.ifscCode,
            bank_name: details.bankName
        }),
        timeout: 30000,
        success: function(data) {
            console.log('Bank details save response:', data);
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Account Details Saved!',
                    text: 'Your bank account has been setup successfully',
                    confirmButtonColor: '#10B981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to save account details',
                    confirmButtonColor: '#EF4444'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Bank details save error:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            
            let errorMessage = 'Failed to save account details';
            
            if (xhr.status === 419) {
                errorMessage = 'Session expired. Please refresh the page and try again.';
            } else if (xhr.status === 422) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.errors) {
                        const firstError = Object.values(response.errors)[0][0];
                        errorMessage = firstError;
                    }
                } catch (e) {
                    errorMessage = 'Validation error. Please check your input.';
                }
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                confirmButtonColor: '#EF4444'
            });
        }
    });
}

// Generate Referral Link
function generateReferralLink(campaignId) {
    Swal.fire({
        title: 'Generating Referral Link',
        html: `
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Creating your unique referral link...</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    // API call to generate referral link
    setTimeout(() => {
        const referralCode = 'REF' + Math.random().toString(36).substr(2, 9).toUpperCase();
        const referralLink = `${window.location.origin}/register?ref=${referralCode}&campaign=${campaignId}`;

        Swal.fire({
            title: 'Your Referral Link',
            html: `
                <div class="referral-link-container">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-link mr-1"></i>
                            Your Unique Referral Link:
                        </div>
                        <div class="bg-white border border-gray-300 rounded p-3 font-mono text-sm break-all" id="referralLinkText">
                            ${referralLink}
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <button onclick="copyReferralLink('${referralLink}')" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-copy mr-2"></i>
                            Copy Link
                        </button>
                        <button onclick="shareReferralLink('${referralLink}')" class="flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-share-alt mr-2"></i>
                            Share
                        </button>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-800 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Sharing Tips
                        </h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Share on WhatsApp, Facebook, or social media</li>
                            <li>• Include a personal message about healthcare benefits</li>
                            <li>• Target health-conscious individuals</li>
                            <li>• Track your referral performance here</li>
                        </ul>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px'
        });
    }, 1500);
}

// Copy Referral Link to Clipboard
function copyReferralLink(link) {
    navigator.clipboard.writeText(link).then(() => {
        // Show success feedback
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
        }, 2000);
        
        showToast('Link copied to clipboard!', 'success');
    }).catch(() => {
        showToast('Failed to copy link', 'error');
    });
}

// Share Referral Link
function shareReferralLink(link) {
    if (navigator.share) {
        navigator.share({
            title: 'Join FreeDoctor Medical Camps ',
            text: 'Get professional healthcare services and consultations. Register now and be part of our growing healthcare community!',
            url: link
        });
    } else {
        // Fallback - open share options
        const message = encodeURIComponent(`Join FreeDoctor Medical Camps  and get professional healthcare services! Register here: ${link}`);
        
        Swal.fire({
            title: 'Share Your Referral Link',
            html: `
                <div class="share-options grid grid-cols-2 gap-3">
                    <a href="https://wa.me/?text=${message}" target="_blank" class="flex items-center justify-center px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fab fa-whatsapp mr-2 text-xl"></i>
                        WhatsApp
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(link)}" target="_blank" class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fab fa-facebook mr-2 text-xl"></i>
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=${message}" target="_blank" class="flex items-center justify-center px-4 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors">
                        <i class="fab fa-twitter mr-2 text-xl"></i>
                        Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(link)}" target="_blank" class="flex items-center justify-center px-4 py-3 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors">
                        <i class="fab fa-linkedin mr-2 text-xl"></i>
                        LinkedIn
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true
        });
    }
}

// Toast Notification Function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-600 text-white',
        error: 'bg-red-600 text-white',
        info: 'bg-blue-600 text-white'
    };
    
    toast.classList.add(...colors[type].split(' '));
    toast.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Add loading states to buttons
document.querySelectorAll('.btn-action, .withdraw-btn').forEach(button => {
    button.addEventListener('click', function() {
        if (!this.disabled) {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
            
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        }
    });
});
</script>
@endpush


@endsection

@push('scripts')
<script>
let currentReferralLink = '';
let currentReferEarning = '';

// Withdrawal Functions
function viewWithdrawalDetails(withdrawal) {
    let bankDetails = `
        <div style="text-align: left; margin: 1rem 0;">
            <p><strong>Bank Name:</strong> ${withdrawal.bank_name}</p>
            <p><strong>Account Number:</strong> ${withdrawal.account_number}</p>
            <p><strong>IFSC Code:</strong> ${withdrawal.ifsc_code}</p>
            <p><strong>Account Holder:</strong> ${withdrawal.account_holder_name}</p>
            ${withdrawal.order_id ? `<p><strong>Order ID:</strong> ${withdrawal.order_id}</p>` : ''}
            ${withdrawal.payment_id ? `<p><strong>Payment ID:</strong> ${withdrawal.payment_id}</p>` : ''}
            ${withdrawal.receipt_number ? `<p><strong>Receipt:</strong> ${withdrawal.receipt_number}</p>` : ''}
            <p><strong>Request Date:</strong> ${new Date(withdrawal.created_at).toLocaleString()}</p>
        </div>
    `;
    
    Swal.fire({
        title: `Withdrawal Details - ₹${Number(withdrawal.amount).toLocaleString()}`,
        html: bankDetails,
        icon: 'info',
        confirmButtonColor: '#E7A51B',
        confirmButtonText: 'Close',
        width: '500px'
    });
}

function downloadWithdrawalReceipt(receiptNumber) {
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

function openWithdrawalModal() {
    // This function can be used to open a withdrawal request modal
    // For now, redirect to withdrawal page
    window.location.href = '/user/withdrawal';
}

// Bank Details Management Functions
function addBankDetails() {
    showBankDetailsModal('Add Bank Account Details', {
        bank_name: '',
        bank_account_number: '',
        bank_ifsc_code: '',
        account_holder_name: '{{ Auth::guard("user")->user()->username ?? "" }}'
    });
}

function editBankDetails() {
    showBankDetailsModal('Edit Bank Account Details', {
        bank_name: '{{ Auth::guard("user")->user()->bank_name ?? "" }}',
        bank_account_number: '{{ Auth::guard("user")->user()->bank_account_number ?? "" }}',
        bank_ifsc_code: '{{ Auth::guard("user")->user()->bank_ifsc_code ?? "" }}',
        account_holder_name: '{{ Auth::guard("user")->user()->account_holder_name ?? Auth::guard("user")->user()->username ?? "" }}'
    });
}

function showBankDetailsModal(title, currentData) {
    Swal.fire({
        title: title,
        html: `
            <form id="bankDetailsForm" style="text-align: left;">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bank Name</label>
                    <input type="text" id="bank_name" name="bank_name" value="${currentData.bank_name}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.5rem;" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Number</label>
                    <input type="text" id="bank_account_number" name="bank_account_number" value="${currentData.bank_account_number}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.5rem;" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">IFSC Code</label>
                    <input type="text" id="bank_ifsc_code" name="bank_ifsc_code" value="${currentData.bank_ifsc_code}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.5rem;" required>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Account Holder Name</label>
                    <input type="text" id="account_holder_name" name="account_holder_name" value="${currentData.account_holder_name}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 0.5rem;" required>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Save Details',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#E7A51B',
        cancelButtonColor: '#757575',
        width: '500px',
        preConfirm: () => {
            const form = document.getElementById('bankDetailsForm');
            const formData = new FormData(form);
            
            // Validate required fields
            if (!formData.get('bank_name') || !formData.get('bank_account_number') || 
                !formData.get('bank_ifsc_code') || !formData.get('account_holder_name')) {
                Swal.showValidationMessage('Please fill all required fields');
                return false;
            }
            
            return {
                bank_name: formData.get('bank_name'),
                bank_account_number: formData.get('bank_account_number'),
                bank_ifsc_code: formData.get('bank_ifsc_code').toUpperCase(),
                account_holder_name: formData.get('account_holder_name')
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            saveBankDetails(result.value);
        }
    });
}

function saveBankDetails(bankData) {
    // Show loading
    Swal.fire({
        title: 'Saving Bank Details',
        text: 'Please wait while we save your bank account information...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Save bank details via AJAX
    const saveUrl = '{{ route('user.withdrawal.account-details') }}';
    
    fetch(saveUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(bankData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message || 'Bank details saved successfully!',
                icon: 'success',
                confirmButtonColor: '#E7A51B'
            }).then(() => {
                location.reload(); // Refresh to show updated details
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Failed to save bank details. Please try again.',
                icon: 'error',
                confirmButtonColor: '#E7A51B'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'An error occurred while saving bank details. Please try again.',
            icon: 'error',
            confirmButtonColor: '#E7A51B'
        });
    });
}



// ========================
// Enhanced Referral System with LocalStorage
// ========================

// Store referral tracking data in localStorage
function storeReferralTracking(referralCode, campaignId) {
    const referralData = {
        referralCode: referralCode,
        campaignId: campaignId,
        userId: currentUserId,
        timestamp: Date.now(),
        clicks: 0,
        conversions: 0
    };
    
    // Store current referral
    localStorage.setItem('current_referral', JSON.stringify(referralData));
    
    // Store in referral history
    let referralHistory = JSON.parse(localStorage.getItem('referral_history') || '[]');
    referralHistory.push(referralData);
    localStorage.setItem('referral_history', JSON.stringify(referralHistory));
    
    console.log('Referral tracking stored:', referralData);
}

// Track referral link clicks
function trackReferralClick(referralCode, campaignId) {
    // Update click count in localStorage
    let currentReferral = JSON.parse(localStorage.getItem('current_referral'));
    if (currentReferral && currentReferral.referralCode === referralCode) {
        currentReferral.clicks += 1;
        localStorage.setItem('current_referral', JSON.stringify(currentReferral));
        
        // Update in history as well
        let referralHistory = JSON.parse(localStorage.getItem('referral_history') || '[]');
        const historyIndex = referralHistory.findIndex(r => r.referralCode === referralCode);
        if (historyIndex > -1) {
            referralHistory[historyIndex].clicks += 1;
            localStorage.setItem('referral_history', JSON.stringify(referralHistory));
        }
    }
    
    // Send to server for database tracking
    fetch('/api/track-referral-click', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            referral_code: referralCode,
            campaign_id: campaignId,
            click_timestamp: Date.now()
        })
    }).catch(error => console.log('Referral tracking error:', error));
}

// Track successful conversions (when someone registers after clicking your referral)
function trackReferralConversion(referralCode, campaignId, registrationId) {
    // Update conversion count in localStorage
    let currentReferral = JSON.parse(localStorage.getItem('current_referral'));
    if (currentReferral && currentReferral.referralCode === referralCode) {
        currentReferral.conversions += 1;
        localStorage.setItem('current_referral', JSON.stringify(currentReferral));
        
        // Update in history as well
        let referralHistory = JSON.parse(localStorage.getItem('referral_history') || '[]');
        const historyIndex = referralHistory.findIndex(r => r.referralCode === referralCode);
        if (historyIndex > -1) {
            referralHistory[historyIndex].conversions += 1;
            localStorage.setItem('referral_history', JSON.stringify(referralHistory));
        }
    }
    
    // Send to server
    fetch('/api/track-referral-conversion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            referral_code: referralCode,
            campaign_id: campaignId,
            registration_id: registrationId,
            conversion_timestamp: Date.now()
        })
    }).catch(error => console.log('Conversion tracking error:', error));
}

// Get referral statistics from localStorage
function getReferralStats() {
    const referralHistory = JSON.parse(localStorage.getItem('referral_history') || '[]');
    const totalClicks = referralHistory.reduce((sum, ref) => sum + (ref.clicks || 0), 0);
    const totalConversions = referralHistory.reduce((sum, ref) => sum + (ref.conversions || 0), 0);
    const conversionRate = totalClicks > 0 ? ((totalConversions / totalClicks) * 100).toFixed(2) : 0;
    
    return {
        totalReferrals: referralHistory.length,
        totalClicks: totalClicks,
        totalConversions: totalConversions,
        conversionRate: conversionRate + '%'
    };
}

// Display localStorage stats (can be called to show in UI)
function displayLocalReferralStats() {
    const stats = getReferralStats();
    console.log('Referral Statistics:', stats);
    
    // You can update UI elements here if needed
    // Example: document.getElementById('stats-display').innerHTML = JSON.stringify(stats, null, 2);
}

// Enhanced generateReferralLink function with localStorage
function generateReferralLinkEnhanced(campaignId) {
    Swal.fire({
        title: 'Generating Referral Link',
        html: `
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Creating your unique referral link with tracking...</p>
            </div>
        `,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    // Make actual API call to server to generate proper referral link
    fetch('/api/generate-referral-link', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            campaign_id: campaignId,
            user_id: currentUserId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const referralCode = data.referral_code;
            const referralLink = data.referral_link;
            
            // Store in localStorage for tracking
            storeReferralTracking(referralCode, campaignId);
            
            Swal.fire({
                title: 'Your Referral Link',
                html: `
                    <div class="referral-link-container">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-link mr-1"></i>
                                Your Unique Referral Link:
                            </div>
                            <div class="bg-white border border-gray-300 rounded p-3 font-mono text-sm break-all" id="referralLinkText">
                                ${referralLink}
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <button onclick="copyReferralLink('${referralLink}')" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-copy mr-2"></i>
                                Copy Link
                            </button>
                            <button onclick="shareReferralLink('${referralLink}')" class="flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-share-alt mr-2"></i>
                                Share
                            </button>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="font-semibold text-blue-800 mb-2">
                                <i class="fas fa-chart-line mr-1"></i>
                                Tracking Enabled
                            </h4>
                            <p class="text-sm text-blue-700">
                                This link includes advanced tracking. View your referral statistics and earnings in real-time on this dashboard.
                            </p>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <h4 class="font-semibold text-green-800 mb-2">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Sharing Tips
                            </h4>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>• Share on WhatsApp, Facebook, or social media</li>
                                <li>• Include a personal message about healthcare benefits</li>
                                <li>• Target health-conscious individuals</li>
                                <li>• Track your referral performance here</li>
                            </ul>
                        </div>
                    </div>
                `,
                width: '600px',
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#3B82F6'
            });
        } else {
            Swal.fire('Error', 'Failed to generate referral link. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error generating referral link:', error);
        Swal.fire('Error', 'An error occurred while generating your referral link.', 'error');
    });
}

// Check if user has clicked a referral link and store it
function checkReferralFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const ref = urlParams.get('ref');
    const campaign = urlParams.get('campaign');
    
    if (ref && campaign) {
        // Store referral data for when user registers
        localStorage.setItem('incoming_referral', JSON.stringify({
            referral_code: ref,
            campaign_id: campaign,
            timestamp: Date.now()
        }));
        
        // Track the click
        trackReferralClick(ref, campaign);
        
        console.log('Referral link detected and stored:', { ref, campaign });
    }
}

// Check for referral when user completes registration
function processRegistrationReferral(registrationId, campaignId) {
    const incomingReferral = JSON.parse(localStorage.getItem('incoming_referral'));
    
    if (incomingReferral && incomingReferral.campaign_id == campaignId) {
        // Track the conversion
        trackReferralConversion(incomingReferral.referral_code, campaignId, registrationId);
        
        // Clear the stored referral
        localStorage.removeItem('incoming_referral');
        
        console.log('Registration referral processed:', incomingReferral);
        
        // Show success message
        Swal.fire({
            title: 'Thank You!',
            text: 'Your registration was completed through a referral. The referrer has been credited.',
            icon: 'success',
            confirmButtonText: 'Continue'
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkReferralFromURL();
    displayLocalReferralStats();
});
</script>
@endpush

@push('styles')
<style>
.glass-card {
    background: rgba(15, 23, 42, 0.8);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-primary {
    @apply bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-2 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 inline-flex items-center;
}

.btn-primary-sm {
    @apply bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-medium py-1.5 px-3 rounded-lg transition-all duration-300 text-sm inline-flex items-center;
}

.modern-input {
    background: rgba(51, 65, 85, 0.8);
    border: 1px solid rgba(148, 163, 184, 0.3);
    backdrop-filter: blur(10px);
}

.modern-input:focus {
    background: rgba(51, 65, 85, 0.9);
    border-color: rgba(59, 130, 246, 0.5);
}
</style>
@endpush
