@extends('user.master')

@section('title', 'Contact Us - FreeDoctor')
@section('description', 'Get in touch with FreeDoctor. Contact our support team for assistance with medical camps, healthcare services, or any questions about our platform.')

@section('content')
<style>
.contact-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 80px 0;
    text-align: center;
}

.contact-form-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.contact-info-section {
    padding: 80px 0;
    background: white;
}

.contact-card {
    background: white;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    transition: transform 0.3s ease;
}

.contact-card:hover {
    transform: translateY(-5px);
}

.contact-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: white;
}

.contact-icon.email {
    background: linear-gradient(45deg, #667eea, #764ba2);
}

.contact-icon.phone {
    background: linear-gradient(45deg, #28a745, #20c997);
}

.contact-icon.address {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
}

.contact-icon.whatsapp {
    background: linear-gradient(45deg, #25d366, #128c7e);
}

.form-control {
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    padding: 15px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-contact {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
    color: white;
    padding: 15px 40px;
    border-radius: 50px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    color: white;
}

.map-container {
    height: 400px;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.faq-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.faq-item {
    background: white;
    border-radius: 10px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.faq-header {
    padding: 20px;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.faq-header:hover {
    background: linear-gradient(45deg, #5a6fd8, #6a42a0);
}

.faq-body {
    padding: 20px;
    background: white;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 30px;
}

.social-link {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.social-link:hover {
    transform: scale(1.1);
    color: white;
}

.social-link.facebook { background: #3b5998; }
.social-link.twitter { background: #1da1f2; }
.social-link.instagram { background: #e4405f; }
.social-link.linkedin { background: #0077b5; }

@media (max-width: 768px) {
    .contact-hero {
        padding: 50px 0;
    }
    
    .contact-form-section,
    .contact-info-section,
    .faq-section {
        padding: 50px 0;
    }
    
    .contact-card {
        padding: 30px 20px;
    }
}
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="container">
        <h1 class="display-4 font-weight-bold mb-4">Get in Touch</h1>
        <p class="lead mb-0">We're here to help you with any questions about FreeDoctor</p>
    </div>
</section>

<!-- Contact Information -->
<section class="contact-info-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-card text-center">
                    <div class="contact-icon email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h5>Email Us</h5>
                    <p class="text-muted mb-3">Send us an email and we'll respond within 24 hours</p>
                    <a href="mailto:info@freedoctor.world" class="btn btn-outline-primary">
                        info@freedoctor.world
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-card text-center">
                    <div class="contact-icon phone">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h5>Call Us</h5>
                    <p class="text-muted mb-3">Speak directly with our support team</p>
                    <a href="tel:+917741044366" class="btn btn-outline-success">
                        +91 77410 44366
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-card text-center">
                    <div class="contact-icon whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h5>WhatsApp</h5>
                    <p class="text-muted mb-3">Chat with us on WhatsApp for instant support</p>
                    <a href="https://wa.me/917741044366" class="btn btn-outline-success" target="_blank">
                        Chat Now
                    </a>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contact-card text-center">
                    <div class="contact-icon address">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h5>Visit Us</h5>
                    <p class="text-muted mb-3">123 Medical Street<br>Healthcare City, HC 12345</p>
                    <a href="https://maps.google.com" class="btn btn-outline-warning" target="_blank">
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form -->
<section class="contact-form-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card">
                    <div class="text-center mb-5">
                        <h2 class="font-weight-bold">Send us a Message</h2>
                        <p class="text-muted">Fill out the form below and we'll get back to you as soon as possible</p>
                    </div>
                    
                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                        @csrf
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label font-weight-bold">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label font-weight-bold">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label font-weight-bold">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="subject" class="form-label font-weight-bold">Subject *</label>
                                <select class="form-control @error('subject') is-invalid @enderror" 
                                        id="subject" name="subject" required>
                                    <option value="">Choose a subject</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                                    <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                                    <option value="billing" {{ old('subject') == 'billing' ? 'selected' : '' }}>Billing Question</option>
                                    <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                                    <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                                    <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label font-weight-bold">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="6" required 
                                      placeholder="Tell us how we can help you...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-contact">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">Frequently Asked Questions</h2>
            <p class="text-muted">Find quick answers to common questions</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="faq-item">
                    <div class="faq-header" data-toggle="collapse" data-target="#faq1">
                        <h5 class="mb-0">
                            How do I book an appointment?
                            <i class="fas fa-chevron-down float-right"></i>
                        </h5>
                    </div>
                    <div id="faq1" class="collapse">
                        <div class="faq-body">
                            <p>You can book an appointment through our WhatsApp bot, website, or by calling our support team. Simply provide your preferred date, time, and doctor preference.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header" data-toggle="collapse" data-target="#faq2">
                        <h5 class="mb-0">
                            What are your operating hours?
                            <i class="fas fa-chevron-down float-right"></i>
                        </h5>
                    </div>
                    <div id="faq2" class="collapse">
                        <div class="faq-body">
                            <p>Our platform is available 24/7 for bookings. Our support team is available Monday to Friday, 9 AM to 6 PM. Emergency consultations are available round the clock.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header" data-toggle="collapse" data-target="#faq3">
                        <h5 class="mb-0">
                            How do I cancel or reschedule an appointment?
                            <i class="fas fa-chevron-down float-right"></i>
                        </h5>
                    </div>
                    <div id="faq3" class="collapse">
                        <div class="faq-body">
                            <p>You can cancel or reschedule appointments through your dashboard, WhatsApp, or by contacting our support team at least 2 hours before your scheduled time.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header" data-toggle="collapse" data-target="#faq4">
                        <h5 class="mb-0">
                            Is my personal information secure?
                            <i class="fas fa-chevron-down float-right"></i>
                        </h5>
                    </div>
                    <div id="faq4" class="collapse">
                        <div class="faq-body">
                            <p>Yes, we use industry-standard encryption and security measures to protect your personal and medical information. We comply with all healthcare privacy regulations.</p>
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-header" data-toggle="collapse" data-target="#faq5">
                        <h5 class="mb-0">
                            Do you accept insurance?
                            <i class="fas fa-chevron-down float-right"></i>
                        </h5>
                    </div>
                    <div id="faq5" class="collapse">
                        <div class="faq-body">
                            <p>We accept most major insurance plans. Please contact us with your insurance details to verify coverage before your appointment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Media Links -->
<section class="py-5 bg-white">
    <div class="container text-center">
        <h4 class="font-weight-bold mb-4">Follow Us</h4>
        <p class="text-muted mb-4">Stay connected for health tips and updates</p>
        
        <div class="social-links">
            <a href="#" class="social-link facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-link twitter">
                <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="social-link instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="social-link linkedin">
                <i class="fab fa-linkedin-in"></i>
            </a>
        </div>
    </div>
</section>

<script>
// Remove the old form submission handler since we're using Laravel form submission
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.parentNode) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 500);
            }
        }, 5000);
    });
});

// FAQ accordion functionality
document.querySelectorAll('.faq-header').forEach(header => {
    header.addEventListener('click', function() {
        const icon = this.querySelector('.fa-chevron-down');
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        
        // Rotate icon
        if (isExpanded) {
            icon.style.transform = 'rotate(0deg)';
        } else {
            icon.style.transform = 'rotate(180deg)';
        }
    });
});
</script>
@endsection
