
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
</script>

<style>
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
</style>

@endsection
