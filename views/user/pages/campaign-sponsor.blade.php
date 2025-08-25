@extends('user.layouts.app')

@section('title', 'Sponsor ' . $campaign->title)

@push('styles')
<style>
    :root {
        --primary-color: #2C2A4C;
        --secondary-color: #667eea;
        --sponsor-color: #fd7e14;
        --sponsor-secondary: #ffc107;
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

    .campaign-sponsor-container {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }

    .sponsor-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .campaign-header {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
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

    .sponsor-content {
        padding: 2rem;
    }

    .section-title {
        color: var(--sponsor-color);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sponsor-impact-section {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        border: 1px solid var(--sponsor-secondary);
    }

    .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .impact-item {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(253, 126, 20, 0.1);
        border: 2px solid rgba(253, 126, 20, 0.1);
    }

    .impact-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--sponsor-color);
        margin-bottom: 0.5rem;
    }

    .impact-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .sponsor-tiers {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .sponsor-tier {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 3px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .sponsor-tier:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .sponsor-tier.active {
        border-color: var(--sponsor-color);
        background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);
    }

    .tier-header {
        text-align: center;
        margin-bottom: 1rem;
    }

    .tier-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--sponsor-color);
        margin-bottom: 0.5rem;
    }

    .tier-amount {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .tier-description {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .tier-benefits {
        list-style: none;
        padding: 0;
        margin: 1rem 0;
    }

    .tier-benefits li {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        color: #495057;
    }

    .tier-benefits i {
        color: var(--success-color);
        font-size: 1rem;
    }

    .custom-amount-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        margin: 2rem 0;
    }

    .amount-input-group {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }

    .amount-prefix {
        background: var(--sponsor-color);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px 0 0 10px;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .amount-input {
        flex: 1;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-left: none;
        border-radius: 0 10px 10px 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .amount-input:focus {
        border-color: var(--sponsor-color);
        outline: none;
    }

    .quick-amounts {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .quick-amount-btn {
        background: var(--light-gray);
        border: 2px solid var(--border-color);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .quick-amount-btn:hover,
    .quick-amount-btn.active {
        background: var(--sponsor-color);
        color: white;
        border-color: var(--sponsor-color);
    }

    .sponsor-form-section {
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
        color: var(--sponsor-color);
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
        border-color: var(--sponsor-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(253, 126, 20, 0.1);
    }

    .sponsor-btn {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(253, 126, 20, 0.3);
    }

    .sponsor-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(253, 126, 20, 0.4);
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

    .trusted-section {
        background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
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

    @media (max-width: 768px) {
        .campaign-sponsor-container {
            padding: 1rem 0;
        }
        
        .sponsor-card {
            margin: 0 1rem;
        }
        
        .campaign-header {
            padding: 1.5rem;
        }
        
        .campaign-title {
            font-size: 1.5rem;
        }
        
        .sponsor-content {
            padding: 1.5rem;
        }
        
        .campaign-info {
            gap: 1rem;
        }
        
        .sponsor-tiers {
            grid-template-columns: 1fr;
        }
        
        .quick-amounts {
            justify-content: center;
        }
    }
</style>
@endpush

<div class="campaign-sponsor-container">
    <div class="container">
        <!-- Back Button -->
                                    <a href="{{ route('user.campaigns') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Campaigns
        </a>

        <div class="sponsor-card">
            <!-- Campaign Header -->
            <div class="campaign-header">
                <h1 class="campaign-title">{{ $campaign->title }}</h1>
                <p class="campaign-subtitle">Become a sponsor and make a difference in healthcare</p>
                <div class="campaign-info">
                    <div class="info-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ \Carbon\Carbon::parse($campaign->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($campaign->end_date)->format('M d, Y') }}</span>
                    </div>
                    @if($campaign->location)
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $campaign->location }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="sponsor-content">
                <!-- Impact Section -->
                <div class="sponsor-impact-section">
                    <h2 class="section-title">
                        <i class="fas fa-chart-line"></i>
                        Your Impact
                    </h2>
                    
                    <div class="impact-grid">
                        <div class="impact-item">
                            <div class="impact-number">{{ $campaign->max_participants ?? '100+' }}</div>
                            <div class="impact-label">People Helped</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-number">₹{{ number_format($campaign->sponsor ?? 0) }}</div>
                            <div class="impact-label">Already Sponsored</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-number">{{ $campaign->doctor ? 1 : 0 }}</div>
                            <div class="impact-label">Medical Expert{{ $campaign->doctor ? '' : 's' }}</div>
                        </div>
                        <div class="impact-item">
                            <div class="impact-number">3-5</div>
                            <div class="impact-label">Days Duration</div>
                        </div>
                    </div>

                    @if($campaign->description)
                    <div style="background: white; padding: 1.5rem; border-radius: 10px; border-left: 4px solid var(--sponsor-color);">
                        <h4 style="color: var(--sponsor-color); margin-bottom: 1rem;">About This Campaign</h4>
                        <p style="margin: 0; line-height: 1.6; color: #6c757d;">{{ $campaign->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Sponsorship Tiers -->
                <h2 class="section-title">
                    <i class="fas fa-star"></i>
                    Sponsorship Tiers
                </h2>

                <div class="sponsor-tiers">
                    <div class="sponsor-tier" onclick="selectTier(this, 500)">
                        <div class="tier-header">
                            <div class="tier-name">Bronze Sponsor</div>
                            <div class="tier-amount">₹500</div>
                            <div class="tier-description">Supporting healthcare access</div>
                        </div>
                        <ul class="tier-benefits">
                            <li><i class="fas fa-check"></i> Certificate of appreciation</li>
                            <li><i class="fas fa-check"></i> Social media recognition</li>
                            <li><i class="fas fa-check"></i> Campaign updates</li>
                        </ul>
                    </div>

                    <div class="sponsor-tier" onclick="selectTier(this, 1000)">
                        <div class="tier-header">
                            <div class="tier-name">Silver Sponsor</div>
                            <div class="tier-amount">₹1,000</div>
                            <div class="tier-description">Making healthcare affordable</div>
                        </div>
                        <ul class="tier-benefits">
                            <li><i class="fas fa-check"></i> All Bronze benefits</li>
                            <li><i class="fas fa-check"></i> Logo display at venue</li>
                            <li><i class="fas fa-check"></i> Detailed impact report</li>
                            <li><i class="fas fa-check"></i> Priority updates</li>
                        </ul>
                    </div>

                    <div class="sponsor-tier" onclick="selectTier(this, 2500)">
                        <div class="tier-header">
                            <div class="tier-name">Gold Sponsor</div>
                            <div class="tier-amount">₹2,500</div>
                            <div class="tier-description">Transforming community health</div>
                        </div>
                        <ul class="tier-benefits">
                            <li><i class="fas fa-check"></i> All Silver benefits</li>
                            <li><i class="fas fa-check"></i> Website recognition</li>
                            <li><i class="fas fa-check"></i> Video testimonials</li>
                            <li><i class="fas fa-check"></i> Personal thank you call</li>
                        </ul>
                    </div>

                    <div class="sponsor-tier" onclick="selectTier(this, 5000)">
                        <div class="tier-header">
                            <div class="tier-name">Platinum Sponsor</div>
                            <div class="tier-amount">₹5,000</div>
                            <div class="tier-description">Leading healthcare revolution</div>
                        </div>
                        <ul class="tier-benefits">
                            <li><i class="fas fa-check"></i> All Gold benefits</li>
                            <li><i class="fas fa-check"></i> Co-branding opportunities</li>
                            <li><i class="fas fa-check"></i> Press release inclusion</li>
                            <li><i class="fas fa-check"></i> Future collaboration priority</li>
                        </ul>
                    </div>
                </div>

                <!-- Custom Amount Section -->
                <div class="custom-amount-section">
                    <h3 style="color: var(--sponsor-color); margin-bottom: 1rem;">
                        <i class="fas fa-hand-holding-heart"></i>
                        Custom Sponsorship Amount
                    </h3>
                    <p style="color: #6c757d; margin-bottom: 1.5rem;">Choose your own sponsorship amount to make a personalized impact</p>
                    
                    <div class="amount-input-group">
                        <span class="amount-prefix">₹</span>
                        <input type="number" id="customAmount" class="amount-input" placeholder="Enter amount" min="100" step="50">
                    </div>
                    
                    <div class="quick-amounts">
                        <button class="quick-amount-btn" onclick="setAmount(250)">₹250</button>
                        <button class="quick-amount-btn" onclick="setAmount(750)">₹750</button>
                        <button class="quick-amount-btn" onclick="setAmount(1500)">₹1,500</button>
                        <button class="quick-amount-btn" onclick="setAmount(3000)">₹3,000</button>
                        <button class="quick-amount-btn" onclick="setAmount(10000)">₹10,000</button>
                    </div>
                </div>

                <!-- Sponsor Form -->
                <div class="sponsor-form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-plus"></i>
                        Sponsor Information
                    </h2>

                    <form id="sponsorForm" action="{{ route('user.campaigns.sponsor.store', $campaign->id) }}" method="POST">
                        @csrf
                        <input type="hidden" id="sponsorAmount" name="amount" value="">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Full Name / Organization *</label>
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
                                    <label for="organization" class="form-label">Organization (Optional)</label>
                                    <input type="text" id="organization" name="organization" class="form-control" 
                                           value="{{ old('organization') }}" placeholder="Company/Organization name">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="3" 
                                      placeholder="Enter your address for certificate delivery">{{ old('address') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="message" class="form-label">Message (Optional)</label>
                            <textarea id="message" name="message" class="form-control" rows="3" 
                                      placeholder="Any message or special instructions">{{ old('message') }}</textarea>
                        </div>

                        <!-- Sponsor Button -->
                        <button type="submit" class="sponsor-btn" id="sponsorSubmitBtn">
                            <i class="fas fa-heart me-2"></i>
                            Become a Sponsor
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
                        Your sponsorship makes a real difference in community healthcare
                    </p>
                    
                    <div class="trusted-features">
                        <div class="trusted-feature">
                            <i class="fas fa-chart-line"></i>
                            <h5>Transparent Impact</h5>
                            <p style="margin: 0; font-size: 0.9rem;">Track exactly how your sponsorship helps people</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-certificate"></i>
                            <h5>Tax Benefits</h5>
                            <p style="margin: 0; font-size: 0.9rem;">Get proper documentation for tax deductions</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-handshake"></i>
                            <h5>Direct Impact</h5>
                            <p style="margin: 0; font-size: 0.9rem;">100% of sponsorship goes to healthcare services</p>
                        </div>
                        
                        <div class="trusted-feature">
                            <i class="fas fa-users"></i>
                            <h5>Community Building</h5>
                            <p style="margin: 0; font-size: 0.9rem;">Join a network of healthcare champions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedAmount = 0;

function selectTier(tierElement, amount) {
    // Remove active class from all tiers
    document.querySelectorAll('.sponsor-tier').forEach(tier => {
        tier.classList.remove('active');
    });
    
    // Add active class to selected tier
    tierElement.classList.add('active');
    
    // Set the amount
    selectedAmount = amount;
    document.getElementById('sponsorAmount').value = amount;
    document.getElementById('customAmount').value = amount;
    
    // Update button text
    updateButtonText();
}

function setAmount(amount) {
    // Remove active class from all tiers
    document.querySelectorAll('.sponsor-tier').forEach(tier => {
        tier.classList.remove('active');
    });
    
    // Remove active class from quick amount buttons
    document.querySelectorAll('.quick-amount-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Add active class to clicked button
    event.target.classList.add('active');
    
    selectedAmount = amount;
    document.getElementById('sponsorAmount').value = amount;
    document.getElementById('customAmount').value = amount;
    
    updateButtonText();
}

document.getElementById('customAmount').addEventListener('input', function() {
    const amount = parseInt(this.value) || 0;
    selectedAmount = amount;
    document.getElementById('sponsorAmount').value = amount;
    
    // Remove active from all tiers and quick buttons
    document.querySelectorAll('.sponsor-tier, .quick-amount-btn').forEach(el => {
        el.classList.remove('active');
    });
    
    updateButtonText();
});

function updateButtonText() {
    const btn = document.getElementById('sponsorSubmitBtn');
    if (selectedAmount > 0) {
        btn.innerHTML = `<i class="fas fa-heart me-2"></i>Sponsor with ₹${selectedAmount.toLocaleString()}`;
    } else {
        btn.innerHTML = '<i class="fas fa-heart me-2"></i>Become a Sponsor';
    }
}

document.getElementById('sponsorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (selectedAmount < 100) {
        Swal.fire({
            title: 'Invalid Amount',
            text: 'Please select a sponsorship amount of at least ₹100',
            icon: 'warning',
            confirmButtonColor: '#fd7e14'
        });
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('sponsorSubmitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    submitBtn.disabled = true;
    
    // Simulate form submission
    setTimeout(() => {
        Swal.fire({
            title: 'Thank You for Your Sponsorship!',
            text: `Your sponsorship of ₹${selectedAmount.toLocaleString()} is being processed. You will receive a confirmation email shortly.`,
            icon: 'success',
            confirmButtonColor: '#fd7e14'
        }).then(() => {
            window.location.href = '{{ route("campaigns.index") }}';
        });
    }, 2000);
});
</script>
@endpush
