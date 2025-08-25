@extends('user.master')

@section('title', 'Organization Camp Request - FreeDoctor')

@push('styles')
<style>
    :root {
        --primary-color: #ffc107;
        --primary-dark: #e6ac00;
        --secondary-color: #343a40;
        --accent-color: #F8F9FA;
        --text-primary: #343a40;
        --text-secondary: #6c757d;
        --border-color: #dee2e6;
        --shadow-light: 0 2px 8px rgba(52, 58, 64, 0.08);
        --shadow-medium: 0 4px 20px rgba(52, 58, 64, 0.12);
        --shadow-heavy: 0 8px 30px rgba(52, 58, 64, 0.16);
        --gradient-primary: linear-gradient(135deg, #ffc107 0%, #e6ac00 100%);
        --gradient-secondary: linear-gradient(135deg, #343a40 0%, #495057 100%);
        --gradient-dark: linear-gradient(135deg, #343a40 0%, #212529 100%);
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        width: 100%;
        max-width: 100vw;
    }

    .professional-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #fff3cd 20%, #f8f9fa 100%);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100%;
        max-width: 100vw;
        overflow-x: hidden;
        margin: 0;
        box-sizing: border-box;
    }

    .main-card {
        background: white;
        border-radius: 24px;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 2rem;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .main-card:hover {
        box-shadow: var(--shadow-heavy);
        transform: translateY(-4px);
    }

    .hero-section {
        background: var(--gradient-secondary);
        padding: 4rem 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .hero-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
        position: relative;
        z-index: 1;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }

    .cta-button {
        background: var(--gradient-primary);
        color: var(--secondary-color);
        padding: 1rem 2.5rem;
        border-radius: 16px;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(255, 193, 7, 0.3);
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cta-button:hover {
        background: linear-gradient(135deg, #e6ac00 0%, #cc9a00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(255, 193, 7, 0.4);
        color: var(--secondary-color);
        text-decoration: none;
    }

    .trust-indicators {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .benefit-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .benefit-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .benefit-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-medium);
    }

    .benefit-card:hover::before {
        opacity: 1;
    }

    .benefit-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: var(--secondary-color);
        font-size: 1.5rem;
    }

    .benefit-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .benefit-description {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .process-section {
        background: var(--accent-color);
        padding: 3rem 2rem;
    }

    .process-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 3rem;
    }

    .process-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        /* max-width: 1200px; */
        margin: 0 auto;
    }

    .process-step {
        text-align: center;
        position: relative;
    }

    .process-step-number {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: var(--secondary-color);
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
        z-index: 2;
    }

    .process-step::after {
        content: '';
        position: absolute;
        top: 30px;
        left: calc(100% - 30px);
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        z-index: 1;
    }

    .process-step:last-child::after {
        display: none;
    }

    .process-step-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .process-step-description {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .form-section {
        background: white;
        padding: 3rem 2rem;
        border-radius: 24px;
        margin-top: 2rem;
        box-shadow: var(--shadow-medium);
        border: 1px solid var(--border-color);
    }

    .form-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        color: var(--text-primary);
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: center;
    }

    .btn-secondary {
        background: var(--secondary-color);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #495057;
        transform: translateY(-1px);
        color: white;
        text-decoration: none;
    }

    .stats-section {
        background: var(--gradient-primary);
        padding: 2rem;
        color: var(--secondary-color);
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .professional-container {
            padding: 0.5rem 0;
            margin: 0;
        }
        
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            margin: 0;
            width: 100%;
            max-width: 100%;
        }
        
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100%;
        }
        
        .col-12 {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .hero-section {
            padding: 2rem 1rem;
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
            padding: 0 0.5rem;
        }
        
        .benefits-grid,
        .form-grid {
            grid-template-columns: 1fr;
            padding: 1rem 0.5rem;
        }
        
        .process-step::after {
            display: none;
        }
        
        .trust-indicators {
            flex-direction: column;
            gap: 1rem;
            padding: 0 1rem;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .main-card {
            margin: 0 0 1rem 0;
            border-radius: 16px;
        }
        
        .process-section {
            padding: 2rem 1rem;
        }
        
        .form-section {
            padding: 2rem 1rem;
            margin: 1rem 0;
        }
        
        .stats-section {
            padding: 1.5rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .professional-container {
            padding: 0.25rem 0;
        }
        
        .container-fluid {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
        }
        
        .hero-title {
            font-size: 2rem;
            line-height: 1.2;
        }
        
        .hero-section {
            padding: 1.5rem 0.5rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
            padding: 0 0.25rem;
        }
        
        .benefits-grid {
            padding: 0.5rem 0.25rem;
            gap: 1rem;
        }
        
        .benefit-card {
            padding: 1.5rem 1rem;
        }
        
        .trust-indicators {
            padding: 0 0.5rem;
        }
        
        .process-section {
            padding: 1.5rem 0.5rem;
        }
        
        .form-section {
            padding: 1.5rem 0.5rem;
            margin: 0.5rem 0;
        }
        
        .stats-section {
            padding: 1rem 0.5rem;
        }
        
        .cta-button {
            padding: 0.875rem 1.5rem;
            font-size: 1rem;
        }
    }

    /* Desktop optimizations */
    @media (min-width: 1200px) {
        .professional-container {
            padding: 3rem 0;
        }
        
        .main-card {
            max-width: 1400px;
            margin: 0 auto 2rem;
        }
        
        .hero-section {
            padding: 5rem 3rem;
        }
        
        .benefits-grid {
            padding: 3rem;
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="professional-container">
    <div class="container-fluid px-3 px-md-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-11">
                <!-- Hero Section -->
                <div class="main-card fade-in">
                    <div class="hero-section">
                        <div class="hero-icon">
                            <i class="fas fa-hospital-alt text-3xl text-white"></i>
                        </div>
                        <h1 class="hero-title">Corporate Healthcare Solutions</h1>
                        <p class="hero-subtitle">
                            Comprehensive on-site medical services for your organization. 
                            Professional healthcare teams delivering quality medical care at your workplace.
                        </p>
                        
                        <!-- Trust Indicators -->
                        <div class="trust-indicators">
                            <div class="trust-item">
                                <i class="fas fa-certificate"></i>
                                <span>Licensed Medical Professionals</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>ISO 9001:2015 Certified</span>
                            </div>
                            <div class="trust-item">
                                <i class="fas fa-award"></i>
                                <span>500+ Organizations Served</span>
                            </div>
                        </div>
                        
                        <!-- Main CTA -->
                        <div style="margin-top: 2rem;">
                            @auth('user')
                                <button id="showFormBtn" class="cta-button">
                                    <i class="fas fa-calendar-plus"></i>Schedule Medical Camp
                                </button>
                            @else
                                <button onclick="showLoginPrompt()" class="cta-button">
                                    <i class="fas fa-sign-in-alt"></i>Login to Schedule Camp
                                </button>
                            @endauth
                        </div>
                    </div>

                    <!-- Statistics Section -->
                    <div class="stats-section">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">500+</div>
                                <div class="stat-label">Organizations Served</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">50,000+</div>
                                <div class="stat-label">Employees Screened</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">98%</div>
                                <div class="stat-label">Client Satisfaction</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">Medical Support</div>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Benefits Section -->
        <div class="main-card fade-in">
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h3 class="benefit-title">Certified Medical Professionals</h3>
                    <p class="benefit-description">
                        Board-certified doctors, specialist physicians, and trained nurses with extensive experience in occupational health and preventive medicine.
                    </p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-microscope"></i>
                    </div>
                    <h3 class="benefit-title">Advanced Diagnostic Equipment</h3>
                    <p class="benefit-description">
                        State-of-the-art portable diagnostic equipment including ECG machines, digital BP monitors, glucometers, and basic laboratory testing capabilities.
                    </p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="benefit-title">Improved Workplace Productivity</h3>
                    <p class="benefit-description">
                        Regular health screenings lead to early detection of health issues, reducing sick leave by up to 30% and improving overall employee productivity.
                    </p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3 class="benefit-title">Cost-Effective Healthcare</h3>
                    <p class="benefit-description">
                        Reduce healthcare costs by 40% compared to individual employee medical visits, while providing comprehensive preventive care services.
                    </p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="benefit-title">Flexible Scheduling</h3>
                    <p class="benefit-description">
                        Customizable scheduling options including weekends, evening hours, and multi-day camps to accommodate your organization's operational requirements.
                    </p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fas fa-file-medical-alt"></i>
                    </div>
                    <h3 class="benefit-title">Comprehensive Health Reports</h3>
                    <p class="benefit-description">
                        Detailed individual health reports and organizational health analytics to help HR departments make informed wellness program decisions.
                    </p>
                </div>
            </div>
        </div>

        <!-- Process Section -->
        <div class="main-card fade-in">
            <div class="process-section">
                <h2 class="process-title">How Our Medical Camp Service Works</h2>
                
                <div class="process-grid">
                    <div class="process-step">
                        <div class="process-step-number">1</div>
                        <h4 class="process-step-title">Initial Consultation</h4>
                        <p class="process-step-description">Submit your requirements and receive a detailed proposal with medical team specifications and cost breakdown</p>
                    </div>
                    
                    <div class="process-step">
                        <div class="process-step-number">2</div>
                        <h4 class="process-step-title">Medical Team Assignment</h4>
                        <p class="process-step-description">Our qualified medical professionals are assigned based on your specific needs and employee demographics</p>
                    </div>
                    
                    <div class="process-step">
                        <div class="process-step-number">3</div>
                        <h4 class="process-step-title">Pre-Camp Coordination</h4>
                        <p class="process-step-description">Site visit, equipment setup, and coordination with your HR team for smooth execution</p>
                    </div>
                    
                    <div class="process-step">
                        <div class="process-step-number">4</div>
                        <h4 class="process-step-title">Medical Camp Execution</h4>
                        <p class="process-step-description">Professional medical screening, consultations, and health assessments conducted on-site</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Camp Registration Form (Hidden by default) -->
        <div id="registrationForm" class="form-section fade-in" style="display: none;">
            <h2 class="form-title">Professional Medical Camp Registration</h2>
            <p class="text-center text-gray-600 mb-8 max-w-2xl mx-auto">
                Please provide detailed information about your organization and requirements. 
                Our medical team will contact you within 24 hours to discuss your needs.
            </p>

            <form action="{{ route('user.organization-camp-request.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() ?? 0 }}">

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Organization Name *</label>
                        <input type="text" name="organization_name" required
                               class="form-input" placeholder="Enter your organization name">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Primary Contact Email *</label>
                        <input type="email" name="email" required
                               class="form-input" placeholder="contact@organization.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Phone Number *</label>
                        <input type="tel" name="phone_number" required
                               class="form-input" placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Medical Camp Type *</label>
                        <select name="camp_request_type" required class="form-select">
                            <option value="">Select Camp Type</option>
                            <option value="medical">General Medical Screening</option>
                            <option value="surgical">Surgical Consultation Camp</option>
                            <option value="preventive">Preventive Health Checkup</option>
                            <option value="specialized">Specialized Health Camp</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Medical Specialty Required *</label>
                        <select name="specialty_id" required class="form-select">
                            <option value="">Select Medical Specialty</option>
                            @foreach(\App\Models\Specialty::all() as $specialty)
                                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Expected Number of Participants *</label>
                        <input type="number" name="number_of_people" min="10" max="1000" required
                               class="form-input" placeholder="e.g., 150">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferred Start Date *</label>
                        <input type="date" name="date_from" required min="{{ date('Y-m-d', strtotime('+3 days')) }}"
                               class="form-input">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Preferred End Date *</label>
                        <input type="date" name="date_to" required
                               class="form-input">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Organization Address/Location *</label>
                    <input type="text" name="location" required
                           class="form-input" 
                           placeholder="Complete address including city, state, and postal code">
                </div>

                <div class="form-group">
                    <label class="form-label">Additional Requirements & Facility Details *</label>
                    <textarea name="description" required rows="5"
                              class="form-textarea"
                              placeholder="Please describe:
• Specific health concerns or focus areas
• Available facilities (rooms, power, parking)
• Any special requirements or accommodations needed
• Preferred time slots or scheduling preferences
• Additional services required"></textarea>
                </div>

                <div class="form-actions">
                    <button type="button" id="cancelFormBtn" class="btn-secondary">
                        <i class="fas fa-times"></i>Cancel Request
                    </button>
                    <button type="submit" class="cta-button">
                        <i class="fas fa-paper-plane"></i>Submit Medical Camp Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Add smooth animations and interactions
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in animation to elements as they come into view
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all main cards
        document.querySelectorAll('.main-card').forEach(card => {
            observer.observe(card);
        });

        // Form validation and enhancement
        const form = document.querySelector('form');
        if (form) {
            // Add real-time validation
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', validateField);
                input.addEventListener('input', clearError);
            });

            // Date validation
            const startDate = form.querySelector('input[name="date_from"]');
            const endDate = form.querySelector('input[name="date_to"]');
            
            if (startDate && endDate) {
                startDate.addEventListener('change', function() {
                    endDate.min = this.value;
                    if (endDate.value && endDate.value < this.value) {
                        endDate.value = this.value;
                    }
                });
            }
        }
    });

    function validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        // Remove existing error styling
        field.classList.remove('error');
        
        // Validate based on field type
        let isValid = true;
        
        if (field.hasAttribute('required') && !value) {
            isValid = false;
        } else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } else if (field.type === 'tel' && value) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
            isValid = phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''));
        }
        
        if (!isValid) {
            field.classList.add('error');
            field.style.borderColor = '#EF4444';
        }
    }

    function clearError(event) {
        const field = event.target;
        if (field.classList.contains('error')) {
            field.classList.remove('error');
            field.style.borderColor = '';
        }
    }

    @guest
    function showLoginPrompt() {
        Swal.fire({
            title: 'Login Required',
            html: `
                <div style="text-align: left; margin: 1rem 0;">
                    <p style="margin-bottom: 1rem;">To schedule a medical camp for your organization, please:</p>
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin: 0.5rem 0; padding-left: 1rem; position: relative;">
                            <i class="fas fa-check" style="color: #ffc107; position: absolute; left: 0;"></i>
                            Login to your account
                        </li>
                        <li style="margin: 0.5rem 0; padding-left: 1rem; position: relative;">
                            <i class="fas fa-check" style="color: #ffc107; position: absolute; left: 0;"></i>
                            Fill out the medical camp request form
                        </li>
                        <li style="margin: 0.5rem 0; padding-left: 1rem; position: relative;">
                            <i class="fas fa-check" style="color: #ffc107; position: absolute; left: 0;"></i>
                            Get contacted by our medical team within 24 hours
                        </li>
                    </ul>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-sign-in-alt"></i> Login Now',
            cancelButtonText: 'Maybe Later',
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#343a40',
            customClass: {
                confirmButton: 'cta-button',
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("user.login") }}';
            }
        });
    }
    @endguest

    @auth('user')
    document.getElementById('showFormBtn').addEventListener('click', function () {
        const form = document.getElementById('registrationForm');
        form.style.display = 'block';
        form.classList.add('fade-in');
        
        // Smooth scroll to form
        setTimeout(() => {
            form.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    });

    document.getElementById('cancelFormBtn').addEventListener('click', function () {
        Swal.fire({
            title: 'Cancel Request?',
            text: 'Are you sure you want to cancel the medical camp request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Cancel',
            cancelButtonText: 'Continue Filling',
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('registrationForm').style.display = 'none';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });
    @endauth

    @if(session('success'))
    // Show enhanced success message
    Swal.fire({
        icon: 'success',
        title: 'Request Submitted Successfully!',
        html: `
            <div style="text-align: left; margin: 1rem 0;">
                <p style="margin-bottom: 1rem;"><strong>{{ session('success') }}</strong></p>
                <div style="background: #fff3cd; padding: 1rem; border-radius: 8px; border-left: 4px solid #ffc107;">
                    <h4 style="margin: 0 0 0.5rem 0; color: #856404;">What happens next?</h4>
                    <ul style="margin: 0; padding-left: 1rem; color: #343a40;">
                        <li>Our medical team will review your request within 24 hours</li>
                        <li>You'll receive a detailed proposal and cost estimate</li>
                        <li>We'll schedule a call to discuss your specific requirements</li>
                        <li>Confirmation with medical team details and schedule</li>
                    </ul>
                </div>
            </div>
        `,
        confirmButtonColor: '#ffc107',
        confirmButtonText: 'Got it!'
    });
    @endif

    // Add custom error styling
    const style = document.createElement('style');
    style.textContent = `
        .error {
            border-color: #EF4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }
        
        .swal2-popup {
            border-radius: 16px !important;
        }
        
        .swal2-confirm, .swal2-cancel {
            border-radius: 8px !important;
            font-weight: 600 !important;
        }
    `;
    document.head.appendChild(style);
</script>


@endsection