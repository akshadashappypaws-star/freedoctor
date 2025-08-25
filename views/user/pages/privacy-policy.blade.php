@extends('user.master')

@section('title', 'Privacy Policy - FreeDoctor')

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-header">
            <h1><i class="fas fa-shield-alt me-3"></i>Privacy Policy</h1>
            <p class="last-updated">Last Updated: August 4, 2025</p>
        </div>

        <div class="legal-content">
            <div class="welcome-section">
                <h2>Your Privacy Matters to Us</h2>
                <p>At FreeDoctor, we value your privacy and are committed to protecting your personal information. This policy explains how we collect, use, and protect your data.</p>
            </div>

            <div class="section">
                <h3><i class="fas fa-database"></i> 1. Information We Collect</h3>
                
                <div class="info-box">
                    <h4>Personal Information</h4>
                    <ul>
                        <li><strong>Basic Details:</strong> Name, email, phone number, address</li>
                        <li><strong>Medical Info:</strong> Health conditions (only when necessary for camps)</li>
                        <li><strong>Payment Data:</strong> Payment details for transactions</li>
                        <li><strong>Doctor Details:</strong> Medical licenses, qualifications, experience</li>
                    </ul>
                </div>

                <div class="info-box">
                    <h4>Technical Information</h4>
                    <ul>
                        <li>Device and browser information</li>
                        <li>IP address and location data</li>
                        <li>Website usage patterns</li>
                        <li>Cookies and tracking data</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-cogs"></i> 2. How We Use Your Information</h3>
                <ul>
                    <li><strong>Provide Services:</strong> Connect you with doctors and manage medical camps</li>
                    <li><strong>Process Payments:</strong> Handle transactions and billing</li>
                    <li><strong>Communication:</strong> Send updates about camps, bookings, and important notices</li>
                    <li><strong>Improve Platform:</strong> Analyze usage to enhance user experience</li>
                    <li><strong>Verify Identity:</strong> Confirm doctor credentials and user authenticity</li>
                </ul>
            </div>

            <div class="section">
                <h3><i class="fas fa-lock"></i> 3. Data Protection & Security</h3>
                <div class="security-box">
                    <h4>We Keep Your Data Safe</h4>
                    <ul>
                        <li><strong>Encryption:</strong> All sensitive data is encrypted</li>
                        <li><strong>Secure Servers:</strong> Data stored on secure, protected servers</li>
                        <li><strong>Access Control:</strong> Only authorized personnel can access your data</li>
                        <li><strong>Regular Audits:</strong> Security systems regularly reviewed and updated</li>
                        <li><strong>HIPAA Standards:</strong> Medical data handled with healthcare-grade security</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-share-alt"></i> 4. Data Sharing</h3>
                <div class="sharing-box">
                    <h4>When We Share Your Information</h4>
                    <p><strong>We DO NOT sell your personal data.</strong> We only share information when:</p>
                    <ul>
                        <li><strong>Medical Services:</strong> With doctors for your treatment</li>
                        <li><strong>Payment Processing:</strong> With payment gateways for transactions</li>
                        <li><strong>Legal Requirements:</strong> When required by law or court orders</li>
                        <li><strong>Emergency Situations:</strong> To protect health and safety</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-user-cog"></i> 5. Your Rights & Choices</h3>
                <div class="rights-grid">
                    <div class="right-item">
                        <h4><i class="fas fa-eye"></i> Access</h4>
                        <p>View all data we have about you</p>
                    </div>
                    <div class="right-item">
                        <h4><i class="fas fa-edit"></i> Update</h4>
                        <p>Correct or update your information</p>
                    </div>
                    <div class="right-item">
                        <h4><i class="fas fa-trash"></i> Delete</h4>
                        <p>Request deletion of your account</p>
                    </div>
                    <div class="right-item">
                        <h4><i class="fas fa-download"></i> Export</h4>
                        <p>Download your data in portable format</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-cookie-bite"></i> 6. Cookies & Tracking</h3>
                <p>We use cookies to improve your experience. You can control cookies in your browser settings.</p>
                <ul>
                    <li><strong>Essential Cookies:</strong> Required for platform functionality</li>
                    <li><strong>Analytics Cookies:</strong> Help us understand how you use our site</li>
                    <li><strong>Marketing Cookies:</strong> Used for relevant advertisements (optional)</li>
                </ul>
            </div>

            <div class="section">
                <h3><i class="fas fa-baby"></i> 7. Children's Privacy</h3>
                <div class="child-protection">
                    <p>Our platform is not intended for children under 18. If a child's data is accidentally collected, we will delete it immediately upon notification.</p>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-edit"></i> 8. Policy Updates</h3>
                <p>We may update this policy occasionally. We'll notify you of important changes via email or platform notifications.</p>
            </div>

            <div class="contact-section">
                <h3><i class="fas fa-envelope"></i> Privacy Questions?</h3>
                <p>Contact our Data Protection Officer:</p>
                <ul>
                    <li><strong>Email:</strong> privacy@freedoctor.in</li>
                    <li><strong>Phone:</strong> +91 1800-123-4567</li>
                    <li><strong>Grievance Officer:</strong> grievance@freedoctor.in</li>
                    <li><strong>Response Time:</strong> 48-72 hours</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.legal-page {
    padding: 2rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.legal-header {
    text-align: center;
    margin-bottom: 3rem;
}

.legal-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.legal-header .last-updated {
    color: #6c757d;
    font-size: 1rem;
    background: rgba(255,255,255,0.8);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    display: inline-block;
}

.legal-content {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    padding: 3rem;
    max-width: 900px;
    margin: 0 auto;
}

.welcome-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.welcome-section h2 {
    color: white;
    margin-bottom: 1rem;
}

.section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e9ecef;
}

.section:last-child {
    border-bottom: none;
}

.section h3 {
    color: #2c3e50;
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section h3 i {
    color: #667eea;
}

.info-box {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 1rem 0;
    border-left: 4px solid #667eea;
}

.security-box {
    background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);
    border: 2px solid #4caf50;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.security-box h4 {
    color: #2e7d32;
    font-weight: 700;
    margin-bottom: 1rem;
}

.sharing-box {
    background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
    border: 2px solid #ff9800;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.sharing-box h4 {
    color: #f57c00;
    font-weight: 700;
    margin-bottom: 1rem;
}

.rights-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.right-item {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
    border: 2px solid #667eea;
}

.right-item h4 {
    color: #667eea;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.child-protection {
    background: linear-gradient(135deg, #ffebee 0%, #f3e5f5 100%);
    border: 2px solid #e91e63;
    padding: 1.5rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.contact-section {
    background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-top: 2rem;
}

.contact-section h3 {
    color: white;
    margin-bottom: 1rem;
}

.contact-section ul {
    list-style: none;
    padding: 0;
}

.contact-section li {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.contact-section li::before {
    content: "→";
    margin-right: 0.5rem;
    font-weight: bold;
}

ul {
    padding-left: 1.5rem;
}

li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

p {
    line-height: 1.7;
    color: #495057;
}

@media (max-width: 768px) {
    .legal-content {
        padding: 2rem 1rem;
    }
    
    .legal-header h1 {
        font-size: 2rem;
    }
    
    .rights-grid {
        grid-template-columns: 1fr;
    }
    
    .security-box,
    .sharing-box {
        padding: 1.5rem;
    }
}
</style>
@endsection
