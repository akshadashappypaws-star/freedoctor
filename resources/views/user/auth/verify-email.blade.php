@extends('layouts.app')

@section('title', 'Verify Email')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
    }

    .email-verification-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .email-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        width: 100%;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .email-header {
        background: linear-gradient(135deg, #383F45 0%, #E7A51B 100%);
        color: white;
        padding: 40px 30px;
        text-align: center;
        position: relative;
    }

    .email-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><pattern id="mail-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="20" fill="url(%23mail-pattern)"/></svg>');
        opacity: 0.3;
    }

    .mail-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }

    .mail-icon svg {
        width: 40px;
        height: 40px;
        color: #E7A51B;
    }

    .email-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .email-subtitle {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
        font-weight: 400;
    }

    .email-content {
        padding: 40px 30px;
        line-height: 1.6;
    }

    .user-email {
        background: #f8f9fa;
        border: 2px solid #E7A51B;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        margin: 25px 0;
        font-weight: 600;
        color: #383F45;
        word-break: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
    }

    .verification-message {
        font-size: 16px;
        color: #495057;
        margin-bottom: 30px;
    }

    .verification-steps {
        background: #f8f9fa;
        border-left: 4px solid #E7A51B;
        padding: 20px;
        margin: 25px 0;
        border-radius: 0 8px 8px 0;
    }

    .verification-steps h4 {
        color: #383F45;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .verification-steps ol {
        margin: 0;
        padding-left: 20px;
    }

    .verification-steps li {
        margin-bottom: 8px;
        color: #6c757d;
    }

    .alert-custom {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-custom .check-icon {
        width: 24px;
        height: 24px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-custom .check-icon svg {
        width: 14px;
        height: 14px;
        color: white;
    }

    .resend-section {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 20px;
        margin: 25px 0;
        text-align: center;
    }

    .resend-text {
        color: #856404;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .btn-resend {
        background: linear-gradient(135deg, #E7A51B, #F7C873);
        color: #383F45;
        border: 2px solid #383F45;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
        min-width: 200px;
    }

    .btn-resend:hover {
        background: linear-gradient(135deg, #383F45, #495057);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 63, 69, 0.3);
    }

    .btn-resend:active {
        transform: translateY(0);
    }

    .btn-resend:disabled {
        opacity: 0.7 !important;
        cursor: not-allowed !important;
        transform: none !important;
        box-shadow: none !important;
    }

    .email-footer {
        background: #f8f9fa;
        padding: 25px 30px;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }

    .footer-text {
        color: #6c757d;
        font-size: 14px;
        margin: 0;
    }

    .support-info {
        margin-top: 15px;
    }

    .support-link {
        color: #E7A51B;
        text-decoration: none;
        font-weight: 600;
        margin: 0 10px;
    }

    .support-link:hover {
        color: #383F45;
        text-decoration: underline;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        color: #6c757d;
        text-decoration: none;
        margin-bottom: 20px;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    .back-link:hover {
        color: #383F45;
        text-decoration: none;
    }

    .back-link svg {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .email-verification-container {
            padding: 15px;
        }
        
        .email-content {
            padding: 30px 20px;
        }
        
        .email-header {
            padding: 30px 20px;
        }
        
        .email-title {
            font-size: 24px;
        }
        
        .email-footer {
            padding: 20px;
        }

        .user-email {
            font-size: 14px;
            padding: 12px;
        }

        .btn-resend {
            padding: 10px 20px;
            font-size: 13px;
            min-width: auto;
            width: 100%;
        }

        .verification-steps {
            padding: 15px;
        }

        .verification-steps h4 {
            font-size: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="email-verification-container">
    <div class="email-card">
        <!-- Email Header -->
        <div class="email-header">
            <div class="mail-icon">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
            </div>
            <h1 class="email-title">Email Verification Required</h1>
            <p class="email-subtitle">Please verify your email address to continue</p>
        </div>

        <!-- Email Content -->
        <div class="email-content">
            <a href="{{ route('user.login') }}" class="back-link">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Login
            </a>

            @if (session('status') == 'verification-link-sent')
                <div class="alert-custom">
                    <div class="check-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <div>
                        <strong>Verification email sent!</strong><br>
                        <small>A fresh verification link has been sent to your email address.</small>
                    </div>
                </div>
            @endif

            <div class="verification-message">
                <p><strong>Hello!</strong></p>
                <p>Thank you for registering with FreeDoctor. Before you can access your account, please verify your email address.</p>
                
                <p>We've sent a verification email to:</p>
                <div class="user-email">{{ auth('user')->user()->email }}</div>
            </div>

            <div class="verification-steps">
                <h4>📧 Verification Steps:</h4>
                <ol>
                    <li>Check your email inbox for our verification message</li>
                    <li>Click the "Verify Email Address" button in the email</li>
                    <li>You'll be automatically redirected to your dashboard</li>
                </ol>
            </div>

            <div class="resend-section">
                <p class="resend-text">
                    <strong>Didn't receive the email?</strong><br>
                    Check your spam/junk folder or request a new verification email below.
                </p>
                
                <form method="POST" action="{{ route('user.verification.send') }}" style="display: inline;" id="resendForm">
                    @csrf
                    <button type="submit" class="btn-resend" id="resendBtn">
                        🔄 Send New Verification Email
                    </button>
                </form>
            </div>

            <div class="verification-message">
                <p><small><strong>Note:</strong> This link will expire in 60 minutes for security purposes. If you're having trouble, please contact our support team.</small></p>
            </div>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p class="footer-text">
                <strong>FreeDoctor Healthcare</strong><br>
                Your Trusted Healthcare Partner
            </p>
            <div class="support-info">
                <a href="mailto:support@freedoctor.com" class="support-link">Email Support</a>
                <a href="tel:+919876543210" class="support-link">Call Support</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.getElementById('resendForm');
    const resendBtn = document.getElementById('resendBtn');
    
    if (resendForm && resendBtn) {
        resendForm.addEventListener('submit', function() {
            // Disable button and show loading state
            resendBtn.disabled = true;
            resendBtn.innerHTML = '⏳ Sending...';
            resendBtn.style.opacity = '0.7';
            resendBtn.style.cursor = 'not-allowed';
            
            // Re-enable after 5 seconds in case of issues
            setTimeout(function() {
                resendBtn.disabled = false;
                resendBtn.innerHTML = '🔄 Send New Verification Email';
                resendBtn.style.opacity = '1';
                resendBtn.style.cursor = 'pointer';
            }, 5000);
        });
    }
});
</script>
@endsection
