<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-section">
                    <div class="footer-logo">
                        <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor">
                        <h4>FreeDoctor</h4>
                    </div>
                    <p>Transforming healthcare accessibility through community-driven medical campaigns and corporate sponsorships.</p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link" title="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.dashboard') }}">Home</a></li>
                        <li><a href="{{ route('user.campaigns') }}">Campaigns</a></li>
                        <li><a href="{{ route('user.sponsors') }}">Sponsors</a></li>
                        <li><a href="{{ route('user.organization-camp-request') }}">Request Campaign</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>For Patients</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.register') }}">Register</a></li>
                        <li><a href="{{ route('user.login') }}">Login</a></li>
                        <li><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                        @auth('user')
                            <li><a href="{{ route('user.profile') }}">My Profile</a></li>
                            <li><a href="{{ route('user.my-registrations') }}">My Registrations</a></li>
                            <li><a href="{{ route('user.notifications') }}">Notifications</a></li>
                            <li><a href="{{ route('user.settings') }}">Settings</a></li>
                        @else
                            <li><a href="{{ route('user.register') }}">Join Now</a></li>
                            <li><a href="{{ route('user.campaigns') }}">Browse Campaigns</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>For Doctors</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('doctor.login') }}">Doctor Login</a></li>
                        <li><a href="{{ route('doctor.registerform') }}">Doctor Registration</a></li>
                        <li><a href="{{ route('user.campaigns') }}">Browse Campaigns</a></li>
                        <li><a href="{{ route('user.organization-camp-request') }}">Request Campaign</a></li>
                        <li><a href="#medical-guidelines">Medical Guidelines</a></li>
                        <li><a href="#support">Support</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-section">
                    <h5>Legal & Support</h5>
                    <ul class="footer-links">
                        <li><a href="{{ route('user.privacy-policy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('user.terms-and-conditions') }}">Terms of Service</a></li>
                        <li><a href="{{ route('user.refund-policy') }}">Refund Policy</a></li>
                        <li><a href="{{ route('user.disclaimer') }}">Disclaimer</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#help-center">Help Center</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Newsletter Subscription -->
        <div class="row">
            <div class="col-12">
                <div class="newsletter-section">
                    <div class="row align-items-center">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <h5><i class="fas fa-envelope me-2"></i>Stay Updated with Healthcare News</h5>
                            <p class="mb-0">Get the latest updates on medical campaigns and health tips.</p>
                        </div>
                        <div class="col-lg-6">
                            <form class="newsletter-form" id="newsletterForm">
                                <div class="input-group">
                                    <input type="email" class="form-control" placeholder="Enter your email address" required>
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-paper-plane me-1"></i>Subscribe
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trust Indicators -->
        
        
        <hr class="footer-divider">
        
        <!-- Copyright and Contact Info -->
        <div class="row">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} FreeDoctor Medical Camps . All rights reserved.
                </p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="footer-contact">
                    <span class="me-3">
                        <i class="fas fa-phone me-1"></i>
                        <a href="tel:+911800123456">+91 1800-123-4567</a>
                    </span>
                    <span>
                        <i class="fas fa-envelope me-1"></i>
                        <a href="mailto:support@freedoctor.in">support@freedoctor.in</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Footer Styles -->
<style>
/* Footer Styles */
.footer {
    background: #373D43;
    color: white;
    padding: 3rem 0 1rem;
    margin-top: auto;
}

.footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.footer-logo img {
    width: 40px;
    height: 40px;
    margin-right: 0.75rem;
    border-radius: 8px;
}

.footer-logo h4 {
    color: white;
    margin: 0;
    font-weight: 700;
}

.footer-section p {
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.footer-section h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.75rem;
}

.footer-links a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.footer-links a:hover {
    color: #4CAF50;
    transform: translateX(5px);
}

.footer-links a::before {
    content: "→";
    margin-right: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.footer-links a:hover::before {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.social-link {
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-link:hover {
    background: #4CAF50;
    transform: translateY(-3px);
    color: white;
    box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
}

/* Newsletter Section */
.newsletter-section {
    background: rgba(255,255,255,0.1);
    padding: 2rem;
    border-radius: 15px;
    margin: 2rem 0;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.newsletter-section h5 {
    color: white;
    margin-bottom: 0.5rem;
}

.newsletter-section p {
    color: rgba(255,255,255,0.8);
    margin-bottom: 0;
}

.newsletter-form .form-control {
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 8px 0 0 8px;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
}

.newsletter-form .form-control:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.3);
    background: white;
}

.newsletter-form .btn {
    border-radius: 0 8px 8px 0;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    border: none;
    font-weight: 600;
}

.newsletter-form .btn:hover {
    background: linear-gradient(135deg, #45a049 0%, #388e3c 100%);
    transform: translateY(-1px);
}

/* Trust Indicators */
.trust-indicators {
    padding: 2rem 0;
    border-top: 1px solid rgba(255,255,255,0.1);
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin: 2rem 0;
}

.trust-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(255,255,255,0.05);
    border-radius: 10px;
    transition: all 0.3s ease;
}

.trust-item:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-2px);
}

.trust-item i {
    font-size: 1.5rem;
    color: #4CAF50;
    margin-bottom: 0.5rem;
}

.trust-item span {
    color: rgba(255,255,255,0.9);
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
}

.footer-divider {
    border-color: rgba(255,255,255,0.2);
    margin: 2rem 0 1rem;
}

.footer-copyright {
    color: rgba(255,255,255,0.6);
    margin: 0;
    font-size: 0.9rem;
}

.footer-contact a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-contact a:hover {
    color: #4CAF50;
}

/* Responsive Design */
@media (max-width: 768px) {
    .footer {
        padding: 2rem 0 1rem;
    }
    
    .newsletter-section {
        padding: 1.5rem;
        text-align: center;
    }
    
    .newsletter-form .input-group {
        flex-direction: column;
    }
    
    .newsletter-form .form-control {
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    
    .newsletter-form .btn {
        border-radius: 8px;
        width: 100%;
    }
    
    .footer-contact {
        text-align: center !important;
        margin-top: 1rem;
    }
    
    .footer-contact span {
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .social-links {
        justify-content: center;
    }
    
    .trust-item {
        padding: 0.75rem 0.5rem;
    }
    
    .trust-item i {
        font-size: 1.25rem;
    }
    
    .trust-item span {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .footer-logo {
        justify-content: center;
        text-align: center;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .footer-section {
        text-align: center;
    }
    
    .trust-indicators .row {
        gap: 0.5rem;
    }
}
</style>

<!-- Footer Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Newsletter form submission
    const newsletterForm = document.getElementById('newsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            
            // Show loading state
            const button = this.querySelector('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subscribing...';
            button.disabled = true;
            
            // Simulate API call (replace with actual implementation)
            setTimeout(() => {
                // Show success message
                Swal.fire({
                    title: 'Subscription Successful!',
                    text: 'Thank you for subscribing to our newsletter. You will receive healthcare updates and campaign notifications.',
                    icon: 'success',
                    confirmButtonText: 'Great!',
                    confirmButtonColor: '#4CAF50'
                });
                
                // Reset form
                this.reset();
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        });
    }
    
    // Add smooth scroll animation to footer links
    document.querySelectorAll('.footer-links a[href^="#"]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Social media click tracking (for analytics)
    document.querySelectorAll('.social-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // You can add actual social media links here
            const platform = this.querySelector('i').classList[1].split('-')[1];
            
            // For demo purposes, show a message
            Swal.fire({
                title: `Connect with us on ${platform.charAt(0).toUpperCase() + platform.slice(1)}!`,
                text: 'Stay updated with the latest healthcare news and campaign updates.',
                icon: 'info',
                confirmButtonText: 'Got it!',
                confirmButtonColor: '#667eea'
            });
        });
    });
});
</script>
