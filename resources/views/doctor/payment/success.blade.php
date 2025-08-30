@extends('layouts.app')

@section('title', 'Payment Successful')

@section('content')
<div class="login-card" role="main" aria-label="Payment Success" style="max-width: 600px;">
    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; margin-bottom: 15px; animation: pulse 2s infinite;">
            <i class="fas fa-check" style="color: white; font-size: 32px;"></i>
        </div>
        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 50px; width: auto; margin-bottom: 10px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <h2 style="color: #667eea; margin: 0; font-size: 20px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                FreeDoctor
            </h2>
        </div>
    </div>
    
    <h1 class="title" style="color: #28a745;">Payment Successful!</h1>
    <p class="subtitle">Your doctor registration has been processed</p>

    <div class="tabs" role="tablist" aria-label="Payment Confirmation">
        <button class="tab active" role="tab" aria-selected="true" aria-controls="success-panel" id="success-tab" tabindex="0" style="background: linear-gradient(135deg, #28a745, #20c997); border: none;">Payment Complete</button>
    </div>

    <!-- Payment Summary -->
    <div style="background: linear-gradient(135deg, #e8f5e8, #f0f8f0); border-radius: 8px; padding: 20px; margin-bottom: 25px; border: 1px solid #d4edda;">
        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #333;">Payment Summary</h3>
        
        <div style="display: grid; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #d4edda;">
                <span style="color: #666; font-size: 14px;">Amount Paid</span>
                <span style="font-weight: 600; color: #28a745; font-size: 16px;">₹{{ number_format($payment->amount, 2) }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #d4edda;">
                <span style="color: #666; font-size: 14px;">Payment Date</span>
                <span style="font-weight: 500; font-size: 14px;">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : 'Just now' }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #d4edda;">
                <span style="color: #666; font-size: 14px;">Status</span>
                <span style="display: inline-flex; align-items: center; padding: 4px 12px; background: #d4edda; color: #155724; border-radius: 15px; font-size: 12px; font-weight: 500;">
                    <i class="fas fa-check-circle" style="margin-right: 4px;"></i>
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </div>
            
            @if($payment->payment_id)
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #d4edda;">
                <span style="color: #666; font-size: 14px;">Payment ID</span>
                <span style="font-weight: 500; font-size: 12px; font-family: monospace;">{{ $payment->payment_id }}</span>
            </div>
            @endif
            
            @if($payment->receipt_number)
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #666; font-size: 14px;">Receipt Number</span>
                <span style="font-weight: 500; font-size: 14px;">{{ $payment->receipt_number }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Next Steps -->
    <div style="margin-bottom: 25px;">
        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #333;">What's Next?</h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 24px; height: 24px; background: #667eea; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0;">1</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Admin Review</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">Our team will review your profile and payment within 24 hours</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 24px; height: 24px; background: #667eea; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0;">2</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Account Activation</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">You'll receive an email once your account is approved</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="width: 24px; height: 24px; background: #667eea; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; flex-shrink: 0;">3</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #333;">Start Creating Camps</h4>
                    <p style="margin: 0; font-size: 13px; color: #666; line-height: 1.4;">Access your dashboard and begin organizing medical camps</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Notification -->
    <div style="background: linear-gradient(135deg, #e3f2fd, #f0f8ff); border: 1px solid #bbdefb; border-radius: 8px; padding: 15px; margin-bottom: 25px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-envelope" style="color: #1976d2; font-size: 16px;"></i>
            <span style="color: #1976d2; font-size: 14px; font-weight: 500;">Confirmation email sent to {{ $payment->doctor->email }}</span>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-6">
            <a href="{{ route('doctor.login') }}" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; padding: 12px; font-weight: 600;">
                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i>
                Go to Login
            </a>
        </div>
        <div class="col-6">
            <button onclick="window.print()" class="btn btn-secondary btn-block" style="padding: 12px; font-weight: 500;">
                <i class="fas fa-print" style="margin-right: 6px;"></i>
                Print Receipt
            </button>
        </div>
    </div>

    <!-- Support Info -->
    <div style="text-align: center;">
        <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">
            Need help? Contact our support team
        </p>
        <div style="display: flex; align-items: center; justify-content: center; gap: 20px; font-size: 12px;">
            <a href="mailto:support@freedoctor.com" style="color: #667eea; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-envelope"></i>
                support@freedoctor.com
            </a>
            <a href="tel:+911234567890" style="color: #667eea; text-decoration: none; display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-phone"></i>
                +91 12345 67890
            </a>
        </div>
    </div>
</div>

<style>
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@media print {
    body * {
        visibility: hidden;
    }
    .login-card, .login-card * {
        visibility: visible;
    }
    .login-card {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none;
        margin: 0;
        padding: 20px;
    }
}
</style>
@endsection
