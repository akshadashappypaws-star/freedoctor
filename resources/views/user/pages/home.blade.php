@extends('user.master')

@section('title', 'Connecting Doctors, Patients & Communities - FreeDoctor')

@section('content')
<style>
    .notificationForm{
        display:block;
    }
</style>

<!-- Material UI CDN -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<!-- Home Page CSS -->
<link rel="stylesheet" href="{{ asset('css/home.css') }}">

<div class="main-content">
    <!-- 1. Hero Section -->
    <section class="hero-section">
        <div class="hero-container">
            <div class="hero-text">
                <h1 class="hero-headline">Connecting Doctors, Patients & Communities through Medical Camps</h1>
                <p class="hero-subheadline">Discover, host, or sponsor healthcare camps in your city – faster, easier, and more impactful.</p>
                <div class="hero-buttons">
                    <a href="{{ route('user.campaigns') }}" class="hero-btn hero-btn-primary">
                        <span class="material-icons">search</span>
                        Explore Camps Near You
                    </a>
                    <a href="javascript:void(0)" onclick="handleDoctorCampPost()" class="hero-btn hero-btn-secondary">
                        <span class="material-icons">add_circle</span>
                        Post Your Camp (Doctors)
                    </a>
                    <a href="{{ route('user.organization-camp-request') }}" class="hero-btn hero-btn-tertiary">
                        <span class="material-icons">business</span>
                        Request a Camp (Industries)
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('storage/campaigns/home/campaign.jpeg') }}" alt="Medical Camp Community" 
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'600\' height=\'400\' viewBox=\'0 0 600 400\'%3E%3Crect width=\'600\' height=\'400\' fill=\'%23f8f9fa\'/%3E%3Cpath d=\'M100 120l80 60 120-80 80 60v140H100z\' fill=\'%23dee2e6\'/%3E%3Ccircle cx=\'200\' cy=\'160\' r=\'30\' fill=\'%23adb5bd\'/%3E%3Ctext x=\'300\' y=\'220\' text-anchor=\'middle\' fill=\'%236c757d\' font-family=\'Arial\' font-size=\'24\'%3EMedical Camp Community%3C/text%3E%3C/svg%3E'">
            </div>
        </div>
    </section>

    <!-- 2. Notification / Interest Form -->
    <section class="notification-section">
        <div class="notification-toggle" onclick="toggleNotificationForm()">
            <span class="material-icons">notifications</span>
            <span>🔔 Get notified when a camp is live near you</span>
            <span class="material-icons toggle-icon">expand_more</span>
        </div>
        <div class="notification-form" id="notificationForm">
            <h3>Get Notified When Your Camp is Available</h3>
                        <form action="{{ route('user.notify-me.store') }}" method="POST" class="notify-form">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="tel" id="mobile" name="mobile" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location (City/Area)</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Camp Interest Category</label>
                        <select id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Dental">Dental</option>
                            <option value="Eye">Eye Care</option>
                            <option value="General Health">General Health</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Orthopedic">Orthopedic</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="Pediatric">Pediatric</option>
                            <option value="Women's Health">Women's Health</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="notify-btn">Notify Me</button>
            </form>
        </div>
    </section>

    <!-- 3. User-Type-Based Cards -->
    <section class="user-cards-section">
        <div class="user-cards-grid">
            <div class="user-card" onclick="window.location.href='{{ route('user.campaigns') }}'">
                <div class="card-icon">
                    <span class="material-icons">person_search</span>
                </div>
                <div class="card-content">
                    <h3>For Patients</h3>
                    <p>Find health camps in your area – register in seconds.</p>
                    <div class="card-stat">Over 1,200 people have attended camps through our network.</div>
                    <div class="card-cta">Explore Camps</div>
                </div>
            </div>
            
            <div class="user-card" onclick="window.location.href='{{ route('user.organization-camp-request') }}'">
                <div class="card-icon">
                    <span class="material-icons">medical_services</span>
                </div>
                <div class="card-content">
                    <h3>For Doctors</h3>
                    <p>Host camps without the hassle – reach your patients faster.</p>
                    <div class="card-stat">Over 350 doctors onboarded.</div>
                    <div class="card-cta">Post a Camp</div>
                </div>
            </div>
            
            <div class="user-card" onclick="window.location.href='{{ route('user.organization-camp-request') }}'">
                <div class="card-icon">
                    <span class="material-icons">business</span>
                </div>
                <div class="card-content">
                    <h3>For Industries & Apartments</h3>
                    <p>Bring healthcare to your workplace or community.</p>
                    <div class="card-stat">50+ corporate health initiatives delivered.</div>
                    <div class="card-cta">Request a Camp</div>
                </div>
            </div>
            
            <div class="user-card" onclick="window.location.href='{{ route('user.campaigns') }}'">
                <div class="card-icon">
                    <span class="material-icons">volunteer_activism</span>
                </div>
                <div class="card-content">
                    <h3>For Sponsors</h3>
                    <p>Make a difference – sponsor health camps in need.</p>
                    <div class="card-stat">Over ₹5L raised for community health.</div>
                    <div class="card-cta">Sponsor a Camp</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Trust-Building Block -->
    <section class="trust-section">
        <div class="trust-container">
            <div class="trust-counters">
                <h2>Trusted by Thousands</h2>
                <div class="counters-grid">
                    <div class="counter-item">
                        <div class="counter-number" data-target="150">0</div>
                        <div class="counter-label">Total Camps Conducted</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="2500">0</div>
                        <div class="counter-label">Total Patients Served</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="350">0</div>
                        <div class="counter-label">Total Doctors Onboard</div>
                    </div>
                    <div class="counter-item">
                        <div class="counter-number" data-target="8">₹0L</div>
                        <div class="counter-label">Total Sponsors Contributed</div>
                    </div>
                </div>
            </div>
            
            <div class="trust-content">
                <div class="trust-logos">
                    <h3>Our Partners</h3>
                    <div class="logos-grid">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='60' viewBox='0 0 120 60'%3E%3Crect width='120' height='60' fill='%23e9ecef'/%3E%3Ctext x='60' y='35' text-anchor='middle' fill='%236c757d' font-family='Arial' font-size='14'%3EPartner Hospital%3C/text%3E%3C/svg%3E" alt="Partner Hospital">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='60' viewBox='0 0 120 60'%3E%3Crect width='120' height='60' fill='%23e9ecef'/%3E%3Ctext x='60' y='35' text-anchor='middle' fill='%236c757d' font-family='Arial' font-size='14'%3ENGO Partner%3C/text%3E%3C/svg%3E" alt="NGO Partner">
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='60' viewBox='0 0 120 60'%3E%3Crect width='120' height='60' fill='%23e9ecef'/%3E%3Ctext x='60' y='35' text-anchor='middle' fill='%236c757d' font-family='Arial' font-size='14'%3ECorporate%3C/text%3E%3C/svg%3E" alt="Corporate Partner">
                    </div>
                </div>
                
                <div class="testimonials-carousel">
                    <h3>What People Say</h3>
                    <div class="testimonials-slider" id="testimonialsSlider">
                        <div class="testimonial-card active">
                            <p>"FreeDoctor helped me get quality healthcare at an affordable price. The doctors were professional and caring."</p>
                            <div class="testimonial-author">
                                <strong>Priya Sharma</strong> - Delhi, Age 34
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <p>"As a doctor, hosting camps through FreeDoctor has been incredibly rewarding. Easy to reach patients who need care."</p>
                            <div class="testimonial-author">
                                <strong>Dr. Rajesh Kumar</strong> - Mumbai, Age 42
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <p>"Our company organized a health camp for employees. Excellent organization and great impact on our team's health."</p>
                            <div class="testimonial-author">
                                <strong>Meera Patel</strong> - Bangalore, Age 38
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-dots">
                        <span class="dot active" onclick="currentTestimonial(1)"></span>
                        <span class="dot" onclick="currentTestimonial(2)"></span>
                        <span class="dot" onclick="currentTestimonial(3)"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. How It Works -->
    <section class="how-it-works-section">
        <h2>How It Works</h2>
        <div class="steps-container">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-icon">
                    <span class="material-icons">search</span>
                </div>
                <h3>Post / Request / Explore</h3>
                <p>Choose your role and take action - post a camp, request one, or explore available options</p>
            </div>
            <div class="step-arrow">
                <span class="material-icons">arrow_forward</span>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-icon">
                    <span class="material-icons">verified</span>
                </div>
                <h3>Get Matched & Verified</h3>
                <p>Our system matches you with verified doctors and validates all camp details</p>
            </div>
            <div class="step-arrow">
                <span class="material-icons">arrow_forward</span>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-icon">
                    <span class="material-icons">local_hospital</span>
                </div>
                <h3>Attend & Benefit</h3>
                <p>Attend the camp and receive quality healthcare at affordable prices</p>
            </div>
        </div>
    </section>

    <!-- 6. Referral Program Teaser -->
    <section class="referral-section">
        <div class="referral-banner">
            <div class="referral-content">
                <span class="referral-emoji">🎁</span>
                <span class="referral-text">Help someone attend a camp — Earn Health Rewards</span>
            </div>
            <button class="referral-cta" onclick="openReferralModal()">Learn More</button>
        </div>
    </section>

    <!-- 7. Real Stories / Impact Gallery -->
    <section class="impact-section">
        <h2>Real Stories, Real Impact</h2>
        <div class="impact-gallery">
            <div class="impact-story">
                <div class="story-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%234CAF50'/%3E%3Ctext x='150' y='105' text-anchor='middle' fill='white' font-family='Arial' font-size='16'%3EDental Camp Story%3C/text%3E%3C/svg%3E" alt="Dental Camp Impact">
                </div>
                <div class="story-quote">"Free dental check-up saved my family ₹15,000"</div>
                <div class="story-location">Mumbai • March 2024</div>
            </div>
            <div class="impact-story">
                <div class="story-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23FF9800'/%3E%3Ctext x='150' y='105' text-anchor='middle' fill='white' font-family='Arial' font-size='16'%3EEye Camp Story%3C/text%3E%3C/svg%3E" alt="Eye Camp Impact">
                </div>
                <div class="story-quote">"Early detection of glaucoma changed everything"</div>
                <div class="story-location">Delhi • February 2024</div>
            </div>
            <div class="impact-story">
                <div class="story-image">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='200' viewBox='0 0 300 200'%3E%3Crect width='300' height='200' fill='%23E91E63'/%3E%3Ctext x='150' y='105' text-anchor='middle' fill='white' font-family='Arial' font-size='16'%3EWomen Health%3C/text%3E%3C/svg%3E" alt="Women Health Camp">
                </div>
                <div class="story-quote">"Women's health screening for our entire community"</div>
                <div class="story-location">Bangalore • January 2024</div>
            </div>
        </div>
    </section>

    <!-- 8. Camp Discovery -->
    <section class="discovery-section">
        <h2>Discover Upcoming Camps</h2>
        <div class="discovery-container">
            <div class="discovery-filters">
                <div class="filter-toggle" onclick="toggleFilters()">
                    <span class="material-icons">tune</span>
                    <span>Filters</span>
                </div>
                <div class="filters-content" id="filtersContent">
                    <div class="filter-group">
                        <h4>Camp Type</h4>
                        <div class="filter-badges">
                            <span class="filter-badge active">🔍 All</span>
                            <span class="filter-badge">👁️ Eye</span>
                            <span class="filter-badge">🦷 Dental</span>
                            <span class="filter-badge">❤️ General</span>
                            <span class="filter-badge">🫀 Cardiology</span>
                        </div>
                    </div>
                    <div class="filter-group">
                        <h4>Location</h4>
                        <input type="text" placeholder="Enter city or area" class="filter-input">
                    </div>
                </div>
            </div>
            <div class="discovery-camps">
                <div class="camp-preview-card">
                    <div class="camp-urgency">Only 4 slots left</div>
                    <h4>Free Eye Check-up Camp</h4>
                    <p>📍 Andheri, Mumbai • 📅 Aug 20, 2024</p>
                    <button class="camp-cta">Register Now</button>
                </div>
                <div class="camp-preview-card">
                    <div class="camp-urgency">12 slots available</div>
                    <h4>Dental Care Camp</h4>
                    <p>📍 Connaught Place, Delhi • 📅 Aug 22, 2024</p>
                    <button class="camp-cta">Register Now</button>
                </div>
                <div class="camp-preview-card">
                    <h4>Women's Health Screening</h4>
                    <p>📍 Koramangala, Bangalore • 📅 Aug 25, 2024</p>
                    <button class="camp-cta">Register Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. FAQs Section -->
    <section class="faqs-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faqs-container">
            <div class="faq-category">
                <h3><span class="material-icons">person</span> For Patients</h3>
                <div class="faq-accordion">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            How do I register for a camp?
                            <span class="material-icons">expand_more</span>
                        </div>
                        <div class="faq-answer">
                            Simply browse available camps, click "Register Now", fill in your details, and you're set! You'll receive confirmation via email and SMS.
                        </div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            Are the camps really free?
                            <span class="material-icons">expand_more</span>
                        </div>
                        <div class="faq-answer">
                            Most camps are free or heavily subsidized. Some specialized camps may have nominal fees, which are clearly mentioned during registration.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="faq-category">
                <h3><span class="material-icons">medical_services</span> For Doctors</h3>
                <div class="faq-accordion">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            How do I host a camp?
                            <span class="material-icons">expand_more</span>
                        </div>
                        <div class="faq-answer">
                            Click "Post Your Camp", provide camp details, and our team will help you with logistics, patient registration, and promotion.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="faq-category">
                <h3><span class="material-icons">volunteer_activism</span> For Sponsors</h3>
                <div class="faq-accordion">
                    <div class="faq-item">
                        <div class="faq-question" onclick="toggleFAQ(this)">
                            How can I sponsor a camp?
                            <span class="material-icons">expand_more</span>
                        </div>
                        <div class="faq-answer">
                            Browse camps needing sponsorship, choose your contribution amount, and make secure payments. You'll receive regular updates on the impact of your sponsorship.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Sticky CTA Footer -->
    <div class="sticky-cta" id="stickyCTA">
        <div class="sticky-cta-content">
            <span class="material-icons">waving_hand</span>
            <span>Need help finding a camp?</span>
        </div>
        <button class="sticky-cta-btn" onclick="openHelpModal()">Get Help</button>
    </div>
</div>

<!-- Footer -->
@include('user.partials.footer')
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

<!-- jQuery (required for Toastr) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- JavaScript -->
<script>
// Notification form toggle
function toggleNotificationForm() {
    const form = document.getElementById('notificationForm');
    const icon = document.querySelector('.toggle-icon');
    
    if (form.style.display === 'block') {
        form.style.display = 'none';
        icon.textContent = 'expand_more';
    } else {
        form.style.display = 'block';
        icon.textContent = 'expand_less';
    }
}

// Animated counters
function animateCounters() {
    const counters = document.querySelectorAll('.counter-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / 200;
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            if (counter.textContent.includes('₹')) {
                counter.textContent = `₹${Math.floor(current)}L`;
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 10);
    });
}

// Testimonials carousel
let currentTestimonialIndex = 0;

function currentTestimonial(n) {
    showTestimonial(currentTestimonialIndex = n - 1);
}

function showTestimonial(n) {
    const testimonials = document.querySelectorAll('.testimonial-card');
    const dots = document.querySelectorAll('.dot');
    
    if (n >= testimonials.length) currentTestimonialIndex = 0;
    if (n < 0) currentTestimonialIndex = testimonials.length - 1;
    
    testimonials.forEach(testimonial => testimonial.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    testimonials[currentTestimonialIndex].classList.add('active');
    dots[currentTestimonialIndex].classList.add('active');
}

// Auto-rotate testimonials
setInterval(() => {
    currentTestimonialIndex++;
    showTestimonial(currentTestimonialIndex);
}, 5000);

// Filters toggle
function toggleFilters() {
    const filtersContent = document.getElementById('filtersContent');
    filtersContent.style.display = filtersContent.style.display === 'block' ? 'none' : 'block';
}

// FAQ toggle
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = element.querySelector('.material-icons');
    
    if (answer.style.display === 'block') {
        answer.style.display = 'none';
        icon.textContent = 'expand_more';
    } else {
        answer.style.display = 'block';
        icon.textContent = 'expand_less';
    }
}

// Sticky CTA show/hide
let scrollTimer = null;
window.addEventListener('scroll', () => {
    if (scrollTimer !== null) {
        clearTimeout(scrollTimer);
    }
    
    scrollTimer = setTimeout(() => {
        const stickyCTA = document.getElementById('stickyCTA');
        if (window.scrollY > 1000) {
            stickyCTA.style.display = 'flex';
        } else {
            stickyCTA.style.display = 'none';
        }
    }, 150);
});

// Modal functions (placeholders)
function openReferralModal() {
    alert('Referral program details coming soon!');
}

function openHelpModal() {
    alert('Help support coming soon!');
}

// Handle Doctor Camp Post button click
function handleDoctorCampPost() {
    @auth('user')
        // User is logged in as user - show logout prompt
        Swal.fire({
            title: 'Doctor Portal Access Required',
            html: `
                <div style="text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">👨‍⚕️</div>
                    <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                        You are currently logged in as a <strong>Patient/User</strong>.
                    </p>
                    <p style="color: #666; margin-bottom: 1.5rem;">
                        To post a medical camp, you need to access the <strong>Doctor Portal</strong>. 
                        Please logout from your current session to continue.
                    </p>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '🚪 Logout & Go to Doctor Portal',
            cancelButtonText: '↩️ Stay Here',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form to logout and redirect to doctor portal
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("user.logout") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_to_doctor';
                redirectInput.value = 'true';
                
                form.appendChild(csrfToken);
                form.appendChild(redirectInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    @else
        // User is not logged in - show login prompt
        Swal.fire({
            title: 'Doctor Login Required',
            html: `
                <div style="text-align: center;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🩺</div>
                    <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                        Only registered <strong>Doctors</strong> can post medical camps.
                    </p>
                    <p style="color: #666; margin-bottom: 1.5rem;">
                        Please login to the Doctor Portal to create and manage your medical camps.
                    </p>
                </div>
            `,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '👨‍⚕️ Login as Doctor',
            cancelButtonText: '↩️ Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to doctor login
                window.location.href = '{{ route("doctor.login") }}';
            }
        });
    @endauth
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate counters when they come into view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    });
    
    const countersSection = document.querySelector('.trust-counters');
    if (countersSection) {
        observer.observe(countersSection);
    }
    
    // Handle notification form submission
    const notifyForm = document.querySelector('.notify-form');
    if (notifyForm) {
        notifyForm.addEventListener('submit', function(e) {
            // Allow form to submit normally - don't prevent default
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });
    }
});

// User Real-time Notification System (Legacy Fallback)
@auth('web')
// These functions are kept for backward compatibility
// The main notification system is now handled by user-notifications.js

function setupUserNotifications() {
    // Check if new notification system is available
    if (typeof window.userNotificationSystem !== 'undefined') {
        console.log('✅ New user notification system already initialized');
        return true;
    }
    
    // Legacy Echo setup as fallback
    if (typeof window.Echo !== 'undefined' && window.Echo !== null) {
        try {
            // Listen for user-specific notifications using properly initialized Echo
            window.Echo.channel('user.{{ auth()->id() }}')
                .listen('user-message.sent', function(data) {
                    console.log('🔔 User notification received via Echo (legacy):', data);
                    
                    // Show toast notification using legacy method
                    showUserToast(data.message.message, getUserNotificationType(data.message.type));
                    
                    // Update badge count if exists
                    updateUserNotificationBadge();
                });
                
            console.log('✅ Legacy Echo channel subscribed for user {{ auth()->id() }}');
            return true;
        } catch (error) {
            console.log('❌ Echo subscription failed for user:', error);
            return false;
        }
    } else {
        console.log('⚠️ Echo not available for user notifications');
        return false;
    }
}

$(document).ready(function() {
    // Check if new notification system is handling things
    if (typeof window.userNotificationSystem !== 'undefined') {
        console.log('✅ New user notification system detected, skipping legacy setup');
        return;
    }
    
    // Legacy notification setup
    console.log('🔄 Setting up legacy user notifications...');
    
    // Try to setup Echo notifications after a delay
    setTimeout(function() {
        if (!setupUserNotifications()) {
            console.log('🔄 Starting polling fallback for user notifications (legacy)');
            startUserNotificationPolling();
        }
    }, 2000);
    
    // Additional retry after more time
    setTimeout(function() {
        if (typeof window.Echo === 'undefined' || window.Echo === null) {
            console.log('🔄 Echo still not available for user, ensuring polling is active (legacy)');
            startUserNotificationPolling();
        }
    }, 5000);
});

function startUserNotificationPolling() {
    // Fallback: Poll for new notifications every 15 seconds
    setInterval(function() {
        checkForNewUserNotifications();
    }, 15000);
    console.log('User notification polling started (15 second intervals)');
}

function checkForNewUserNotifications() {
    $.get('/user/notifications/check-new', function(data) {
        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach(function(notification) {
                showUserToast(notification.message, getUserNotificationType(notification.type));
            });
        }
    }).fail(function() {
        console.log('Failed to check for new user notifications');
    });
}
@endauth


function showUserToast(message, type = 'info') {
    // Remove existing toasts
    $('.user-toast').remove();
    
    const bgColor = type === 'success' ? 'bg-green-500' : 
                   type === 'error' ? 'bg-red-500' : 
                   type === 'warning' ? 'bg-yellow-500' : 
                   type === 'proposal_approved' ? 'bg-green-500' : 
                   type === 'proposal_rejected' ? 'bg-red-500' : 
                   'bg-blue-500';
    
    const icon = type === 'success' || type === 'proposal_approved' ? 'check-circle' : 
                type === 'error' || type === 'proposal_rejected' ? 'times-circle' : 
                type === 'warning' ? 'exclamation-triangle' : 
                'bell';
    
    const title = type === 'proposal_approved' ? 'Proposal Approved!' : 
                 type === 'proposal_rejected' ? 'Proposal Update' : 
                 type === 'success' ? 'Success!' : 
                 type === 'error' ? 'Alert!' : 
                 'Notification';
    
    const toast = $(`
        <div class="user-toast fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl z-50 transform translate-x-full transition-all duration-500 max-w-sm">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-${icon} text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <div class="font-bold text-sm">${title}</div>
                    <div class="text-xs mt-1 opacity-90 line-clamp-3">${message}</div>
                    <div class="text-xs mt-2 opacity-75">Just now</div>
                </div>
                <button class="ml-2 text-white hover:text-gray-200 text-lg" onclick="$(this).closest('.user-toast').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `);
    
    $('body').append(toast);
    
    // Animate in
    setTimeout(() => toast.removeClass('translate-x-full'), 100);
    
    // Auto remove after 8 seconds
    setTimeout(() => {
        toast.addClass('translate-x-full');
        setTimeout(() => toast.remove(), 500);
    }, 8000);
    
    // Play notification sound if available
    try {
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCGH0+/WgCkEJoHO8daJOAgRaLvt555NEAxPqOHwtmMdBjiS2O/OeyoFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBCKG0+/VgCkGJoLM8daJOQgRaL3t4Z5MEAxPpuHxtmQcBjiS2O/OeysFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwiBCGG0+/VfyoII4TL8taCOQkPbbzs4Z9OEAxOpuHytmQcBjaSWG1/PJGhSr4FH/4%3D');
        audio.volume = 0.3;
        audio.play().catch(() => {});
    } catch (e) {}
}

function getUserNotificationType(type) {
    const typeMap = {
        'camp_proposal_approved': 'proposal_approved',
        'camp_proposal_rejected': 'proposal_rejected',
        'success': 'success',
        'error': 'error',
        'warning': 'warning'
    };
    return typeMap[type] || 'info';
}

function updateUserNotificationBadge() {
    // Update user notification badge if it exists
    const badge = $('.user-notification-badge');
    if (badge.length > 0) {
        const current = parseInt(badge.text()) || 0;
        badge.text(current + 1).show();
    }
}
</script>
<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>

@endsection