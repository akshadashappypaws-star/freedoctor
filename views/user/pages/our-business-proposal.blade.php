@extends('user.master')

@section('title', 'Our Business Proposal - FreeDoctor.World')
@section('description', 'Grow Your Practice. Reach More Patients. Create Real Impact. Connect with genuine patients through medical camps, OPDs, and events.')
@section('content')
<style>
        :root {
            --bg-color: #f3f3f3;
            --primary-text: #383f45;
            --accent-color: #E7A51B;
            --white: #ffffff;
            --dark-overlay: rgba(56, 63, 69, 0.8);
            --success-color: #10B981;
            --blue-accent: #3B82F6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-color);
            color: var(--primary-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            line-height: 1.6;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--dark-overlay), var(--dark-overlay)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23383f45" width="1200" height="600"/><circle fill="%23E7A51B" opacity="0.1" cx="200" cy="150" r="80"/><circle fill="%23E7A51B" opacity="0.1" cx="800" cy="400" r="120"/><circle fill="%23E7A51B" opacity="0.1" cx="1000" cy="200" r="60"/></svg>');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.95;
        }

        .trust-badges {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .trust-badge {
            background: rgba(255,255,255,0.1);
            padding: 10px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            font-size: 0.9rem;
            font-weight: 600;
        }

        .cta-button {
            background: var(--accent-color);
            color: var(--primary-text);
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.2rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(231, 165, 27, 0.3);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(231, 165, 27, 0.4);
            color: var(--primary-text);
            text-decoration: none;
        }

        .stats-banner {
            background: var(--success-color);
            color: white;
            text-align: center;
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .stats-content {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            display: block;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .section {
            padding: 80px 0;
        }

        .section:nth-child(even) {
            background: var(--white);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary-text);
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid var(--accent-color);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--accent-color);
        }

        .testimonials-section {
            background: linear-gradient(135deg, var(--blue-accent), #1E40AF);
            color: white;
            padding: 100px 0;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .testimonial-card {
            background: rgba(255,255,255,0.1);
            padding: 2rem;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--primary-text);
        }

        .author-info h4 {
            margin-bottom: 0.25rem;
            font-weight: 600;
        }

        .author-info p {
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .verification-section {
            background: var(--white);
            padding: 80px 0;
        }

        .verification-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .verification-card {
            background: var(--bg-color);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            border: 2px solid var(--accent-color);
            position: relative;
        }

        .verification-icon {
            background: var(--accent-color);
            color: var(--primary-text);
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .step-card {
            text-align: center;
            padding: 2rem;
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .step-number {
            background: var(--accent-color);
            color: var(--primary-text);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1.5rem;
        }

        .pricing-section {
            background: var(--bg-color);
            padding: 80px 0;
        }

        .pricing-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .pricing-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .pricing-card.popular {
            border: 3px solid var(--accent-color);
            transform: scale(1.05);
        }

        .popular-badge {
            background: var(--accent-color);
            color: var(--primary-text);
            padding: 8px 25px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .pricing-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-text);
        }

        .pricing-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent-color);
            margin-bottom: 0.5rem;
        }

        .pricing-period {
            color: #666;
            margin-bottom: 2rem;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 2rem;
        }

        .pricing-features li {
            padding: 0.5rem 0;
            position: relative;
            padding-left: 25px;
        }

        .pricing-features li:before {
            content: "✓";
            color: var(--success-color);
            font-weight: 700;
            position: absolute;
            left: 0;
        }

        .success-stories {
            background: var(--white);
            padding: 80px 0;
        }

        .story-card {
            background: var(--bg-color);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            border-left: 5px solid var(--success-color);
        }

        .story-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .story-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-text);
        }

        .emotional-section {
            background: linear-gradient(135deg, var(--primary-text), #2c3238);
            color: white;
            text-align: center;
            padding: 100px 0;
        }

        .emotional-quote {
            font-size: 2rem;
            font-style: italic;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.4;
        }

        .contact-section {
            background: var(--white);
            text-align: center;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-item {
            padding: 1.5rem;
            background: var(--bg-color);
            border-radius: 10px;
            border: 2px solid var(--accent-color);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        .footer-note {
            background: var(--primary-text);
            color: white;
            text-align: center;
            padding: 40px 0;
            font-style: italic;
        }

        .sticky-cta {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
        }

        .security-badges {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .security-badge {
            background: rgba(255,255,255,0.9);
            color: var(--primary-text);
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comparison-table {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-top: 3rem;
        }

        .comparison-header {
            background: var(--primary-text);
            color: white;
            padding: 1.5rem;
            text-align: center;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .comparison-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #eee;
        }

        .comparison-row:nth-child(even) {
            background: var(--bg-color);
        }

        .comparison-feature {
            font-weight: 600;
        }

        .check-mark {
            color: var(--success-color);
            font-size: 1.2rem;
            text-align: center;
        }

        .cross-mark {
            color: #EF4444;
            font-size: 1.2rem;
            text-align: center;
        }

        .highlight-mark {
            color: var(--accent-color);
            font-size: 1.2rem;
            text-align: center;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .hero-content p {
                font-size: 1.1rem;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .emotional-quote {
                font-size: 1.5rem;
            }
            
            .sticky-cta {
                display: block;
            }

            .stats-content {
                gap: 30px;
            }

            .trust-badges {
                gap: 15px;
            }

            .comparison-row {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 10px;
            }

            .comparison-row > div {
                padding: 5px 0;
            }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .text-center {
            text-align: center;
        }

        .mb-4 {
            margin-bottom: 2rem;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>✨ Grow Your Practice. Reach More Patients. Create Real Impact. ✨</h1>
                <p>At FreeDoctor.World, we connect doctors with genuine patients through medical camps, OPDs, and events.<br>
                💡 <strong>No fixed marketing cost. Pay only for verified leads.</strong></p>
                <a href="#contact" class="cta-button">👉 Get Started – Generate Leads Now</a>
                
                <div class="trust-badges">
                    <div class="trust-badge">🔒 HIPAA Compliant</div>
                    <div class="trust-badge">✅ Doctor Verified</div>
                    <div class="trust-badge">🏆 5000+ Happy Doctors</div>
                    <div class="trust-badge">📱 24/7 Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="stats-banner">
        <div class="container">
            <div class="stats-content">
                <div class="stat-item">
                    <span class="stat-number">50,000+</span>
                    <span class="stat-label">Patients Connected</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">5,000+</span>
                    <span class="stat-label">Verified Doctors</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <span class="stat-label">Cities Covered</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">95%</span>
                    <span class="stat-label">Lead Conversion Rate</span>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">🩺 FreeDoctor.World – Marketing That Heals</h2>
            <div class="text-center">
                <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto;">
                    We are more than a marketing company. We are a healthcare movement.<br>
                    Our mission: to bridge the gap between patients in need and doctors who can serve them.
                </p>
                
                <div class="feature-grid mt-4">
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>Patients</h3>
                        <p>Discover free & affordable healthcare events. Pre-screened, verified medical camps and consultations.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👨‍⚕️</div>
                        <h3>Doctors</h3>
                        <p>Gain instant visibility & verified patient leads. Focus on healing while we handle patient acquisition.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🏘️</div>
                        <h3>Communities</h3>
                        <p>Thrive with accessible healthcare. Building healthier neighborhoods through trusted medical connections.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctor Verification Process -->
    <section class="verification-section">
        <div class="container">
            <h2 class="section-title">🛡️ Our Rigorous Doctor Verification Process</h2>
            <p class="text-center mb-4" style="font-size: 1.1rem; max-width: 700px; margin: 0 auto;">
                Every doctor on our platform goes through a comprehensive verification process to ensure patient safety and trust.
            </p>
            
            <div class="verification-grid">
                <div class="verification-card">
                    <div class="verification-icon">📋</div>
                    <h3>License Verification</h3>
                    <p>Medical license validation with state medical councils. All credentials cross-checked and verified.</p>
                </div>
                <div class="verification-card">
                    <div class="verification-icon">🏥</div>
                    <h3>Practice Verification</h3>
                    <p>Hospital affiliations and clinic locations verified. Physical practice validation by our team.</p>
                </div>
                <div class="verification-card">
                    <div class="verification-icon">⭐</div>
                    <h3>Background Check</h3>
                    <p>Professional background verification and peer references. Clean track record confirmation.</p>
                </div>
                <div class="verification-card">
                    <div class="verification-icon">📞</div>
                    <h3>Ongoing Monitoring</h3>
                    <p>Continuous patient feedback monitoring and quality assurance. Regular compliance checks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Doctors Choose Us -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Why 5000+ Doctors Choose FreeDoctor.World</h2>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">✅</div>
                    <h3>No Fixed Cost, No Risk</h3>
                    <p>Pay only for verified patient leads. No upfront marketing costs. Start with zero investment and grow based on results.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Instant Multi-Channel Exposure</h3>
                    <p>Your events promoted across WhatsApp groups, social media, Google ads, and local networks simultaneously.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🧠</div>
                    <h3>AI-Powered Smart Marketing</h3>
                    <p>50-70% lower acquisition costs vs. Google/Meta ads. Targeted to patients who actually need your services.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Pre-Qualified Patient Base</h3>
                    <p>Over 50,000 active patients already trust our platform. Higher conversion rates than cold marketing.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💎</div>
                    <h3>Complete Transparency</h3>
                    <p>Real-time lead tracking, detailed analytics, and performance reports. Know exactly what you're paying for.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💡</div>
                    <h3>Dedicated Support Team</h3>
                    <p>Personal account manager, 24/7 technical support, and marketing strategy consultation included.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">📊 FreeDoctor.World vs Traditional Marketing</h2>
            
            <div class="comparison-table">
                <div class="comparison-header">
                    See Why Smart Doctors Switch to FreeDoctor.World
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature"><strong>Feature</strong></div>
                    <div style="text-align: center; font-weight: 700;">FreeDoctor.World</div>
                    <div style="text-align: center; font-weight: 700;">Google Ads</div>
                    <div style="text-align: center; font-weight: 700;">Traditional Marketing</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature">Upfront Cost</div>
                    <div class="highlight-mark">₹0</div>
                    <div class="cross-mark">₹10,000+</div>
                    <div class="cross-mark">₹25,000+</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature">Patient Verification</div>
                    <div class="check-mark">✓</div>
                    <div class="cross-mark">✗</div>
                    <div class="cross-mark">✗</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature">Lead Quality</div>
                    <div class="highlight-mark">95%</div>
                    <div style="text-align: center;">60%</div>
                    <div style="text-align: center;">40%</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature">Setup Time</div>
                    <div class="highlight-mark">24 hours</div>
                    <div style="text-align: center;">1-2 weeks</div>
                    <div style="text-align: center;">1-3 months</div>
                </div>
                <div class="comparison-row">
                    <div class="comparison-feature">Risk</div>
                    <div class="check-mark">Zero Risk</div>
                    <div class="cross-mark">High Risk</div>
                    <div class="cross-mark">Very High Risk</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title" style="color: white;">💬 What Doctors & Patients Say</h2>
            
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "In just 3 months, I got 150+ verified patient leads through FreeDoctor.World. My practice has grown 40% without any upfront marketing spend. The lead quality is exceptional!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">RS</div>
                        <div class="author-info">
                            <h4>Dr. Rajesh Sharma</h4>
                            <p>Cardiologist, Mumbai • 15+ years experience</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Found an excellent orthopedic specialist through FreeDoctor.World's medical camp. The doctor was professional, and the treatment was exactly what I needed. Highly recommend!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">AM</div>
                        <div class="author-info">
                            <h4>Anita Mehta</h4>
                            <p>Patient, Pune • Software Engineer</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Unlike Google Ads where I was spending ₹50,000/month with unclear results, FreeDoctor.World gives me transparent, pay-per-lead pricing. ROI increased by 300%!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">PK</div>
                        <div class="author-info">
                            <h4>Dr. Priya Kulkarni</h4>
                            <p>Dermatologist, Bangalore • 12+ years experience</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "My elderly mother needed urgent care. FreeDoctor.World helped us find a trusted geriatrician nearby. The booking process was smooth and the doctor was excellent."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">VG</div>
                        <div class="author-info">
                            <h4>Vikram Gupta</h4>
                            <p>Patient, Delhi • Business Owner</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="success-stories">
        <div class="container">
            <h2 class="section-title">🎯 Real Success Stories from Our Network</h2>
            
            <div class="story-card">
                <div class="story-header">
                    <div class="story-avatar">NK</div>
                    <div>
                        <h3>Dr. Nikhil Kapse - Orthopedic Surgeon, Pune</h3>
                        <p><strong>Challenge:</strong> New practice, struggling to attract patients</p>
                    </div>
                </div>
                <p><strong>Results in 6 months:</strong></p>
                <p>✅ 300+ verified patient consultations<br>
                ✅ ₹12 lakh revenue generated<br>
                ✅ Zero marketing spend upfront<br>
                ✅ 4.8/5 patient satisfaction rating</p>
                <p><em>"FreeDoctor.World transformed my practice from struggling to thriving. The quality of patients is exceptional - they come prepared and genuinely need orthopedic care."</em></p>
            </div>
            
            <div class="story-card">
                <div class="story-header">
                    <div class="story-avatar">ST</div>
                    <div>
                        <h3>Dr. Sunita Tiwari - Pediatrician, Mumbai</h3>
                        <p><strong>Challenge:</strong> High Google Ads costs, low conversion</p>
                    </div>
                </div>
                <p><strong>Results comparison (3 months):</strong></p>
                <p>📊 <strong>Before:</strong> ₹80,000 Google Ads spend → 45 patients<br>
                📊 <strong>After:</strong> ₹0 upfront → 180 verified patients<br>
                💰 Cost per patient: Reduced from ₹1,780 to ₹450<br>
                ⚡ Lead quality: Improved from 40% to 92%</p>
                <p><em>"I wish I had found FreeDoctor.World earlier. The platform's parent network is incredible - genuinely concerned parents looking for trusted pediatric care."</em></p>
            </div>
            
            <div class="story-card">
                <div class="story-header">
                    <div class="story-avatar">MR</div>
                    <div>
                        <h3>Dr. Manoj Reddy - General Physician, Hyderabad</h3>
                        <p><strong>Challenge:</strong> Limited to local patient base</p>
                    </div>
                </div>
                <p><strong>Results in 4 months:</strong></p>
                <p>🌍 Expanded from 1 to 5 areas in Hyderabad<br>
                📈 Patient base grew by 250%<br>
                🏥 Opened second clinic due to demand<br>
                💯 98% patient retention rate</p>
                <p><em>"The platform's reach is amazing. I'm now treating patients from areas I never thought I could reach. The verification process ensures I get serious patients."</em></p>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="pricing-section">
        <div class="container">
            <h2 class="section-title">💰 Simple, Transparent Pricing</h2>
            <p class="text-center mb-4" style="font-size: 1.1rem;">
                No hidden fees. No long-term contracts. Pay only for results.
            </p>
            
            <div class="pricing-cards">
                <div class="pricing-card">
                    <div class="pricing-title">Starter</div>
                    <div class="pricing-amount">₹0</div>
                    <div class="pricing-period">Setup Cost</div>
                    <ul class="pricing-features">
                        <li>Basic profile listing</li>
                        <li>Up to 10 leads/month</li>
                        <li>Email support</li>
                        <li>Pay ₹300 per verified lead</li>
                        <li>Basic analytics</li>
                    </ul>
                    <a href="#contact" class="cta-button">Start Free</a>
                </div>
                
                <div class="pricing-card popular">
                    <div class="popular-badge">Most Popular</div>
                    <div class="pricing-title">Professional</div>
                    <div class="pricing-amount">₹0</div>
                    <div class="pricing-period">Setup Cost</div>
                    <ul class="pricing-features">
                        <li>Enhanced profile with photos</li>
                        <li>Unlimited leads</li>
                        <li>Priority support (24/7)</li>
                        <li>Pay ₹250 per verified lead</li>
                        <li>Advanced analytics & insights</li>
                        <li>Social media promotion</li>
                        <li>WhatsApp marketing</li>
                    </ul>
                    <a href="#contact" class="cta-button">Get Started</a>
                </div>
                
                <div class="pricing-card">
                    <div class="pricing-title">Enterprise</div>
                    <div class="pricing-amount">₹0</div>
                    <div class="pricing-period">Setup Cost</div>
                    <ul class="pricing-features">
                        <li>Premium profile with videos</li>
                        <li>Unlimited leads + priority queue</li>
                        <li>Dedicated account manager</li>
                        <li>Pay ₹200 per verified lead</li>
                        <li>Custom analytics dashboard</li>
                        <li>Multi-location support</li>
                        <li>API access</li>
                        <li>Brand partnership opportunities</li>
                    </ul>
                    <a href="#contact" class="cta-button">Contact Sales</a>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <div class="security-badges">
                    <div class="security-badge">
                        <span>🔒</span> SSL Secured Payments
                    </div>
                    <div class="security-badge">
                        <span>💳</span> All Payment Methods Accepted
                    </div>
                    <div class="security-badge">
                        <span>📋</span> GST Compliant Invoicing
                    </div>
                    <div class="security-badge">
                        <span>🔄</span> 7-Day Money Back Guarantee
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">How It Works for Doctors</h2>
            <p class="text-center mb-4" style="font-size: 1.1rem;">📌 Simple. Fast. Result-driven. Get started in 24 hours.</p>
            
            <div class="steps-container">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3>Quick Registration</h3>
                    <p>Create your profile in 10 minutes. Upload credentials, practice details, and specializations.</p>
                    <small style="color: #666;">⏱️ Average time: 10 minutes</small>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3>Verification Process</h3>
                    <p>Our team verifies your credentials with medical councils. Get approved within 24 hours.</p>
                    <small style="color: #666;">✅ 99% approval rate for genuine doctors</small>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3>Post Your Services</h3>
                    <p>List your camps, OPD timings, consultation services, and special offers.</p>
                    <small style="color: #666;">📅 Schedule recurring events easily</small>
                </div>
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h3>We Promote Everywhere</h3>
                    <p>Your services get promoted via social media, Google ads, WhatsApp groups, and local networks.</p>
                    <small style="color: #666;">🚀 Reaches 10,000+ people per campaign</small>
                </div>
                <div class="step-card">
                    <div class="step-number">5</div>
                    <h3>Patients Register</h3>
                    <p>Verified patients register for your services through our platform with pre-screening questionnaire.</p>
                    <small style="color: #666;">📋 Quality patients, not random inquiries</small>
                </div>
                <div class="step-card">
                    <div class="step-number">6</div>
                    <h3>Pay Only for Results</h3>
                    <p>Pay only for verified patient leads who actually show up for consultation.</p>
                    <small style="color: #666;">💰 No-show patients are free</small>
                </div>
            </div>
        </div>
    </section>

    <!-- For Patients -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">For Patients – Our Promise</h2>
            <div class="text-center">
                <p style="font-size: 1.2rem; margin-bottom: 2rem;">
                    Healthcare should be accessible, affordable, and trusted.<br>
                    That's why every doctor on FreeDoctor.World is personally verified by our medical team.
                </p>
                
                <h3 style="color: var(--accent-color); margin-bottom: 2rem;">✨ What patients get with us:</h3>
                
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">🔍</div>
                        <h3>Verified Doctor Network</h3>
                        <p>All 5000+ doctors verified with medical councils. Valid licenses, genuine practices, clean track records.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💰</div>
                        <h3>Free & Affordable Care</h3>
                        <p>Access to free medical camps, discounted consultations, and community health events in your area.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⏰</div>
                        <h3>Real-Time Updates</h3>
                        <p>Instant WhatsApp notifications for medical camps, appointment reminders, and health tips.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🤝</div>
                        <h3>Right Doctor Matching</h3>
                        <p>AI-powered matching based on your condition, location, budget, and language preferences.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🛡️</div>
                        <h3>Patient Protection</h3>
                        <p>Grievance redressal system, feedback mechanism, and 24/7 support for any issues.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📱</div>
                        <h3>Easy Booking</h3>
                        <p>Book appointments, join camps, and manage health records through our simple mobile app.</p>
                    </div>
                </div>
                
                <p style="font-size: 1.1rem; margin-top: 2rem; font-weight: 600;">
                    👉 Healthcare is not just business—it's about trust, compassion, and making quality care accessible to everyone.
                </p>
            </div>
        </div>
    </section>

    <!-- Security & Compliance -->
    <section class="section" style="background: var(--bg-color);">
        <div class="container">
            <h2 class="section-title">🔒 Security & Compliance You Can Trust</h2>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon">🏥</div>
                    <h3>HIPAA Compliance</h3>
                    <p>All patient data protected under international healthcare privacy standards. End-to-end encryption for all communications.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h3>Data Security</h3>
                    <p>ISO 27001 certified data centers, regular security audits, and zero data sharing with third parties.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚖️</div>
                    <h3>Legal Compliance</h3>
                    <p>Registered with MCA, GST compliant, and following all Indian healthcare regulations and medical advertising guidelines.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Regular Audits</h3>
                    <p>Quarterly third-party security audits, continuous compliance monitoring, and transparent audit reports.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">❓ Frequently Asked Questions</h2>
            
            <div class="feature-grid">
                <div class="feature-card">
                    <h3>How quickly can I start getting patients?</h3>
                    <p>Most doctors receive their first verified leads within 48-72 hours of profile approval. Our established patient network means faster results than building from scratch.</p>
                </div>
                <div class="feature-card">
                    <h3>What if patients don't show up?</h3>
                    <p>You only pay for patients who actually attend your consultation. No-shows are completely free. We also send automated reminders to reduce no-show rates.</p>
                </div>
                <div class="feature-card">
                    <h3>Is there any long-term contract?</h3>
                    <p>No contracts, no lock-in periods. You can pause or stop anytime. We work on a pure performance basis - you succeed, we succeed.</p>
                </div>
                <div class="feature-card">
                    <h3>How do you verify patient quality?</h3>
                    <p>Multi-step verification: phone verification, symptom pre-screening, genuine medical need assessment, and spam filtering using AI.</p>
                </div>
                <div class="feature-card">
                    <h3>What makes you different from clinic booking apps?</h3>
                    <p>We focus on lead generation, not just bookings. We actively market your services and bring new patients, while booking apps only manage existing patient flow.</p>
                </div>
                <div class="feature-card">
                    <h3>Can I track my ROI and performance?</h3>
                    <p>Yes! Real-time dashboard showing leads generated, conversion rates, payment details, patient feedback, and detailed analytics for better decision making.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Emotional Bonding -->
    <section class="emotional-section">
        <div class="container">
            <div class="emotional-quote">
                "Every patient deserves access to the right care.<br>
                Every doctor deserves the right reach.<br>
                At FreeDoctor.World, both journeys meet—with compassion, trust, and results."
            </div>
            <p style="margin-top: 2rem; font-size: 1.1rem; opacity: 0.9;">
                Join thousands of doctors who've transformed their practice and millions of patients who've found trusted healthcare through our platform.
            </p>
        </div>
    </section>

    <!-- Contact Us -->
    <section class="section contact-section" id="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <p class="text-center mb-4" style="font-size: 1.1rem;">
                Ready to grow your practice? Our team is here to help you get started.
            </p>
            
            <div class="contact-grid">
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <h3>Phone/WhatsApp</h3>
                    <p><strong>+91-XXXXXXXXXX</strong></p>
                    <small>Available 24/7 for urgent queries</small>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📧</div>
                    <h3>Email</h3>
                    <p><strong>contact@freedoctor.world</strong></p>
                    <small>Response within 2 hours</small>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🌐</div>
                    <h3>Website</h3>
                    <p><strong>www.freedoctor.world</strong></p>
                    <small>Online registration available</small>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <h3>Head Office</h3>
                    <p><strong>Pune, Maharashtra, India</strong></p>
                    <small>Visit by appointment</small>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="#" class="cta-button">👉 Partner With Us Today</a>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #666;">
                    🚀 <strong>Special Launch Offer:</strong> First 100 leads at 50% discount for new doctors
                </p>
            </div>
        </div>
    </section>

    <!-- Footer Transparency -->
    <section class="footer-note">
        <div class="container">
            <p>"We are committed to building a fair healthcare ecosystem where patients find trusted healthcare options and doctors grow without heavy marketing burdens.<br>
            <strong>FreeDoctor.World is where healthcare meets trust, technology meets compassion, and results meet integrity.</strong>"</p>
            <p style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.8;">
                Registered Company • GST: XXXXXXXXX • CIN: XXXXXXXXX • Licensed Healthcare Technology Platform
            </p>
        </div>
    </section>

    <!-- Sticky CTA for Mobile -->
    <div class="sticky-cta">
        <a href="#contact" class="cta-button">Get Started</a>
    </div>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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

        // Show/hide sticky CTA based on scroll position
        window.addEventListener('scroll', function() {
            const stickyCta = document.querySelector('.sticky-cta');
            const heroSection = document.querySelector('.hero-section');
            if (heroSection && stickyCta) {
                const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
                
                if (window.scrollY > heroBottom) {
                    stickyCta.style.display = 'block';
                } else {
                    stickyCta.style.display = 'none';
                }
            }
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card, .step-card, .testimonial-card, .story-card').forEach(el => {
            el.classList.add('fade-in');
            observer.observe(el);
        });

        // Animate statistics on load
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const currentValue = Math.floor(progress * (end - start) + start);
                element.innerHTML = currentValue.toLocaleString();
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Animate stats when they come into view
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(stat => {
                        const value = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
                        stat.textContent = '0';
                        animateValue(stat, 0, value, 2000);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.stats-banner');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
@endsection