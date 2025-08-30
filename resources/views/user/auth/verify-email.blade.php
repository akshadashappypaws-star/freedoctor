@extends('layouts.app')

@section('content')
<!-- Back Arrow Button - Fixed Top Left for App Launch -->
<div class="back-arrow-container">
    <a href="{{ route('user.login') }}" class="back-arrow-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Login
    </a>
</div>

<div class="login-card" role="main" aria-label="Email Verification Form">
    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 70px; width: auto; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <h2 style="color: #667eea; margin: 0; font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                FreeDoctor
            </h2>
        </div>
    </div>
    
    <h1 class="title">Verify Your Email</h1>
    <p class="subtitle">Check your inbox to continue</p>

    <div class="tabs" role="tablist" aria-label="Email Verification">
        <button class="tab active" role="tab" aria-selected="true" aria-controls="verify-panel" id="verify-tab" tabindex="0">Email Verification</button>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            <div class="check-icon">
                <svg fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
            <div>
                <strong>Email sent successfully!</strong><br>
                <small>Please check your inbox and spam folder for the verification link.</small>
            </div>
        </div>
    @endif

    <div class="verification-content">
        <div class="verification-message">
            <p><strong>We've sent a verification email to:</strong></p>
            <div class="user-email">{{ auth('user')->user()->email }}</div>
            <p>Click the verification link in your email to activate your account and start accessing all features of FreeDoctor.</p>
        </div>

        <div class="resend-section">
            <p class="resend-text">
                <strong>Didn't receive the email?</strong><br>
                Check your spam folder, or click below to send a new verification email.
            </p>
            
            <form method="POST" action="{{ route('user.verification.send') }}" id="resendForm">
                @csrf
                <button type="submit" class="btn-resend" id="resendBtn">
                    <span class="loading-spinner"></span>
                    <span class="btn-text">Send New Email</span>
                </button>
            </form>
        </div>
    </div>

    <div class="footer-links">
        <p>Need assistance? <a href="mailto:support@freedoctor.world">Contact Support</a></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resendForm = document.getElementById('resendForm');
    const resendBtn = document.getElementById('resendBtn');
    const btnText = resendBtn.querySelector('.btn-text');
    
    if (resendForm && resendBtn) {
        resendForm.addEventListener('submit', function(e) {
            // Prevent double submission
            if (resendBtn.disabled) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            resendBtn.disabled = true;
            resendBtn.classList.add('loading');
            btnText.textContent = 'Sending...';
            
            // Reset after 10 seconds if no response
            setTimeout(function() {
                if (resendBtn.disabled) {
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('loading');
                    btnText.textContent = 'Send New Email';
                }
            }, 10000);
        });
    }

    // Auto-enable button after successful send
    @if (session('status') == 'verification-link-sent')
        setTimeout(function() {
            if (resendBtn && resendBtn.disabled) {
                resendBtn.disabled = false;
                resendBtn.classList.remove('loading');
                btnText.textContent = 'Send Another Email';
            }
        }, 2000);
    @endif
});
</script>

<style>
/* Hide scroll bars while keeping scrollability */
body {
    overflow-x: hidden;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}

body::-webkit-scrollbar {
    display: none; /* WebKit */
}

.login-card {
    overflow-y: auto;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer 10+ */
}

.login-card::-webkit-scrollbar {
    display: none; /* WebKit */
}

/* Verification-specific styles that match the login page design */
.verification-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.verification-message {
    text-align: center;
    color: var(--text-primary);
}

.verification-message p {
    font-size: 0.85rem;
    margin: 0 0 10px 0;
    color: var(--text-secondary);
    line-height: 1.4;
}

.verification-message p strong {
    color: var(--primary-color);
}

.user-email {
    background: linear-gradient(135deg, var(--background-color), #e9ecef);
    border: 2px solid var(--accent-color);
    border-radius: 10px;
    padding: 12px 15px;
    text-align: center;
    margin: 15px 0;
    font-weight: 600;
    color: var(--primary-color);
    word-break: break-word;
    font-size: 0.9rem;
}

.resend-section {
    background: linear-gradient(135deg, var(--background-color), #e9ecef);
    border: 2px solid var(--accent-color);
    border-radius: 10px;
    padding: 20px;
    text-align: center;
}

.resend-text {
    color: var(--text-secondary);
    margin-bottom: 15px;
    font-size: 0.8rem;
    line-height: 1.4;
}

.resend-text strong {
    color: var(--primary-color);
    font-weight: 600;
}

.btn-resend {
    background: linear-gradient(90deg, #7a5de8, #5a4de8);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 12px 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-resend:hover {
    background: linear-gradient(90deg, #5a4de8, #7a5de8);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(122, 93, 232, 0.3);
}

.btn-resend:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-resend:disabled:hover {
    transform: none;
    box-shadow: none;
}

/* Loading spinner */
.loading-spinner {
    display: none;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.btn-resend.loading .loading-spinner {
    display: inline-block;
}

/* Success alert matching login page style */
.alert.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    border: 2px solid #28a745;
    color: #155724;
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    font-size: 0.85rem;
}

.alert.alert-success .check-icon {
    width: 24px;
    height: 24px;
    background: #28a745;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.alert.alert-success .check-icon svg {
    width: 14px;
    height: 14px;
    color: white;
}

.footer-links {
    margin-top: 20px;
    text-align: center;
}

.footer-links p {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin: 0;
}

.footer-links a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 600;
}

.footer-links a:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

/* Responsive design matching login page */
@media (max-width: 768px) {
    .login-card {
        max-width: 350px;
        padding: 30px 25px 40px;
    }
    
    .title {
        font-size: 1.3rem;
    }
    
    .subtitle {
        font-size: 0.8rem;
    }
    
    .user-email {
        font-size: 0.85rem;
        padding: 10px 12px;
    }
    
    .resend-section {
        padding: 18px;
    }
    
    .resend-text {
        font-size: 0.75rem;
    }
    
    .btn-resend {
        font-size: 0.8rem;
        padding: 10px 20px;
    }
}

@media (max-width: 480px) {
    .login-card {
        margin: 10px;
        padding: 25px 20px 35px;
    }
}
</style>
@endsection
