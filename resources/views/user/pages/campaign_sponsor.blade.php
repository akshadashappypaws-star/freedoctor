@extends('../user.master')

@section('title', 'Sponsor Campaign - FreeDoctor')

@section('content')

<style>
/* Campaign Sponsor Page Styles */
:root {
    --primary-color: #2C2A4C;
    --secondary-color: #E7A51B;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
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

.sponsor-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #4a47a3 100%);
    color: var(--white);
    padding: 3rem 0 2rem;
    position: relative;
    overflow: hidden;
}

.sponsor-header::before {
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

.progress-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.progress-title {
    color: var(--primary-color);
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.progress-item {
    margin-bottom: 1.5rem;
}

.progress-item:last-child {
    margin-bottom: 0;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.progress-label {
    font-weight: 600;
    color: var(--text-dark);
}

.progress-value {
    font-weight: 700;
    color: var(--primary-color);
}

.progress-bar {
    height: 10px;
    background: #e9ecef;
    border-radius: 5px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.progress-fill {
    height: 100%;
    border-radius: 5px;
    transition: width 0.8s ease;
}

.sponsorship-progress {
    background: linear-gradient(90deg, var(--warning-color), #fd7e14);
}

.registration-progress {
    background: linear-gradient(90deg, var(--success-color), #20c997);
}

.sponsor-tiers-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
    border: 1px solid var(--border-color);
}

.tiers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.tier-card {
    background: var(--white);
    border: 2px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.tier-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.tier-card:hover {
    border-color: var(--primary-color);
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.tier-card:hover::before {
    transform: scaleX(1);
}

.tier-card.selected {
    border-color: var(--primary-color);
    background: #f0f0ff;
    box-shadow: var(--shadow-md);
}

.tier-card.selected::before {
    transform: scaleX(1);
}

.tier-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--primary-color);
}

.tier-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.tier-amount {
    font-size: 2rem;
    font-weight: 700;
    color: var(--secondary-color);
    margin-bottom: 1rem;
}

.tier-benefits {
    list-style: none;
    padding: 0;
    margin: 0;
}

.tier-benefits li {
    padding: 0.25rem 0;
    color: var(--text-dark);
    font-size: 0.9rem;
}

.tier-benefits li i {
    color: var(--success-color);
    margin-right: 0.5rem;
}

.custom-amount-section {
    background: linear-gradient(135deg, var(--light-gray) 0%, #e9ecef 100%);
    border-radius: var(--radius-md);
    padding: 1.5rem;
    margin-top: 1.5rem;
    border: 2px dashed var(--border-color);
}

.custom-amount-section.active {
    border-color: var(--primary-color);
    background: #f0f0ff;
}

.sponsor-form-card {
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

.sponsor-summary {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #ffc107 100%);
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

.summary-amount {
    font-size: 3rem;
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

.btn-warning {
    background: var(--warning-color);
    color: var(--text-dark);
}

.btn-warning:hover {
    background: #e0a800;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .sponsor-header {
        padding: 2rem 0 1rem;
    }
    
    .campaign-info-card,
    .sponsor-tiers-card,
    .sponsor-form-card {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .campaign-details {
        grid-template-columns: 1fr;
    }
    
    .tiers-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .summary-amount {
        font-size: 2rem;
    }
}
</style>

<!-- Sponsor Header -->
<section class="sponsor-header">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1>Sponsor Campaign</h1>
                <p>Support healthcare initiatives and make a difference in your community</p>
            </div>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <strong>Thank You!</strong> {{ session('success') }}
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
            <div class="col-lg-10">
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
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <strong>Location:</strong><br>
                                {{ $campaign->location }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-bullseye"></i>
                            <div>
                                <strong>Target Amount:</strong><br>
                                ₹{{ number_format($campaign->target_amount ?? 50000) }}
                            </div>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <strong>Expected Patients:</strong><br>
                                {{ $campaign->expected_patients ?? 100 }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="campaign-description">
                        <h5>About this Campaign:</h5>
                        <p>{{ $campaign->description }}</p>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="progress-section">
                    <h3 class="progress-title">Campaign Progress</h3>
                    
                    @php
                        $totalSponsored = $campaign->campaignSponsors ? $campaign->campaignSponsors->sum('amount') : 0;
                        $targetAmount = $campaign->target_amount ?? 50000;
                        $sponsorshipProgress = min(($totalSponsored / $targetAmount) * 100, 100);
                        
                        $totalRegistered = $campaign->patientRegistrations ? $campaign->patientRegistrations->count() : 0;
                        $expectedPatients = $campaign->expected_patients ?? 100;
                        $registrationProgress = min(($totalRegistered / $expectedPatients) * 100, 100);
                    @endphp
                    
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">Sponsorship Progress</span>
                            <span class="progress-value">₹{{ number_format($totalSponsored) }} / ₹{{ number_format($targetAmount) }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill sponsorship-progress" style="width: {{ $sponsorshipProgress }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($sponsorshipProgress, 1) }}% of target reached</small>
                    </div>
                    
                    <div class="progress-item">
                        <div class="progress-header">
                            <span class="progress-label">Patient Registrations</span>
                            <span class="progress-value">{{ $totalRegistered }} / {{ $expectedPatients }}</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill registration-progress" style="width: {{ $registrationProgress }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($registrationProgress, 1) }}% capacity filled</small>
                    </div>
                </div>

                <!-- Sponsor Tiers -->
                <div class="sponsor-tiers-card">
                    <h3 class="form-section-title">Choose Your Sponsorship Level</h3>
                    
                    <div class="tiers-grid">
                        <!-- Bronze Tier -->
                        <div class="tier-card" data-tier="bronze" data-amount="1000">
                            <div class="tier-icon">🥉</div>
                            <div class="tier-name">Bronze Sponsor</div>
                            <div class="tier-amount">₹1,000</div>
                            <ul class="tier-benefits">
                                <li><i class="fas fa-check"></i> Certificate of Appreciation</li>
                                <li><i class="fas fa-check"></i> Social Media Recognition</li>
                                <li><i class="fas fa-check"></i> Thank You Email</li>
                            </ul>
                        </div>
                        
                        <!-- Silver Tier -->
                        <div class="tier-card" data-tier="silver" data-amount="5000">
                            <div class="tier-icon">🥈</div>
                            <div class="tier-name">Silver Sponsor</div>
                            <div class="tier-amount">₹5,000</div>
                            <ul class="tier-benefits">
                                <li><i class="fas fa-check"></i> All Bronze Benefits</li>
                                <li><i class="fas fa-check"></i> Logo on Campaign Materials</li>
                                <li><i class="fas fa-check"></i> Monthly Impact Reports</li>
                                <li><i class="fas fa-check"></i> VIP Event Access</li>
                            </ul>
                        </div>
                        
                        <!-- Gold Tier -->
                        <div class="tier-card" data-tier="gold" data-amount="10000">
                            <div class="tier-icon">🥇</div>
                            <div class="tier-name">Gold Sponsor</div>
                            <div class="tier-amount">₹10,000</div>
                            <ul class="tier-benefits">
                                <li><i class="fas fa-check"></i> All Silver Benefits</li>
                                <li><i class="fas fa-check"></i> Prominent Logo Placement</li>
                                <li><i class="fas fa-check"></i> Press Release Mention</li>
                                <li><i class="fas fa-check"></i> Direct Doctor Interaction</li>
                                <li><i class="fas fa-check"></i> Tax Exemption Certificate</li>
                            </ul>
                        </div>
                        
                        <!-- Platinum Tier -->
                        <div class="tier-card" data-tier="platinum" data-amount="25000">
                            <div class="tier-icon">💎</div>
                            <div class="tier-name">Platinum Sponsor</div>
                            <div class="tier-amount">₹25,000</div>
                            <ul class="tier-benefits">
                                <li><i class="fas fa-check"></i> All Gold Benefits</li>
                                <li><i class="fas fa-check"></i> Campaign Co-branding</li>
                                <li><i class="fas fa-check"></i> Media Interview Opportunity</li>
                                <li><i class="fas fa-check"></i> Exclusive Impact Video</li>
                                <li><i class="fas fa-check"></i> Annual Recognition Award</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Custom Amount Section -->
                    <div class="custom-amount-section" id="customAmountSection">
                        <h4 style="margin-bottom: 1rem; color: var(--primary-color);">
                            <i class="fas fa-hand-holding-heart"></i>
                            Custom Amount
                        </h4>
                        <p>Want to contribute a different amount? Enter your preferred sponsorship amount below.</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Custom Amount (₹)</label>
                                    <input type="number" id="customAmount" class="form-control" min="100" placeholder="Enter amount">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sponsor Name (Optional)</label>
                                    <input type="text" id="customSponsorName" class="form-control" placeholder="Your organization name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sponsor Form -->
                <div class="sponsor-form-card">
                    <form id="sponsorForm" action="{{ route('user.campaigns.sponsor.store', $campaign->id) }}" method="POST">
                        @csrf
                        
                        <!-- Sponsor Information -->
                        <div class="form-section">
                            <h3 class="form-section-title">Sponsor Information</h3>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name / Organization <span class="required">*</span></label>
                                        <input type="text" name="name" class="form-control" required 
                                               value="{{ old('name', auth()->user()->name ?? '') }}">
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}">
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number <span class="required">*</span></label>
                                        <input type="tel" name="phone_number" class="form-control" required 
                                               value="{{ old('phone_number', auth()->user()->phone ?? '') }}">
                                        @error('phone_number')
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
                            
                            <div class="form-group">
                                <label class="form-label">Message / Motivation (Optional)</label>
                                <textarea name="message" class="form-control" rows="3" 
                                          placeholder="Share why you want to sponsor this campaign...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sponsorship Summary -->
                        <div class="sponsor-summary" id="sponsorSummary" style="display: none;">
                            <div class="summary-title">Sponsorship Summary</div>
                            <div class="summary-amount" id="summaryAmount">₹0</div>
                            <div class="summary-details">
                                <div class="summary-item">
                                    <strong>Tier:</strong><br>
                                    <span id="summaryTier">-</span>
                                </div>
                                <div class="summary-item">
                                    <strong>Tax Benefit:</strong><br>
                                    <span id="summaryTax">80G Available</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="form-section">
                            <h3 class="form-section-title">Payment Method</h3>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Secure Online Payment Only:</strong> All sponsorship payments are processed securely through our online payment gateway using Credit/Debit Cards, UPI, or Net Banking.
                            </div>
                            
                            <div class="payment-methods">
                                <div class="payment-method selected" data-method="razorpay">
                                    <i class="fas fa-credit-card"></i>
                                    <h5>Secure Online Payment</h5>
                                    <p>Credit/Debit Card, UPI, Net Banking</p>
                                    <small class="text-success">
                                        <i class="fas fa-shield-alt"></i> 256-bit SSL Encrypted
                                    </small>
                                </div>
                            </div>
                            
                            <input type="hidden" name="payment_method" id="payment_method" value="razorpay">
                            <input type="hidden" name="amount" id="sponsorship_amount">
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="terms-section">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agreeTerms" name="agree_terms" required>
                                <label class="form-check-label" for="agreeTerms">
                                    I agree to the <a href="#" target="_blank">Terms and Conditions</a> and understand that:
                                    <ul class="mt-2">
                                        <li>My sponsorship will be used for this campaign only</li>
                                        <li>I will receive regular updates on campaign progress</li>
                                        <li>Tax exemption certificates will be provided for eligible amounts</li>
                                        <li>I consent to being recognized as a sponsor (unless anonymous)</li>
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
                            
                            <button type="button" id="proceedSponsor" class="btn btn-warning" disabled>
                                <i class="fas fa-hand-holding-heart"></i>
                                Proceed to Sponsor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let selectedAmount = 0;
let selectedTier = '';

// Tier selection
document.querySelectorAll('.tier-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.tier-card').forEach(c => c.classList.remove('selected'));
        document.getElementById('customAmountSection').classList.remove('active');
        document.getElementById('customAmount').value = '';
        
        this.classList.add('selected');
        selectedAmount = parseInt(this.dataset.amount);
        selectedTier = this.dataset.tier;
        
        updateSponsorSummary();
    });
});

// Custom amount selection
document.getElementById('customAmount').addEventListener('input', function() {
    const amount = parseInt(this.value);
    if (amount >= 100) {
        document.querySelectorAll('.tier-card').forEach(c => c.classList.remove('selected'));
        document.getElementById('customAmountSection').classList.add('active');
        
        selectedAmount = amount;
        selectedTier = 'custom';
        
        updateSponsorSummary();
    } else {
        selectedAmount = 0;
        selectedTier = '';
        hideSponsorSummary();
    }
});

function updateSponsorSummary() {
    if (selectedAmount > 0) {
        document.getElementById('sponsorSummary').style.display = 'block';
        document.getElementById('summaryAmount').textContent = `₹${selectedAmount.toLocaleString()}`;
        
        let tierName = '';
        switch(selectedTier) {
            case 'bronze': tierName = 'Bronze Sponsor'; break;
            case 'silver': tierName = 'Silver Sponsor'; break;
            case 'gold': tierName = 'Gold Sponsor'; break;
            case 'platinum': tierName = 'Platinum Sponsor'; break;
            case 'custom': tierName = 'Custom Amount'; break;
        }
        
        document.getElementById('summaryTier').textContent = tierName;
        document.getElementById('summaryTax').textContent = selectedAmount >= 2000 ? '80G Available' : 'Not Applicable';
        
        // Update hidden form fields
        document.getElementById('sponsorship_amount').value = selectedAmount;
        
        // Enable sponsor button
        document.getElementById('proceedSponsor').disabled = false;
    } else {
        hideSponsorSummary();
    }
}

function hideSponsorSummary() {
    document.getElementById('sponsorSummary').style.display = 'none';
    document.getElementById('proceedSponsor').disabled = true;
}

// Form validation
function validateForm() {
    const form = document.getElementById('sponsorForm');
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
    
    // Amount validation
    if (selectedAmount <= 0) {
        showGlobalError('Please select a sponsorship tier or enter a custom amount');
        isValid = false;
    }
    
    // Terms validation
    const agreeTerms = form.querySelector('#agreeTerms');
    if (!agreeTerms.checked) {
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

function showGlobalError(message) {
    Swal.fire({
        title: 'Validation Error',
        text: message,
        icon: 'error'
    });
}

// Sponsor processing
document.getElementById('proceedSponsor').addEventListener('click', function() {
    if (!validateForm()) {
        Swal.fire({
            title: 'Validation Error',
            text: 'Please fill in all required fields correctly.',
            icon: 'error'
        });
        return;
    }
    
    // Only online payment is allowed
    initiateRazorpayPayment();
});

function initiateRazorpayPayment() {
    const amount = selectedAmount * 100; // Convert to paise
    const userEmail = document.querySelector('[name="email"]').value;
    const sponsorName = document.querySelector('[name="name"]').value;
    const userPhone = document.querySelector('[name="phone_number"]').value;
    
    const options = {
        "key": "{{ config('services.razorpay.key') }}", // Your Razorpay key
        "amount": amount,
        "currency": "INR",
        "name": "FreeDoctor",
        "description": "Campaign Sponsorship: {{ Str::limit($campaign->title, 50) }}",
        "image": "{{ asset('logo.png') }}",
        "handler": function (response) {
            // Add payment details to form
            const form = document.getElementById('sponsorForm');
            
            const paymentIdInput = document.createElement('input');
            paymentIdInput.type = 'hidden';
            paymentIdInput.name = 'razorpay_payment_id';
            paymentIdInput.value = response.razorpay_payment_id;
            form.appendChild(paymentIdInput);
            
            // Submit the form
            form.submit();
        },
        "prefill": {
            "name": sponsorName,
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
                    text: 'You can try again or choose bank transfer option.',
                    icon: 'info'
                });
            }
        }
    };
    
    const rzp = new Razorpay(options);
    rzp.open();
}

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
