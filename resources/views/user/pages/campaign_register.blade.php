@extends('../user.master')

@section('title', 'Register for Campaign - FreeDoctor')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

<style>
/* Campaign Registration Page Styles */
:root {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --white: #ffffff;
    --light-gray: #f8f9fa;
    --border-color: #e9ecef;
    --text-dark: #2d3748;
    --text-muted: #6c757d;
    --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
    --shadow-md: 0 4px 8px rgba(0,0,0,0.15);
    --radius-md: 12px;
    --radius-lg: 16px;
}

.registration-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    padding: 3rem 0 2rem;
    position: relative;
    overflow: hidden;
}

.registration-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
    animation: float 20s infinite linear;
}

@keyframes float {
    0% { transform: translateY(0); }
    100% { transform: translateY(-100px); }
}

.campaign-info-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.campaign-title {
    color: var(--primary-color);
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.campaign-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: var(--light-gray);
    border-radius: var(--radius-md);
}

.detail-item i {
    color: var(--primary-color);
    width: 20px;
    text-align: center;
}

.registration-form-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section-title {
    color: var(--primary-color);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--secondary-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
}

.form-control:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 3px rgba(231, 165, 27, 0.1);
}

.form-control.is-invalid {
    border-color: var(--danger-color);
}

.invalid-feedback {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.required {
    color: var(--danger-color);
}

.payment-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.payment-amount {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    text-align: center;
    margin-bottom: 1rem;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.payment-method {
    background: var(--white);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-md);
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.payment-method:hover {
    border-color: var(--secondary-color);
    background: #fffbf0;
}

.payment-method.selected {
    border-color: var(--primary-color);
    background: #f0f0ff;
}

.payment-method i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.terms-section {
    background: var(--light-gray);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.form-check {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
}

.form-check-input {
    margin-top: 0.25rem;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: space-between;
    flex-wrap: wrap;
}

.btn {
    padding: 0.875rem 2rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background: #3a3564;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background: var(--text-muted);
    color: var(--white);
}

.btn-secondary:hover {
    background: #5a6268;
    text-decoration: none;
    color: var(--white);
}

.btn-success {
    background: var(--success-color);
    color: var(--white);
}

.btn-success:hover {
    background: #218838;
    transform: translateY(-2px);
}

.registration-summary {
    background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
    color: var(--white);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.summary-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.summary-item {
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-md);
    padding: 1rem;
    backdrop-filter: blur(10px);
}

@media (max-width: 768px) {
    .registration-header {
        padding: 2rem 0 1rem;
    }
    
    .campaign-info-card,
    .registration-form-card {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .campaign-details {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Registration Header -->
<section class="registration-header">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1>Campaign Registration</h1>
                <p>Join this medical campaign and take a step towards better healthcare</p>
            </div>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Campaign Information -->
                <div class="campaign-info-card">
                    <h2 class="campaign-title">{{ $campaign->title }}</h2>
                    
                    <div class="campaign-details">
                        <div class="detail-item">
                            <i class="fas fa-user-md"></i>
                            <div>
                                <strong>Doctor:</strong><br>
                                {{ $campaign->doctor->doctor_name ?? 'TBD' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-stethoscope"></i>
                            <div>
                                <strong>Specialty:</strong><br>
                                {{ $campaign->doctor->specialty->name ?? 'General' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-calendar"></i>
                            <div>
                                <strong>Date:</strong><br>
                                {{ \Carbon\Carbon::parse($campaign->start_date)->format('M j, Y') }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Time:</strong><br>
                                {{ $campaign->timings ?? 'TBD' }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Location:</strong><br>
                                {{ $campaign->location }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Available Slots:</strong><br>
                                {{ ($campaign->expected_patients ?? 100) - ($campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0) }} remaining
                            </div>
                        </div>
                    </div>
                    
                    <div class="campaign-description">
                        <h5>About this Campaign:</h5>
                        <p>{{ $campaign->description }}</p>
                    </div>
                </div>

                <!-- Registration Form -->
                <div class="registration-form-card">
                    <form id="registrationForm" action="{{ route('user.campaigns.register.store', $campaign->id) }}" method="POST">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="form-section">
                            <h3 class="form-section-title">Personal Information</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name <span class="required">*</span></label>
                                        <input type="text" name="patient_name" class="form-control" required 
                                               value="{{ old('patient_name', auth()->user()->name ?? '') }}">
                                        @error('patient_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email <span class="required">*</span></label>
                                        <input type="email" name="email" class="form-control" required 
                                               value="{{ old('email', auth()->user()->email ?? '') }}">
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number <span class="required">*</span></label>
                                        <input type="tel" name="phone_number" class="form-control" required 
                                               value="{{ old('phone_number', auth()->user()->phone ?? '') }}">
                                        @error('phone_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Age <span class="required">*</span></label>
                                        <input type="number" name="age" class="form-control" min="1" max="120" required 
                                               value="{{ old('age') }}">
                                        @error('age')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Gender <span class="required">*</span></label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Emergency Contact</label>
                                        <input type="tel" name="emergency_contact" class="form-control" 
                                               value="{{ old('emergency_contact') }}">
                                        @error('emergency_contact')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Address <span class="required">*</span></label>
                                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="form-section">
                            <h3 class="form-section-title">Medical Information</h3>
                            
                            <div class="form-group">
                                <label class="form-label">Medical History / Current Health Issues</label>
                                <textarea name="medical_history" class="form-control" rows="3" 
                                          placeholder="Please describe any existing medical conditions, allergies, or current medications...">{{ old('medical_history') }}</textarea>
                                @error('medical_history')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Reason for Registration</label>
                                <textarea name="registration_reason" class="form-control" rows="2" 
                                          placeholder="Why are you interested in this campaign?">{{ old('registration_reason') }}</textarea>
                                @error('registration_reason')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Registration Information -->
                        <div class="form-section">
                            <div class="alert alert-info" role="alert">
                                <h5><i class="fas fa-info-circle"></i> Registration Process</h5>
                                <p class="mb-2"><strong>What happens after registration:</strong></p>
                                <ul class="mb-0">
                                    <li>We will review your registration within 24 hours</li>
                                    <li>Our team will contact you soon with further details</li>
                                    <li>You can download your registration receipt from "My Registrations" page</li>
                                    <li>You will receive email confirmation once approved</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Payment Section -->
                        @php
                            $registrationFee = $campaign->registration_payment ?? 0;
                            $isFree = $registrationFee <= 0;
                        @endphp

                        @if($isFree)
                            <div class="registration-summary">
                                <div class="summary-title">
                                    <i class="fas fa-gift"></i> FREE Registration
                                </div>
                                <p>This campaign has no registration fee. You can register immediately!</p>
                            </div>
                        @else
                            <div class="payment-section">
                                <div class="payment-amount">
                                    Registration Fee: ₹{{ number_format($registrationFee) }}
                                </div>
                                
                                <div class="alert alert-warning" role="alert">
                                    <h6><i class="fas fa-credit-card"></i> Online Payment Required</h6>
                                    <p class="mb-0">A registration fee of ₹{{ number_format($registrationFee) }} must be paid online to complete your registration for this campaign.</p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Payment Method</label>
                                    <div class="payment-methods">
                                        <div class="payment-method selected" data-method="razorpay">
                                            <i class="fas fa-credit-card"></i>
                                            <h6>Secure Online Payment</h6>
                                            <small>Pay with Credit/Debit Card, UPI, Net Banking</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="payment_method" id="payment_method" value="razorpay">
                                </div>
                            </div>
                        @endif
                      

                        <!-- Terms and Conditions -->
                        <div class="terms-section">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree_terms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and understand that:
                                    <ul class="mt-2">
                                        <li>I will attend the campaign at the specified date and time</li>
                                        <li>I will bring a valid ID proof for verification</li>
                                        <li>I understand that registration fees (if any) are non-refundable</li>
                                        <li>I consent to receive SMS/email updates about this campaign</li>
                                    </ul>
                                </label>
                            </div>
                            @error('agree_terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('user.campaigns') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Back to Campaigns
                            </a>
                            
                            @if($isFree)
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-plus"></i>
                                    Submit Registration
                                </button>
                            @else
                                <button type="button" id="proceedPayment" class="btn btn-success">
                                    <i class="fas fa-credit-card"></i>
                                    Proceed to Payment
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

// Form validation
function validateForm() {
    const form = document.getElementById('registrationForm');
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    // Clear previous error states
    form.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    form.querySelectorAll('.field-error').forEach(error => {
        error.remove();
    });
    
    // Check required fields
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            showFieldError(field, 'This field is required');
            isValid = false;
        }
    });
    
    // Email validation
    const email = form.querySelector('[name="email"]');
    if (email && email.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.value)) {
            email.classList.add('is-invalid');
            showFieldError(email, 'Please enter a valid email address');
            isValid = false;
        }
    }
    
    // Phone validation
    const phone = form.querySelector('[name="phone_number"]');
    if (phone && phone.value.trim()) {
        const phoneRegex = /^[6-9]\d{9}$/;
        if (!phoneRegex.test(phone.value.replace(/\D/g, ''))) {
            phone.classList.add('is-invalid');
            showFieldError(phone, 'Please enter a valid 10-digit phone number');
            isValid = false;
        }
    }
    
    // Age validation
    const age = form.querySelector('[name="age"]');
    if (age && age.value.trim()) {
        const ageValue = parseInt(age.value);
        if (ageValue < 1 || ageValue > 120) {
            age.classList.add('is-invalid');
            showFieldError(age, 'Please enter a valid age between 1 and 120');
            isValid = false;
        }
    }
    
    // Terms validation
    const agreeTerms = form.querySelector('#agreeTerms');
    if (agreeTerms && !agreeTerms.checked) {
        agreeTerms.classList.add('is-invalid');
        showFieldError(agreeTerms, 'You must agree to the terms and conditions');
        isValid = false;
    }
    
    return isValid;
}

function showFieldError(field, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error text-danger small mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

// Payment processing
document.getElementById('proceedPayment')?.addEventListener('click', function() {
    if (!validateForm()) {
        Swal.fire({
            title: 'Validation Error',
            text: 'Please fill in all required fields correctly.',
            icon: 'error'
        });
        return;
    }
    
    // Always use Razorpay for paid campaigns
    initiateRazorpayPayment();
});

function initiateRazorpayPayment() {
    const amount = {{ $campaign->registration_payment ?? 0 }} * 100; // Convert to paise
    const userEmail = document.querySelector('[name="email"]').value;
    const userName = document.querySelector('[name="patient_name"]').value;
    const userPhone = document.querySelector('[name="phone_number"]').value;
    
    const options = {
        "key": "{{ config('services.razorpay.key') }}", // Your Razorpay key
        "amount": amount,
        "currency": "INR",
        "name": "FreeDoctor",
        "description": "Campaign Registration: {{ Str::limit($campaign->title, 50) }}",
        "image": "{{ asset('logo.png') }}",
        "handler": function (response) {
            console.log('Razorpay payment successful:', response);
            
            // Add payment details to form
            const form = document.getElementById('registrationForm');
            
            // Remove any existing payment ID inputs to avoid duplicates
            const existingPaymentInput = form.querySelector('input[name="razorpay_payment_id"]');
            if (existingPaymentInput) {
                existingPaymentInput.remove();
            }
            
            const paymentIdInput = document.createElement('input');
            paymentIdInput.type = 'hidden';
            paymentIdInput.name = 'razorpay_payment_id';
            paymentIdInput.value = response.razorpay_payment_id;
            form.appendChild(paymentIdInput);
            
            console.log('Payment ID added to form:', response.razorpay_payment_id);
            
            // Show success message and submit via AJAX
            Swal.fire({
                title: 'Payment Successful!',
                text: 'Processing your registration...',
                icon: 'success',
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                console.log('About to submit form via AJAX');
                submitRegistrationForm();
            });
        },
        "prefill": {
            "name": userName,
            "email": userEmail,
            "contact": userPhone
        },
        "theme": {
            "color": "#2C2A4C"
        },
        "modal": {
            "ondismiss": function() {
                Swal.fire({
                    title: 'Payment Cancelled',
                    text: 'You can try again or choose to pay at the venue.',
                    icon: 'info'
                });
            }
        }
    };
    
    const rzp = new Razorpay(options);
    rzp.open();
}

// AJAX form submission function
function submitRegistrationForm() {
    const form = document.getElementById('registrationForm');
    const formData = new FormData(form);
    
    // Show loading
    Swal.fire({
        title: 'Submitting Registration...',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Registration response:', data);
        
        if (data.success) {
            showRegistrationSuccess(data.registration);
        } else {
            Swal.fire({
                title: 'Registration Failed',
                text: data.message || 'An error occurred during registration.',
                icon: 'error',
                confirmButtonColor: '#2C2A4C'
            });
        }
    })
    .catch(error => {
        console.error('Registration error:', error);
        Swal.fire({
            title: 'Registration Failed',
            text: 'A network error occurred. Please try again.',
            icon: 'error',
            confirmButtonColor: '#2C2A4C'
        });
    });
}

// Show detailed registration success alert
function showRegistrationSuccess(registration) {
    const paymentInfo = registration.payment_status === 'completed' 
        ? `<p><strong>Payment Status:</strong> <span style="color: #28a745;">✓ Completed</span></p>
           <p><strong>Payment ID:</strong> ${registration.payment_id}</p>
           <p><strong>Amount Paid:</strong> ₹${registration.payment_amount}</p>`
        : '<p><strong>Registration Type:</strong> <span style="color: #17a2b8;">Free Campaign</span></p>';
    
    Swal.fire({
        title: '<i class="fas fa-check-circle" style="color: #28a745;"></i> Registration Successful!',
        html: `
            <div style="text-align: left; margin: 20px 0;">
                <h5 style="color: #2C2A4C; margin-bottom: 15px;"><i class="fas fa-user"></i> Registration Details</h5>
                <p><strong>Registration ID:</strong> <span style="color: #E7A51B; font-weight: bold;">${registration.registration_id}</span></p>
                <p><strong>Patient Name:</strong> ${registration.patient_name}</p>
                <p><strong>Email:</strong> ${registration.email}</p>
                <p><strong>Phone:</strong> ${registration.phone_number}</p>
                
                <hr style="margin: 15px 0;">
                
                <h5 style="color: #2C2A4C; margin-bottom: 15px;"><i class="fas fa-calendar-alt"></i> Campaign Details</h5>
                <p><strong>Campaign:</strong> ${registration.campaign_title}</p>
                <p><strong>Date:</strong> ${registration.campaign_date}</p>
                <p><strong>Time:</strong> ${registration.campaign_time}</p>
                <p><strong>Location:</strong> ${registration.campaign_location}</p>
                <p><strong>Doctor:</strong> Dr. ${registration.doctor_name} (${registration.specialty})</p>
                
                <hr style="margin: 15px 0;">
                
                <h5 style="color: #2C2A4C; margin-bottom: 15px;"><i class="fas fa-credit-card"></i> Payment Information</h5>
                ${paymentInfo}
                
                <hr style="margin: 15px 0;">
                
                <h5 style="color: #2C2A4C; margin-bottom: 15px;"><i class="fas fa-list-ol"></i> Next Steps</h5>
                <ol style="text-align: left; padding-left: 20px;">
                    <li><strong>Save your Registration ID:</strong> ${registration.registration_id}</li>
                    <li><strong>Email Confirmation:</strong> You will receive confirmation shortly</li>
                    <li><strong>Bring ID Proof:</strong> Valid identification on campaign day</li>
                    <li><strong>Arrive Early:</strong> Report 15 minutes before scheduled time</li>
                    <li><strong>Contact:</strong> Our team will reach out with additional details</li>
                </ol>
                
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <p style="margin: 0; color: #6c757d; font-size: 0.9em;">
                        <i class="fas fa-info-circle"></i> 
                        Please save this information. You can also view your registration in "My Registrations" section.
                    </p>
                </div>
            </div>
        `,
        width: '600px',
        confirmButtonText: '<i class="fas fa-home"></i> Go to Dashboard',
        showCancelButton: true,
        cancelButtonText: '<i class="fas fa-list"></i> View More Campaigns',
        confirmButtonColor: '#2C2A4C',
        cancelButtonColor: '#E7A51B',
        allowOutsideClick: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("user.dashboard") }}';
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            window.location.href = '{{ route("user.campaigns") }}';
        }
    });
}

// Form submission for free campaigns
document.addEventListener('DOMContentLoaded', function() {
    const campaignPayment = {{ $campaign->registration_payment ?? 0 }};
    
    // Add form submission handler for both free and paid campaigns
    document.getElementById('registrationForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Always prevent default and use AJAX
        
        // Check if this is a payment completion (has razorpay_payment_id)
        const hasPaymentId = this.querySelector('input[name="razorpay_payment_id"]');
        
        // For paid campaigns without payment ID, don't submit
        if (campaignPayment > 0 && (!hasPaymentId || !hasPaymentId.value)) {
            console.log('Paid campaign requires payment first');
            return false;
        }
        
        // Validate form for free campaigns or after payment
        if (!validateForm()) {
            Swal.fire({
                title: 'Validation Error',
                text: 'Please fill in all required fields correctly.',
                icon: 'error'
            });
            return false;
        }
        
        // Submit via AJAX
        submitRegistrationForm();
    });
});

// Real-time validation
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    input.addEventListener('input', function() {
        if (this.classList.contains('is-invalid') && this.value.trim()) {
            this.classList.remove('is-invalid');
        }
    });
});

</script>
@endpush
