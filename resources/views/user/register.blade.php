@extends('layouts.app')

@section('content')
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
  <p class="subtitle">Register your User account below.</p>

  <div class="tabs" role="tablist" aria-label="User Register or Info">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="login-panel" id="login-tab" tabindex="0">User Register</button>

  </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please correct the following:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('user.register') }}" id="registrationForm">
        @csrf
        
        <!-- Google Register Button -->
        <button type="button" class="google-login-btn" onclick="registerWithGoogle('user')">
          <div class="google-icon">
            <svg width="14" height="14" viewBox="0 0 24 24">
              <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
          </div>
          Sign up with Google
        </button>
        
        <div class="divider">
          <span>or register with email</span>
        </div>
        
        <!-- Hidden fields for referral tracking -->
        <input type="hidden" name="referred_by" id="referredBy" value="">

        <div class="mb-3">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
        </div>

        <div class="mb-3">
            <label for="phone">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
        </div>

        <div class="mb-3">
            <label for="email">Email address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <!-- Referral info display (if referred) -->
        <div id="referralInfo" style="display: none;" class="mb-3 p-3 bg-success bg-opacity-10 border border-success rounded">
            <div class="d-flex align-items-start">
                <i class="fas fa-gift text-success me-2 mt-1"></i>
                <div>
                    <strong class="text-success">🎉 Referral Benefits Applied!</strong>
                    <p class="mb-2 text-muted small referral-message">You're registering through a referral link and will receive special bonuses!</p>
                    <div class="small text-success">
                        <i class="fas fa-check-circle me-1"></i>
                        <span>Referral ID: <code id="referralCode">Loading...</code></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms and Conditions Checkbox -->
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="accept_terms" id="accept_terms" class="form-check-input" required>
                <label for="accept_terms" class="form-check-label">
                    I agree to the <a href="{{ route('user.terms-and-conditions') }}" target="_blank" class="text-primary">Terms and Conditions</a> and <a href="{{ route('user.privacy-policy') }}" target="_blank" class="text-primary">Privacy Policy</a>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-success" id="registerButton" disabled>Register</button>

        <p class="mt-3">Already have an account? <a href="{{ route('user.login') }}">Login here</a></p>
    </form>
</div>

<script>
// Google Register Function
function registerWithGoogle(type) {
    window.location.href = "{{ route('user.auth.google') }}";
}

// Enhanced referral tracking for registration
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Initializing referral tracking for registration...');
    
    // Handle terms and conditions checkbox
    const termsCheckbox = document.getElementById('accept_terms');
    const registerButton = document.getElementById('registerButton');
    
    if (termsCheckbox && registerButton) {
        termsCheckbox.addEventListener('change', function() {
            registerButton.disabled = !this.checked;
        });
    }
    
    // Wait for referral tracker to be available and then setup referral data
    waitForReferralTracker();
});

function waitForReferralTracker() {
    if (window.referralTracker && window.getReferralCode) {
        checkAndSetReferralData();
    } else {
        setTimeout(waitForReferralTracker, 100);
    }
}

function checkAndSetReferralData() {
    let referralData = null;
    
    // Priority 1: Check URL parameters for immediate referral
    const urlParams = new URLSearchParams(window.location.search);
    const urlRef = urlParams.get('ref') || urlParams.get('s');
    const urlCampaign = urlParams.get('campaign') || urlParams.get('campaign_id');
    
    // Priority 2: Check our new referral tracking system
    const storedReferralCode = window.getReferralCode();
    
    // Priority 3: Check session storage from server-side
    const sessionReferralData = @json(session('referral_data'));
    
    // Priority 4: Check legacy localStorage
    const legacyReferral = localStorage.getItem('referral_data');
    
    if (urlRef) {
        // Direct URL referral takes priority
        let decodedRef = urlRef;
        
        // If it's an encoded 's' parameter, decode it
        if (urlParams.has('s')) {
            try {
                const paddedRef = urlRef.padEnd(Math.ceil(urlRef.length / 4) * 4, '=');
                decodedRef = atob(paddedRef);
            } catch (error) {
                console.warn('❌ Failed to decode referral parameter:', error);
                decodedRef = urlRef;
            }
        }
        
        referralData = {
            referral_id: decodedRef,
            campaign_id: urlCampaign || '',
            source: 'url_direct'
        };
        
        console.log('🔗 URL referral found:', referralData);
        
        // Store in our new tracking system
        if (window.referralTracker) {
            window.referralTracker.storeReferralCode(decodedRef);
        }
        
    } else if (storedReferralCode) {
        // Use our new referral tracking system
        referralData = {
            referral_id: storedReferralCode,
            campaign_id: '',
            source: 'stored_tracking'
        };
        
        console.log('💾 Stored referral found:', referralData);
        
    } else if (sessionReferralData && sessionReferralData.referral_id) {
        // Use session data from server-side
        referralData = sessionReferralData;
        referralData.source = 'session_server';
        
        console.log('🖥️ Server session referral found:', referralData);
        
    } else if (legacyReferral) {
        // Legacy fallback
        try {
            referralData = JSON.parse(legacyReferral);
            referralData.source = 'legacy_storage';
            console.log('📚 Legacy referral found:', referralData);
        } catch (e) {
            console.error('❌ Error parsing legacy referral data:', e);
        }
    }
    
    if (referralData && referralData.referral_id) {
        // Set hidden form fields
        document.getElementById('referredBy').value = referralData.referral_id;
        
        // Show referral info
        const referralInfoDiv = document.getElementById('referralInfo');
        if (referralInfoDiv) {
            referralInfoDiv.style.display = 'block';
            
            // Update referral code display
            const referralCodeSpan = document.getElementById('referralCode');
            if (referralCodeSpan) {
                referralCodeSpan.textContent = referralData.referral_id;
            }
        }
        
        console.log('✅ Referral data set for registration:', referralData);
        
        // Show success notification
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Referral Benefits Applied!',
                text: 'You\'ll receive special bonuses when you complete registration.',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#e8f5e8',
                iconColor: '#4caf50'
            });
        }
    } else {
        console.log('ℹ️ No referral data found');
    }
}

// Clear referral data after successful registration
document.getElementById('registrationForm').addEventListener('submit', function() {
    console.log('📝 Registration form submitted, will clean up referral data after submission...');
    
    // Clear all referral tracking data after successful submission
    setTimeout(function() {
        // Clear new referral tracking system
        if (window.clearReferralCode) {
            window.clearReferralCode();
        }
        
        // Clear legacy data
        localStorage.removeItem('referral_data');
        
        console.log('🧹 Referral data cleanup completed');
    }, 2000); // Wait longer to ensure form submission completes
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
