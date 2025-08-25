@extends('layouts.app')

@section('title', 'Verify Email')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #2C2A4C 0%, #E7A51B 100%);
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
        border-radius: 16px;
        box-shadow: 0 15px 50px rgba(44, 42, 76, 0.15);
        max-width: 500px;
        width: 100%;
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .email-header {
        background: linear-gradient(135deg, #2C2A4C 0%, #E7A51B 100%);
        color: white;
        padding: 30px 25px;
        text-align: center;
        position: relative;
    }

    .mail-icon {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .mail-icon svg {
        width: 30px;
        height: 30px;
        color: #2C2A4C;
    }

    .email-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .email-subtitle {
        font-size: 14px;
        opacity: 0.9;
        font-weight: 400;
    }

    .email-content {
        padding: 30px 25px;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        background: #f8f9fa;
        color: #2C2A4C;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .back-btn:hover {
        background: #2C2A4C;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(44, 42, 76, 0.2);
    }

    .back-btn svg {
        margin-right: 6px;
    }

    .user-email {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 2px solid #E7A51B;
        border-radius: 8px;
        padding: 12px 15px;
        text-align: center;
        margin: 15px 0;
        font-weight: 600;
        color: #2C2A4C;
        word-break: break-word;
    }

    .verification-message {
        font-size: 15px;
        color: #495057;
        line-height: 1.5;
        margin-bottom: 20px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .alert-success .check-icon {
        width: 20px;
        height: 20px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .alert-success .check-icon svg {
        width: 12px;
        height: 12px;
        color: white;
    }

    .resend-section {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border: 1px solid #E7A51B;
        border-radius: 8px;
        padding: 20px;
        margin: 20px 0;
        text-align: center;
    }

    .resend-text {
        color: #495057;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .btn-resend {
        background: linear-gradient(135deg, #2C2A4C, #E7A51B);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 14px;
        min-width: 180px;
    }

    .btn-resend:hover {
        background: linear-gradient(135deg, #E7A51B, #2C2A4C);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(44, 42, 76, 0.25);
    }

    .btn-resend:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .email-footer {
        background: #f8f9fa;
        padding: 20px 25px;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }

    .footer-text {
        color: #6c757d;
        font-size: 13px;
        margin: 0;
    }

    .support-link {
        color: #2C2A4C;
        text-decoration: none;
        font-weight: 500;
        margin: 0 8px;
    }

    .support-link:hover {
        color: #E7A51B;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .email-verification-container {
            padding: 15px;
        }
        
        .email-content {
            padding: 25px 20px;
        }
        
        .email-header {
            padding: 25px 20px;
        }
        
        .email-title {
            font-size: 20px;
        }
        
        .email-footer {
            padding: 15px 20px;
        }

        .btn-resend {
            width: 100%;
            min-width: auto;
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
            <h1 class="email-title">Verify Your Email</h1>
            <p class="email-subtitle">Check your inbox to continue</p>
        </div>

        <!-- Email Content -->
        <div class="email-content">
            <a href="{{ route('user.login') }}" class="back-btn">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.42-1.41L7.83 13H20v-2z"/>
                </svg>
                Back to Login
            </a>

            @if (session('status') == 'verification-link-sent')
                <div class="alert-success">
                    <div class="check-icon">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <div>
                        <strong>Email sent successfully!</strong><br>
                        <small>Please check your inbox for the verification link.</small>
                    </div>
                </div>
            @endif

            <div class="verification-message">
                <p>We've sent a verification email to:</p>
                <div class="user-email">{{ auth('user')->user()->email }}</div>
                <p>Click the verification link in your email to activate your account.</p>
            </div>

            <div class="resend-section">
                <p class="resend-text">
                    <strong>Didn't receive the email?</strong><br>
                    Check your spam folder or send a new verification email.
                </p>
                
                <form method="POST" action="{{ route('user.verification.send') }}" style="display: inline;" id="resendForm">
                    @csrf
                    <button type="submit" class="btn-resend" id="resendBtn">
                        Send New Email
                    </button>
                </form>
            </div>
        </div>

        <!-- Email Footer -->
        <div class="email-footer">
            <p class="footer-text">
                <strong>FreeDoctor Healthcare</strong><br>
                Need help? 
                <a href="mailto:info@freedoctor.world" class="support-link">Contact Support</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.getElementById('resendForm');
    const resendBtn = document.getElementById('resendBtn');
    
    if (resendForm && resendBtn) {
        resendForm.addEventListener('submit', function() {
            resendBtn.disabled = true;
            resendBtn.innerHTML = 'Sending...';
            resendBtn.style.opacity = '0.7';
            
            setTimeout(function() {
                if (resendBtn.disabled) {
                    resendBtn.disabled = false;
                    resendBtn.innerHTML = 'Send New Email';
                    resendBtn.style.opacity = '1';
                }
            }, 5000);
        });
    }
});
</script>
@endsection
