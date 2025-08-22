@extends('user.layouts.app')

@section('title', 'Register for ' . $campaign->title)

@push('styles')
<style>
    :root {
        --primary-color: #2C2A4C;
        --secondary-color: #667eea;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --white: #ffffff;
        --border-color: #e9ecef;
        --text-color: #495057;
        --light-gray: #f8f9fa;
    }

    .campaign-register-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .register-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .campaign-header {
        background: linear-gradient(135deg, #2C2A4C 0%, #667eea 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .campaign-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .campaign-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .campaign-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .register-content {
        padding: 2rem;
    }

    .section-title {
        color: var(--primary-color);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .campaign-details-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        border: 1px solid #e9ecef;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0.75rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .detail-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }

    .detail-text {
        flex: 1;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.95rem;
        color: var(--primary-color);
        font-weight: 600;
    }

    .campaign-description {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid var(--secondary-color);
        margin-bottom: 1rem;
    }

    .registration-form-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--primary-color);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--secondary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .payment-summary {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
    }

    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    .payment-item:last-child {
        border-bottom: none;
        font-size: 1.2rem;
        font-weight: 700;
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
    }

    .trusted-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .trusted-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .trusted-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .trusted-feature {
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .trusted-feature i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .register-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }

    .register-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
    }

    .back-btn {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .campaign-register-container {
            padding: 1rem 0;
        }
        
        .register-card {
            margin: 0 1rem;
        }
        
        .campaign-header {
            padding: 1.5rem;
        }
        
        .campaign-title {
            font-size: 1.5rem;
        }
        
        .register-content {
            padding: 1.5rem;
        }
        
        .campaign-info {
            gap: 1rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<div class="campaign-register-container">
    <div class="container">
        <!-- Back Button -->
        <a href="{{ route('user.campaigns') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Campaigns
        </a>

        <div class="register-card">
            <!-- Campaign Header -->
            <div class="campaign-header">
                <h1 class="campaign-title">{{ $campaign->title }}</h1>
                <p class="campaign-subtitle">You are registering for this medical campaign</p>
                <div class="campaign-info">
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') }}</span>
                    </div>
                    @if($campaign->start_time && $campaign->end_time)
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ \Carbon\Carbon::parse($campaign->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($campaign->end_time)->format('g:i A') }}</span>
                    </div>
                    @endif
                    @if($campaign->location)
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $campaign->location }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="register-content">
                <!-- Campaign Details Section -->
                <div class="campaign-details-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Campaign Details
                    </h2>
                    
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="detail-text">
                                <div class="detail-label">Doctor</div>
                                <div class="detail-value">{{ $campaign->doctor ? $campaign->doctor->doctor_name : 'TBA' }}</div>
                            </div>
                        </div>

                        @if($campaign->doctor && $campaign->doctor->specialty)
                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="detail-text">
                                <div class="detail-label">Specialty</div>
                                <div class="detail-value">{{ $campaign->doctor->specialty->name }}</div>
                            </div>
                        </div>
                        @endif

                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="detail-text">
                                <div class="detail-label">Capacity</div>
                                <div class="detail-value">{{ $campaign->max_participants ?? 'Unlimited' }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon" style="background: linear-gradient(135deg, #dc3545, #e83e8c);">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="detail-text">
                                <div class="detail-label">Type</div>
                                <div class="detail-value">{{ $campaign->camp_type ?? 'Medical Camp' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($campaign->description)
                    <div class="campaign-description">
                        <h4 style="color: var(--primary-color); margin-bottom: 1rem;">About This Campaign</h4>
                        <p style="margin: 0; line-height: 1.6; color: #6c757d;">{{ $campaign->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Registration Form -->
                <div class="registration-form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-plus"></i>
                        Registration Information
                    </h2>

                    <form id="registrationForm" action="{{ route('user.campaigns.register.store', $campaign->id) }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" id="name" name="name" class="form-control" 
                                           value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" 
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age" class="form-label">Age *</label>
                                    <input type="number" id="age" name="age" class="form-control" 
                                           value="{{ old('age') }}" min="1" max="120" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3" 
                                      placeholder="Enter your full address">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                            <input type="text" id="emergency_contact" name="emergency_contact" class="form-control" 
                                   value="{{ old('emergency_contact') }}" placeholder="Emergency contact name and phone">
                        </div>

                        <div class="form-group">
                            <label for="medical_conditions" class="form-label">Medical Conditions/Allergies</label>
                            <textarea id="medical_conditions" name="medical_conditions" class="form-control" rows="3" 
                                      placeholder="Please mention any medical conditions, allergies, or medications you're currently taking">{{ old('medical_conditions') }}</textarea>
                        </div>

                        <!-- Payment Summary -->
                        @if($campaign->registration_payment && $campaign->registration_payment > 0)
                        <div class="payment-summary">
                            <h4 style="margin-bottom: 1rem;"><i class="fas fa-rupee-sign"></i> Payment Summary</h4>
                            
                            @if($campaign->original_price && $campaign->original_price > $campaign->registration_payment)
                            <div class="payment-item">
                                <span>Original Price:</span>
                                <span style="text-decoration: line-through;">₹{{ number_format($campaign->original_price) }}</span>
                            </div>
                            <div class="payment-item">
                                <span>Discount ({{ $campaign->discount_percentage }}%):</span>
                                <span>-₹{{ number_format($campaign->original_price - $campaign->registration_payment) }}</span>
                            </div>
                            @endif
                            
                            <div class="payment-item">
                                <span>Registration Fee:</span>
                                <span>₹{{ number_format($campaign->registration_payment) }}</span>
                            </div>
                        </div>
                        @else
                        <div class="payment-summary" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            <h4 style="margin-bottom: 1rem; text-align: center;">
                                <i class="fas fa-gift"></i> FREE Registration
                            </h4>
                            <p style="margin: 0; text-align: center; font-size: 1.1rem;">No registration fee required for this campaign!</p>
                        </div>
                        @endif

                        <!-- Register Button -->
                        <button type="submit" class="register-btn">
                            <i class="fas fa-user-plus me-2"></i>
                            @if($campaign->registration_payment && $campaign->registration_payment > 0)
                                Proceed to Payment (₹{{ number_format($campaign->registration_payment) }})
                            @else
                                Complete Registration
                            @endif
                        </button>
                    </form>
                </div>

                <!-- Trusted Section -->
                <div class="trusted-section">
                    <h3 class="trusted-title">
                        <i class="fas fa-shield-alt"></i>
                        Why Trust Our Platform?
                    </h3>
                    <p style="margin-bottom: 2rem; font-size: 1.1rem; opacity: 0.9;">
                        Join thousands of satisfied participants who trust us for their healthcare needs
                    </p>
                    
                    <div class="trusted-features">
                        <div class="trusted-feature">
                            <i class="fas fa-user-md"></i>
                            <h5>Verified Doctors</h5>
                            <p style="margin: 0; font-size: 0.9rem;">All our medical professionals are certified and verified</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-lock"></i>
                            <h5>Secure Payments</h5>
                            <p style="margin: 0; font-size: 0.9rem;">Your payment information is protected with bank-level security</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-headset"></i>
                            <h5>24/7 Support</h5>
                            <p style="margin: 0; font-size: 0.9rem;">Round-the-clock customer support for all your queries</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-certificate"></i>
                            <h5>Quality Assured</h5>
                            <p style="margin: 0; font-size: 0.9rem;">High-quality medical services with proper documentation</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    submitBtn.disabled = true;
    
    // Simulate form submission (replace with actual form submission)
    setTimeout(() => {
        // Here you would normally submit the form
        // For now, we'll show a success message
        Swal.fire({
            title: 'Registration Successful!',
            text: 'Your registration has been submitted successfully. You will receive a confirmation email shortly.',
            icon: 'success',
            confirmButtonColor: '#28a745'
        }).then(() => {
            // Redirect to campaigns page or success page
            window.location.href = '{{ route("user.campaigns") }}';
        });
    }, 2000);
});
</script>
@endpush
                </div>
            </div>

            <!-- Registration Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Registration Details</h5>
                </div>
                <div class="card-body">
                    <form id="campaignRegistrationForm" method="POST" action="{{ route('user.campaigns.register', $campaign->id) }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="patient_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('patient_name') is-invalid @enderror" 
                                           id="patient_name" 
                                           name="patient_name" 
                                           value="{{ old('patient_name', auth('user')->check() ? auth('user')->user()->username : '') }}"
                                           required>
                                    @error('patient_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', auth('user')->check() ? auth('user')->user()->email : '') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" 
                                           class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" 
                                           name="phone_number" 
                                           value="{{ old('phone_number', auth('user')->check() ? auth('user')->user()->phone : '') }}"
                                           required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('age') is-invalid @enderror" 
                                           id="age" 
                                           name="age" 
                                           value="{{ old('age') }}"
                                           min="1" 
                                           max="120" 
                                           required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender" 
                                            required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address', auth('user')->check() ? auth('user')->user()->address : '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="terms_accepted" 
                                       name="terms_accepted" 
                                       required>
                                <label class="form-check-label" for="terms_accepted">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and 
                                    <a href="#" target="_blank">Privacy Policy</a> <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('user.campaigns.view', $campaign->id) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Campaign
                            </a>
                            
                            @if($campaign->registration_payment > 0)
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card"></i> 
                                    Proceed to Payment (₹{{ number_format($campaign->registration_payment, 2) }})
                                </button>
                            @else
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check"></i> 
                                    Complete Free Registration
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation and submission
    $('#campaignRegistrationForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent double submission
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        // Submit form via AJAX for better UX
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    if (response.redirect_url) {
                        // Redirect to payment if needed
                        window.location.href = response.redirect_url;
                    } else {
                        // Show success message and redirect
                        Swal.fire({
                            title: 'Success!',
                            text: response.message || 'Registration completed successfully!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route("user.campaigns.view", $campaign->id) }}';
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message || 'Registration failed. Please try again.',
                        icon: 'error'
                    });
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Handle validation errors
                    const errors = xhr.responseJSON.errors;
                    let errorList = '<ul class="text-left mb-0">';
                    for (let field in errors) {
                        errors[field].forEach(error => {
                            errorList += `<li>${error}</li>`;
                        });
                    }
                    errorList += '</ul>';
                    errorMessage = errorList;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    title: 'Error!',
                    html: errorMessage,
                    icon: 'error'
                });
                
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}
</style>
@endpush
