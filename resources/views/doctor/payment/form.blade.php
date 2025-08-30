@extends('layouts.app')

@section('title', 'Doctor Registration Payment')

@section('content')
<!-- Back Arrow Button - Fixed Top Left for App Launch -->
<div class="back-arrow-container">
    <a href="{{ route('doctor.login') }}" class="back-arrow-btn">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>
        Back to Login
    </a>
</div>

<div class="login-card" role="main" aria-label="Doctor Payment Form">
    <!-- Logo Section -->
    <div class="logo-section" style="text-align: center; margin-bottom: 25px;">
        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="height: 70px; width: auto; margin-bottom: 15px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));" />
        <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
            <h2 style="color: #667eea; margin: 0; font-size: 24px; font-weight: 700; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                FreeDoctor
            </h2>
        </div>
    </div>
    
    <h1 class="title">Complete Your Registration</h1>
    <p class="subtitle">One-time payment to activate your doctor account</p>

    <div class="tabs" role="tablist" aria-label="Doctor Payment">
        <button class="tab active" role="tab" aria-selected="true" aria-controls="payment-panel" id="payment-tab" tabindex="0">Registration Payment</button>
    </div>

    <!-- Doctor Info Section -->
    <div style="background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 8px; padding: 20px; margin-bottom: 25px; color: white;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-md" style="font-size: 20px;"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">{{ $doctor->doctor_name }}</h3>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">{{ $doctor->email }}</p>
                @if($doctor->phone)
                <p style="margin: 2px 0 0 0; opacity: 0.8; font-size: 13px;">{{ $doctor->phone }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Benefits -->
    <div style="margin-bottom: 25px;">
        <h3 style="color: #333; font-size: 16px; font-weight: 600; margin-bottom: 15px;">Registration Benefits:</h3>
        <div style="display: grid; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #e8f5e8; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check" style="color: #28a745; font-size: 10px;"></i>
                </div>
                <span style="font-size: 14px; color: #555;">Verified Profile Badge</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #e8f5e8; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-bullhorn" style="color: #28a745; font-size: 10px;"></i>
                </div>
                <span style="font-size: 14px; color: #555;">Create Medical Camps</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #e8f5e8; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-users" style="color: #28a745; font-size: 10px;"></i>
                </div>
                <span style="font-size: 14px; color: #555;">Patient Management System</span>
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <div style="width: 20px; height: 20px; background: #e8f5e8; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-chart-line" style="color: #28a745; font-size: 10px;"></i>
                </div>
                <span style="font-size: 14px; color: #555;">Analytics Dashboard</span>
            </div>
        </div>
    </div>

    <!-- Amount Display -->
    <div style="background: linear-gradient(135deg, #e8f5e8, #f0f8f0); border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 25px; border: 1px solid #d4edda;">
        <p style="margin: 0 0 5px 0; color: #666; font-size: 14px;">Registration Fee</p>
        <div style="font-size: 28px; font-weight: bold; color: #28a745; margin-bottom: 5px;">₹{{ number_format($subscriptionFee, 2) }}</div>
        <p style="margin: 0; font-size: 12px; color: #666;">One-time payment • Lifetime access</p>
    </div>

    <!-- Payment Form -->
    <form method="POST" action="{{ route('doctor.payment.create', $doctor->id) }}" id="paymentForm">
        @csrf
        <input type="hidden" name="amount" value="{{ $subscriptionFee }}">
        
        <!-- Display Error Messages -->
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <div style="font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i>
                    Please fix the following errors:
                </div>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li style="margin-bottom: 4px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Display Session Error Messages -->
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                <div style="font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i>
                    Error:
                </div>
                <p style="margin: 0; font-size: 14px;">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Display Success Messages -->
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                <div style="font-weight: 600; margin-bottom: 8px;">
                    <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
                    Success:
                </div>
                <p style="margin: 0; font-size: 14px;">{{ session('success') }}</p>
            </div>
        @endif
        
        <!-- Terms and Conditions -->
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #555; cursor: pointer;">
                <input type="checkbox" name="terms" required style="margin-top: 2px;">
                <span>I agree to the <a href="#" style="color: #667eea;">Terms and Conditions</a> and <a href="#" style="color: #667eea;">Privacy Policy</a></span>
            </label>
        </div>

        <!-- Payment Button -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block" style="background: linear-gradient(135deg, #28a745, #20c997); border: none; padding: 12px; font-weight: 600; font-size: 16px;">
                    <i class="fas fa-shield-alt" style="margin-right: 8px;"></i>
                    Proceed to Secure Payment
                </button>
            </div>
        </div>
    </form>

    <!-- Security Info -->
    <div style="text-align: center; margin-top: 20px;">
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px; color: #666; font-size: 12px;">
            <div style="display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-lock"></i>
                <span>256-bit SSL</span>
            </div>
            <div style="display: flex; align-items: center; gap: 4px;">
                <i class="fas fa-shield-alt"></i>
                <span>Razorpay Secured</span>
            </div>
        </div>
    </div>

    <!-- Support Link -->
    <p style="text-align: center; margin-top: 20px; font-size: 13px; color: #666;">
        Questions about payment? <a href="mailto:support@freedoctor.com" style="color: #667eea;">Contact Support</a>
    </p>
</div>
@endsection
