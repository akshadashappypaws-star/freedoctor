
@extends('layouts.app')

@section('content')
<!-- Back Arrow Button - Fixed Top Left for App Launch -->
<div class="back-arrow-container">
    <a href="{{ route('user.home') }}" class="back-arrow-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Site
    </a>
</div>

<div class="login-card" role="main" aria-label="User Registration Form">
  <!-- Logo Section -->
  <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 70px; width: auto; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
      <h2 style="color: #667eea; margin: 0; font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        FreeDoctor
      </h2>
    </div>
  </div>
  
  
  <h1 class="title">Welcome User</h1>
  <p class="subtitle">login your User account below.</p>

  <div class="tabs" role="tablist" aria-label="User login or Info">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="login-panel" id="login-tab" tabindex="0">User login</button>

  </div>
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('user.login') }}">
        @csrf
        
        <!-- Google Login Button -->
        <button type="button" class="google-login-btn" onclick="loginWithGoogle('user')">
          <div class="google-icon">
            <svg width="14" height="14" viewBox="0 0 24 24">
              <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
          </div>
          Continue with Google
        </button>
        
        <div class="divider">
          <span>or login with email</span>
        </div>

        <div class="mb-3">
            <label for="email">Email address</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" class="form-check-input">
            <label class="form-check-label">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>

        <!-- Hidden field for redirect URL -->
        <input type="hidden" name="redirect_url" id="redirect_url" value="">

        <a href="{{ route('user.password.request') }}" class="btn btn-link">Forgot Password?</a>
    </form>

    <p class="mt-3">Don't have an account? <a href="{{ route('user.register') }}">Register here</a></p>
</div>

<!-- Email Verification Modal -->
@if(session('show_verification_modal'))
<div class="modal fade show" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="verificationModalLabel" style="display: block; background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verificationModalLabel">
                    <i class="fas fa-envelope-open-text text-primary me-2"></i>
                    Email Verification Required
                </h5>
                <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <div class="verification-icon mb-3">
                        <i class="fas fa-envelope fa-3x text-warning"></i>
                    </div>
                    <h6>We need to verify your email address</h6>
                    <p class="text-muted mb-3">
                        Your account <strong>{{ session('user_email') }}</strong> is not yet verified. 
                        Please check your email for the verification link, or click below to send a new one.
                    </p>
                </div>
                
                <div id="resendStatus" class="alert" style="display: none;"></div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" onclick="resendVerificationEmail()">
                        <i class="fas fa-paper-plane me-2"></i>
                        Send Verification Email
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="closeModal()">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a redirect URL stored in sessionStorage
    const redirectUrl = sessionStorage.getItem('redirectAfterLogin');
    if (redirectUrl) {
        document.getElementById('redirect_url').value = redirectUrl;
        
        // Clear the sessionStorage item
        sessionStorage.removeItem('redirectAfterLogin');
    }
});

// Google Login Function
function loginWithGoogle(type) {
    window.location.href = "{{ route('user.auth.google') }}";
}

// Modal Functions
function closeModal() {
    document.getElementById('verificationModal').style.display = 'none';
}

// Resend Verification Email
function resendVerificationEmail() {
    const statusDiv = document.getElementById('resendStatus');
    const button = document.querySelector('[onclick="resendVerificationEmail()"]');
    const originalText = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    button.disabled = true;
    statusDiv.style.display = 'none';
    
    // Get email from session
    const email = "{{ session('user_email') }}";
    
    fetch("{{ route('user.resend.verification') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            statusDiv.className = 'alert alert-success';
            statusDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>' + data.message;
        } else {
            statusDiv.className = 'alert alert-danger';
            statusDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>' + (data.message || 'Error sending verification email');
        }
        statusDiv.style.display = 'block';
        
        // Reset button after 3 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 3000);
    })
    .catch(error => {
        console.error('Error:', error);
        statusDiv.className = 'alert alert-danger';
        statusDiv.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>Network error. Please try again.';
        statusDiv.style.display = 'block';
        
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
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

.google-login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #dadce0;
    border-radius: 8px;
    background-color: #fff;
    color: #3c4043;
    font-family: 'Segoe UI', Arial, sans-serif;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-bottom: 20px;
}

.google-login-btn:hover {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border-color: #c6c6c6;
}

.google-login-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.3);
}

.google-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.divider {
    position: relative;
    text-align: center;
    margin: 20px 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background-color: #dadce0;
}

.divider span {
    background-color: #fff;
    padding: 0 12px;
    color: #5f6368;
    font-size: 13px;
}

/* Verification Modal Styles */
.modal {
    z-index: 1050;
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
    padding: 1.25rem;
    border-radius: 12px 12px 0 0;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.modal-header .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
    text-shadow: none;
    font-size: 1.5rem;
    background: none;
    border: none;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body {
    padding: 1.5rem;
}

.verification-icon {
    padding: 1rem;
    background: rgba(255, 193, 7, 0.1);
    border-radius: 50px;
    display: inline-block;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary {
    border: 1px solid #6c757d;
    color: #6c757d;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}
</style>

@endsection
