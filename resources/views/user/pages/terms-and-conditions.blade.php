@extends('user.master')

@section('title', 'Terms and Conditions - FreeDoctor')

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-header">
            <h1><i class="fas fa-file-contract me-3"></i>Terms & Conditions</h1>
            <p class="last-updated">Effective Date: August 9, 2025</p>
            <p class="last-updated">Last Updated: August 9, 2025</p>
        </div>

        <div class="legal-content">
            <div class="welcome-section">
                <p>Welcome to <strong>Freedoctor Healthcare Pvt. Ltd.</strong> ("Freedoctor", "we", "our", "us").</p>
                <p>These Terms & Conditions ("Terms") govern your access and use of our platform, mobile application, and related services ("Platform").</p>
                <p>By using our Platform, you agree to these Terms. If you do not agree, please do not use the Platform.</p>
            </div>

            <div class="section">
                <h3><i class="fas fa-book"></i> 1. Definitions</h3>
                <ul>
                    <li><strong>Doctor / Hospital / Service Provider</strong> – Licensed medical professional or healthcare entity posting medical camps on our Platform.</li>
                    <li><strong>Patient / User</strong> – Any individual registering for medical camps via our Platform.</li>
                    <li><strong>Sponsor</strong> – Any individual or organisation sponsoring a medical camp.</li>
                    <li><strong>Medical Camp</strong> – A time-bound healthcare service event listed on the Platform.</li>
                    <li><strong>Commission</strong> – The service fee charged by Freedoctor for facilitating transactions.</li>
                </ul>
            </div>

            <div class="section">
                <h3><i class="fas fa-cogs"></i> 2. Services Provided</h3>
                <div class="feature-box">
                    <ol>
                        <li>Freedoctor provides an online platform for:
                            <ul>
                                <li>Posting medical camps by doctors and hospitals.</li>
                                <li>Approving and listing medical camps after admin review.</li>
                                <li>Allowing patients to register and pay for camps.</li>
                                <li>Facilitating sponsorship for camps.</li>
                            </ul>
                        </li>
                        <li>Freedoctor does <strong>not</strong> provide medical advice, diagnosis, or treatment. All medical services are the sole responsibility of the Service Provider.</li>
                    </ol>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-user-plus"></i> 3. Registration & Account</h3>
                <ol>
                    <li>Doctors must register with valid medical licenses and proof of identity before posting camps.</li>
                    <li>Patients must provide accurate personal details when registering for camps.</li>
                    <li>Sponsors must provide valid contact and payment details.</li>
                    <li>Freedoctor reserves the right to approve or reject any registration or camp listing at its sole discretion.</li>
                </ol>
            </div>

            <div class="section">
                <h3><i class="fas fa-rupee-sign"></i> 4. Pricing & Payment</h3>
                <div class="highlight-box">
                    <ol>
                        <li>For <strong>paid medical camps</strong>, Freedoctor charges <strong>10% commission</strong> on the total booking amount paid by patients.</li>
                        <li>For sponsorship payments, Freedoctor charges <strong>10% commission</strong> from the sponsor amount before transferring the balance to the doctor/hospital.</li>
                        <li>Referral commissions (if applicable) are paid from the doctor/hospital to the referrer via the Freedoctor wallet system.</li>
                        <li>Payments will be processed via secure payment gateways.</li>
                        <li>Freedoctor is not liable for delays caused by banking or payment gateway issues.</li>
                    </ol>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-undo"></i> 5. Refund & Cancellation Policy</h3>
                <ol>
                    <li>Refund requests for patient bookings are handled directly by the Service Provider.</li>
                    <li>Freedoctor will refund service fees only if a transaction fails due to a technical error on our Platform.</li>
                    <li>Sponsors cannot cancel their sponsorship after payment unless agreed in writing by the Service Provider.</li>
                    <li>Freedoctor is not responsible for any disputes between doctors, patients, or sponsors.</li>
                </ol>
            </div>

            <div class="section">
                <h3><i class="fas fa-tasks"></i> 6. Responsibilities of Users</h3>
                
                <div class="feature-box">
                    <h4><strong>Doctors / Hospitals must:</strong></h4>
                    <ul>
                        <li>Ensure camps are legitimate and comply with laws.</li>
                        <li>Provide accurate and up-to-date information in listings.</li>
                        <li>Honour all confirmed bookings and sponsorships.</li>
                    </ul>
                </div>

                <div class="feature-box">
                    <h4><strong>Patients must:</strong></h4>
                    <ul>
                        <li>Provide correct information during registration.</li>
                        <li>Attend the camp as scheduled.</li>
                    </ul>
                </div>

                <div class="feature-box">
                    <h4><strong>Sponsors must:</strong></h4>
                    <ul>
                        <li>Provide sponsorship payments on time.</li>
                        <li>Not use sponsorship for unlawful purposes.</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-exclamation-triangle"></i> 7. Liability Disclaimer</h3>
                <div class="warning-box">
                    <ol>
                        <li>Freedoctor is only a <strong>facilitator</strong> and not responsible for the quality, safety, legality, or outcome of medical services.</li>
                        <li>All liability for medical services rests solely with the Service Provider.</li>
                        <li>Freedoctor is not liable for:
                            <ul>
                                <li>Cancellations or rescheduling by doctors.</li>
                                <li>Misrepresentation by Service Providers.</li>
                                <li>Any injury, loss, or damage arising from participation in a camp.</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-copyright"></i> 8. Intellectual Property</h3>
                <ol>
                    <li>All content on the Platform, including text, graphics, logos, and software, is owned by Freedoctor and protected by copyright law.</li>
                    <li>Service Providers grant Freedoctor a license to display their camp details for promotional purposes.</li>
                </ol>
            </div>

            <div class="section">
                <h3><i class="fas fa-shield-alt"></i> 9. Privacy & Data Protection</h3>
                <ol>
                    <li>Freedoctor will collect, use, and store your personal data in accordance with our Privacy Policy.</li>
                    <li>Patient data will only be shared with the doctor/hospital for the purpose of providing services.</li>
                    <li>Unauthorized use or resale of patient data is strictly prohibited.</li>
                </ol>
            </div>

            <div class="section">
                <h3><i class="fas fa-ban"></i> 10. Prohibited Activities</h3>
                <div class="warning-box">
                    <p>Users must not:</p>
                    <ul>
                        <li>Post false or misleading information.</li>
                        <li>Use the Platform for illegal purposes.</li>
                        <li>Attempt to hack or disrupt the Platform.</li>
                        <li>Infringe intellectual property rights.</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-edit"></i> 11. Modification of Terms</h3>
                <p>Freedoctor reserves the right to modify these Terms at any time. Continued use of the Platform after changes constitutes acceptance.</p>
            </div>

            <div class="section">
                <h3><i class="fas fa-gavel"></i> 12. Governing Law & Dispute Resolution</h3>
                <div class="legal-box">
                    <ol>
                        <li>These Terms shall be governed by the laws of India.</li>
                        <li>In case of disputes, the courts of Pune, Maharashtra shall have exclusive jurisdiction.</li>
                    </ol>
                </div>
            </div>

            <div class="contact-section">
                <h3><i class="fas fa-envelope"></i> 13. Contact Information</h3>
                <div class="contact-details">
                    <p><strong>Freedoctor Healthcare Pvt. Ltd.</strong></p>
                    <ul>
                        <li><strong>Address:</strong> Laxmi Enclave, Pune, Maharashtra</li>
                        <li><strong>Email:</strong> <a href="mailto:info@freedoctor.world" style="color: white;">info@freedoctor.world</a></li>
                        <li><strong>Phone:</strong> +91 7741044366</li>
                    </ul>
                </div>
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

.definitions,
.feature-box {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.highlight-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: 2px solid #667eea;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.highlight-box h4 {
    color: #667eea;
    font-weight: 700;
    margin-bottom: 1rem;
}

.warning-box {
    background: linear-gradient(135deg, #fff3e0 0%, #ffebee 100%);
    border: 2px solid #ff6b6b;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.warning-box h4 {
    color: #d32f2f;
    font-weight: 700;
    margin-bottom: 1rem;
}

.legal-box {
    background: linear-gradient(135deg, #f1f8e9 0%, #e8f5e8 100%);
    border: 2px solid #4caf50;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.legal-box h4 {
    color: #2e7d32;
    font-weight: 700;
    margin-bottom: 1rem;
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
    
    .highlight-box,
    .warning-box,
    .legal-box {
        padding: 1.5rem;
    }
}
</style>
@endsection
