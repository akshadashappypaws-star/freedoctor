
@extends('layouts.app')

@section('title', 'Doctor Register')

@section('content')
<div class="login-card" role="main" aria-label="Doctor Registration Form">
  <!-- Logo Section -->
  <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
    <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 70px; width: auto; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
      <h2 style="color: #667eea; margin: 0; font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        FreeDoctor
      </h2>
    </div>
  </div>
  

  <h1 class="title">Welcome Doctor</h1>
  <p class="subtitle">Register your Doctor account below.</p>

  <div class="tabs" role="tablist" aria-label="Doctor login or Info">
    <button class="tab active" role="tab" aria-selected="true" aria-controls="login-panel" id="login-tab" tabindex="0">Doctor Register</button>

  </div>
  
      <form method="POST" action="{{ route('doctor.register') }}">
        @csrf
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

  <!-- Google Login Button -->
  <button type="button" class="google-login-btn" onclick="registerWithGoogle('doctor')">
    <div class="google-icon">
      <svg width="14" height="14" viewBox="0 0 24 24">
        <path fill="#4285f4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34a853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#fbbc05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#ea4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
    </div>
    Register with Google
  </button>
  
  <div class="divider">
    <span>or register with email</span>
  </div>

  <div class="input-group mb-3">
  <input type="text" name="Doctor_name" class="form-control" placeholder="Full Name" required>
  <div class="input-group-append">
  
  </div>
</div>

<div class="input-group mb-3">
  <input type="email" name="email" class="form-control" placeholder="Email" required>
  <div class="input-group-append">

  </div>
</div>

<div class="input-group mb-3">
  <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
  <div class="input-group-append">
 
  </div>
</div>
<div class="input-group mb-3">
  <input type="password" name="password" class="form-control" placeholder="Password" required>
  <div class="input-group-append">
  
  </div>
</div>



        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
          
        </div>

        <!-- Optional Specialty Dropdown -->
        @if(isset($specialties))
        <div class="form-group">
          <select name="specialty_id" class="form-control">
            <option value="">Select Specialty</option>
            @foreach($specialties as $specialty)
              <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
            @endforeach
          </select>
        </div>
        @endif

        <!-- Subscription Fee Information -->
        <div class="alert alert-info mt-3 mb-3" style="background-color: #e3f2fd; border: 1px solid #2196F3; color: #1976D2; padding: 15px; border-radius: 5px;">
            <div style="display: flex; align-items: center;">
                <i class="fas fa-info-circle" style="margin-right: 10px; font-size: 18px;"></i>
                <div>
                    @if($subscriptionFee == 0)
                        <strong>Free Registration!</strong><br>
                        <small>Doctor registration is currently free. You can register and start using the platform immediately.</small>
                    @else
                        <strong>Registration Fee: ₹{{ number_format($subscriptionFee, 2) }}</strong><br>
                        <small>After registration, you'll be redirected to complete the payment via Razorpay to activate your account.</small>
                    @endif
                </div>
            </div>
        </div>

        <!-- Terms and Conditions Checkbox -->
        <div class="form-check mb-3" style="text-align: left;">
            <input type="checkbox" name="accept_terms" id="accept_terms" class="form-check-input" required>
            <label for="accept_terms" class="form-check-label" style="font-size: 14px;">
                I agree to the <a href="{{ route('user.terms-and-conditions') }}" target="_blank" class="text-primary">Terms and Conditions</a> and <a href="{{ route('user.privacy-policy') }}" target="_blank" class="text-primary">Privacy Policy</a>
            </label>
        </div>

        <div class="row">
          <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" id="registerButton" disabled>
                  @if($subscriptionFee == 0)
                      Register Free
                  @else
                      Register & Pay ₹{{ number_format($subscriptionFee, 2) }}
                  @endif
              </button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">Already have an account? <a href="{{ route('doctor.login') }}">Login</a></p>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle terms and conditions checkbox
    const termsCheckbox = document.getElementById('accept_terms');
    const registerButton = document.getElementById('registerButton');
    
    termsCheckbox.addEventListener('change', function() {
        registerButton.disabled = !this.checked;
    });
});
</script>
@endsection
