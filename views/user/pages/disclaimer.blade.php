@extends('user.master')

@section('title', 'Disclaimer - FreeDoctor')

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-header">
            <h1><i class="fas fa-exclamation-triangle me-3"></i>Disclaimer</h1>
            <p class="last-updated">Last Updated: August 4, 2025</p>
        </div>

        <div class="legal-content">
            <div class="welcome-section">
                <h2>Important Legal Information</h2>
                <p>Please read this disclaimer carefully before using FreeDoctor platform. By using our services, you acknowledge and agree to these terms.</p>
            </div>

            <div class="section">
                <h3><i class="fas fa-stethoscope"></i> 1. Medical Disclaimer</h3>
                <div class="medical-warning">
                    <h4><i class="fas fa-heartbeat"></i> We Connect, Doctors Treat</h4>
                    <ul>
                        <li><strong>Platform Role:</strong> FreeDoctor is a technology platform that connects patients with doctors</li>
                        <li><strong>Not Medical Provider:</strong> We do not provide medical advice, diagnosis, or treatment</li>
                        <li><strong>Doctor Responsibility:</strong> All medical care is provided by independent licensed doctors</li>
                        <li><strong>Professional Judgment:</strong> Medical decisions are made by qualified healthcare professionals, not by our platform</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-shield-alt"></i> 2. Liability Limitations</h3>
                <div class="liability-box">
                    <h4>What We're Not Responsible For</h4>
                    <ul>
                        <li><strong>Medical Outcomes:</strong> Treatment results, side effects, or complications</li>
                        <li><strong>Doctor Performance:</strong> Quality of medical care provided by doctors</li>
                        <li><strong>Misdiagnosis:</strong> Incorrect diagnosis or treatment by healthcare providers</li>
                        <li><strong>Technical Issues:</strong> Internet connectivity or device problems during consultations</li>
                        <li><strong>Third-Party Services:</strong> Issues with payment gateways, partner services</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-user-check"></i> 3. Doctor Verification</h3>
                <div class="verification-info">
                    <h4>Our Verification Process</h4>
                    <ul>
                        <li><strong>License Check:</strong> We verify medical licenses and registrations</li>
                        <li><strong>Credential Review:</strong> Educational qualifications and experience verified</li>
                        <li><strong>Ongoing Monitoring:</strong> Regular checks on doctor status and compliance</li>
                        <li><strong>Your Responsibility:</strong> You should also verify doctor credentials independently</li>
                    </ul>
                    
                    <div class="important-note">
                        <h5><i class="fas fa-info-circle"></i> Important Note</h5>
                        <p>While we strive to maintain accurate doctor information, medical licenses and credentials can change. Always verify current status independently.</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-ambulance"></i> 4. Emergency Situations</h3>
                <div class="emergency-warning">
                    <h4><i class="fas fa-exclamation-triangle"></i> Medical Emergencies</h4>
                    <div class="emergency-instructions">
                        <p><strong>DO NOT use FreeDoctor for medical emergencies!</strong></p>
                        <ul>
                            <li><strong>Call Emergency Services:</strong> Dial 108 (India Emergency Number)</li>
                            <li><strong>Visit ER:</strong> Go to nearest hospital emergency room</li>
                            <li><strong>Contact Emergency Helpline:</strong> Use local emergency medical services</li>
                        </ul>
                        
                        <div class="emergency-examples">
                            <h5>Examples of Medical Emergencies:</h5>
                            <ul>
                                <li>Chest pain or heart attack symptoms</li>
                                <li>Difficulty breathing or choking</li>
                                <li>Severe bleeding or trauma</li>
                                <li>Loss of consciousness</li>
                                <li>Stroke symptoms</li>
                                <li>Severe allergic reactions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-database"></i> 5. Information Accuracy</h3>
                <ul>
                    <li><strong>Best Efforts:</strong> We strive to maintain accurate information but cannot guarantee 100% accuracy</li>
                    <li><strong>Doctor Profiles:</strong> Information provided by doctors themselves and subject to change</li>
                    <li><strong>Camp Details:</strong> Medical camp information is provided by organizing doctors</li>
                    <li><strong>User Responsibility:</strong> Verify important details independently before making decisions</li>
                </ul>
            </div>

            <div class="section">
                <h3><i class="fas fa-globe"></i> 6. Platform Availability</h3>
                <div class="availability-info">
                    <h4>Service Availability</h4>
                    <ul>
                        <li><strong>24/7 Goal:</strong> We aim for continuous service availability</li>
                        <li><strong>Maintenance:</strong> Scheduled maintenance may cause temporary interruptions</li>
                        <li><strong>Technical Issues:</strong> Unexpected technical problems may occur</li>
                        <li><strong>No Guarantee:</strong> We cannot guarantee uninterrupted service</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-gavel"></i> 7. Legal Compliance</h3>
                <div class="compliance-box">
                    <h4>Regulatory Framework</h4>
                    <ul>
                        <li><strong>Indian Laws:</strong> Platform operates under Indian legal framework</li>
                        <li><strong>Medical Council:</strong> Doctors must comply with Medical Council of India regulations</li>
                        <li><strong>Telemedicine:</strong> All online consultations follow Telemedicine Practice Guidelines</li>
                        <li><strong>Data Protection:</strong> Compliance with Digital Personal Data Protection Act, 2023</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-users"></i> 8. User Responsibilities</h3>
                <div class="responsibility-grid">
                    <div class="responsibility-item">
                        <h4><i class="fas fa-info"></i> Accurate Information</h4>
                        <p>Provide truthful medical history and personal information</p>
                    </div>
                    
                    <div class="responsibility-item">
                        <h4><i class="fas fa-pills"></i> Follow Instructions</h4>
                        <p>Follow prescribed treatment and medication instructions</p>
                    </div>
                    
                    <div class="responsibility-item">
                        <h4><i class="fas fa-calendar-check"></i> Keep Appointments</h4>
                        <p>Attend scheduled appointments and camps on time</p>
                    </div>
                    
                    <div class="responsibility-item">
                        <h4><i class="fas fa-question-circle"></i> Ask Questions</h4>
                        <p>Clarify doubts and seek second opinions when needed</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-edit"></i> 9. Changes to Disclaimer</h3>
                <p>This disclaimer may be updated to reflect changes in services or legal requirements. Continued use of the platform constitutes acceptance of any changes.</p>
            </div>

            <div class="contact-section">
                <h3><i class="fas fa-envelope"></i> Questions About This Disclaimer?</h3>
                <p>If you have questions about this disclaimer or need clarification:</p>
                <ul>
                    <li><strong>Email:</strong> legal@freedoctor.in</li>
                    <li><strong>Phone:</strong> +91 1800-123-4567</li>
                    <li><strong>Legal Team:</strong> Available Mon-Fri, 9 AM - 6 PM</li>
                    <li><strong>Emergency Contact:</strong> For urgent legal matters</li>
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
    max-width: 1000px;
    margin: 0 auto;
}

.welcome-section {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
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
    color: #ff6b6b;
}

.medical-warning {
    background: linear-gradient(135deg, #ffebee 0%, #f3e5f5 100%);
    border: 2px solid #e91e63;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.medical-warning h4 {
    color: #c2185b;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.liability-box {
    background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
    border: 2px solid #ff9800;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.liability-box h4 {
    color: #f57c00;
    font-weight: 700;
    margin-bottom: 1rem;
}

.verification-info {
    background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);
    border: 2px solid #4caf50;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.verification-info h4 {
    color: #2e7d32;
    margin-bottom: 1rem;
}

.important-note {
    background: rgba(255, 193, 7, 0.1);
    border: 1px solid #ffc107;
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.important-note h5 {
    color: #f57c00;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.emergency-warning {
    background: linear-gradient(135deg, #ffebee 0%, #fce4ec 100%);
    border: 3px solid #d32f2f;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.emergency-warning h4 {
    color: #d32f2f;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.emergency-instructions {
    background: rgba(244, 67, 54, 0.1);
    padding: 1.5rem;
    border-radius: 10px;
    margin: 1rem 0;
    border: 2px solid #f44336;
}

.emergency-instructions p {
    color: #d32f2f;
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 1rem;
}

.emergency-examples {
    background: rgba(255, 255, 255, 0.8);
    padding: 1rem;
    border-radius: 10px;
    margin-top: 1rem;
}

.emergency-examples h5 {
    color: #d32f2f;
    margin-bottom: 0.5rem;
}

.availability-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 1rem 0;
    border-left: 4px solid #667eea;
}

.compliance-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border: 2px solid #667eea;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.compliance-box h4 {
    color: #667eea;
    margin-bottom: 1rem;
}

.responsibility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.responsibility-item {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
}

.responsibility-item h4 {
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
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
    
    .responsibility-grid {
        grid-template-columns: 1fr;
    }
    
    .medical-warning,
    .liability-box,
    .verification-info,
    .emergency-warning,
    .compliance-box {
        padding: 1.5rem;
    }
}
</style>
@endsection
