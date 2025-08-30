@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="login-card" role="main" aria-label="Payment Failed" style="max-width: 600px;">
    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 50%; margin-bottom: 15px;">
            <i class="fas fa-times" style="color: white; font-size: 32px;"></i>
        </div>
        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 50px; width: auto; margin-bottom: 10px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <h2 style="color: #667eea; margin: 0; font-size: 20px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                FreeDoctor
            </h2>
        </div>
    </div>
    
    <h1 class="title" style="color: #dc3545;">Payment Failed</h1>
    <p class="subtitle">There was an issue processing your payment</p>

    <div class="tabs" role="tablist" aria-label="Payment Failed">
        <button class="tab active" role="tab" aria-selected="true" aria-controls="failure-panel" id="failure-tab" tabindex="0" style="background: linear-gradient(135deg, #dc3545, #c82333); border: none;">Payment Issues</button>
    </div>

    <!-- Error Information -->
    <div style="background: linear-gradient(135deg, #f8d7da, #f5c6cb); border: 1px solid #f5c6cb; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #721c24;">
            <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
            Payment Could Not Be Processed
        </h3>
        
        <div style="margin-bottom: 15px;">
            <p style="margin: 0; font-size: 14px; color: #721c24; line-height: 1.5;">
                Your payment was not successful. This could be due to:
            </p>
        </div>
        
        <ul style="margin: 0; padding-left: 20px; color: #721c24; font-size: 14px;">
            <li style="margin-bottom: 5px;">Insufficient funds in your account</li>
            <li style="margin-bottom: 5px;">Card verification failed</li>
            <li style="margin-bottom: 5px;">Network connectivity issues</li>
            <li style="margin-bottom: 5px;">Bank authorization declined</li>
            <li>Payment gateway timeout</li>
        </ul>
    </div>

    <!-- Payment Details -->
    @if($payment)
    <div style="background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; margin-bottom: 25px;">
        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #333;">Payment Details</h3>
        
        <div style="display: grid; gap: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #dee2e6;">
                <span style="color: #666; font-size: 14px;">Amount</span>
                <span style="font-weight: 600; font-size: 16px;">₹{{ number_format($payment->amount, 2) }}</span>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 8px; border-bottom: 1px solid #dee2e6;">
                <span style="color: #666; font-size: 14px;">Status</span>
                <span style="display: inline-flex; align-items: center; padding: 4px 12px; background: #f5c6cb; color: #721c24; border-radius: 15px; font-size: 12px; font-weight: 500;">
                    <i class="fas fa-times-circle" style="margin-right: 4px;"></i>
                    {{ ucfirst($payment->payment_status) }}
                </span>
            </div>
            
            @if($payment->order_id)
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #666; font-size: 14px;">Reference ID</span>
                <span style="font-weight: 500; font-size: 12px; font-family: monospace;">{{ $payment->order_id }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-6">
            <a href="{{ route('doctor.payment.form', $payment->doctor->id ?? 1) }}" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #28a745, #20c997); border: none; padding: 12px; font-weight: 600;">
                <i class="fas fa-redo-alt" style="margin-right: 6px;"></i>
                Try Again
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('doctor.login') }}" class="btn btn-secondary btn-block" style="padding: 12px; font-weight: 500;">
                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                Back to Login
            </a>
        </div>
    </div>

    <!-- Support Information -->
    <div style="background: linear-gradient(135deg, #e3f2fd, #f0f8ff); border: 1px solid #bbdefb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
        <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #1976d2;">Need Help?</h3>
        
        <p style="margin: 0 0 15px 0; font-size: 14px; color: #1976d2; line-height: 1.5;">
            If you continue to experience payment issues, please contact our support team with the reference ID above.
        </p>
        
        <div style="display: flex; flex-wrap: wrap; gap: 15px; font-size: 14px;">
            <a href="mailto:support@freedoctor.com" style="color: #1976d2; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-envelope"></i>
                support@freedoctor.com
            </a>
            <a href="tel:+911234567890" style="color: #1976d2; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-phone"></i>
                +91 12345 67890
            </a>
            <span style="color: #1976d2; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-clock"></i>
                24/7 Support
            </span>
        </div>
    </div>

    <!-- Tips -->
    <div style="text-align: center;">
        <h4 style="margin: 0 0 10px 0; font-size: 14px; font-weight: 600; color: #333;">Quick Tips</h4>
        <div style="display: grid; gap: 8px; text-align: left; font-size: 13px; color: #666;">
            <div>• Check your internet connection and try again</div>
            <div>• Ensure your card has sufficient balance</div>
            <div>• Try using a different payment method</div>
            <div>• Clear browser cache and cookies</div>
        </div>
    </div>
</div>
@endsection
