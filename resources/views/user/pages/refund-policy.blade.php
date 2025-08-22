@extends('user.master')

@section('title', 'Refund Policy - FreeDoctor')

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-header">
            <h1><i class="fas fa-undo-alt me-3"></i>Refund Policy</h1>
            <p class="last-updated">Last Updated: August 4, 2025</p>
        </div>

        <div class="legal-content">
            <div class="welcome-section">
                <h2>Fair & Transparent Refunds</h2>
                <p>At FreeDoctor, we want you to be confident in your healthcare decisions. Our refund policy is designed to be fair and transparent for all users.</p>
            </div>

            <div class="section">
                <h3><i class="fas fa-user-injured"></i> 1. Patient Refunds</h3>
                
                <div class="refund-table">
                    <h4>Cancellation Timeline</h4>
                    <table class="policy-table">
                        <thead>
                            <tr>
                                <th>When You Cancel</th>
                                <th>Refund Amount</th>
                                <th>Processing Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>48+ hours before camp</strong></td>
                                <td><span class="success">90% refund</span><br><small>(10% service charge deducted)</small></td>
                                <td>3-5 business days</td>
                            </tr>
                            <tr>
                                <td><strong>24-48 hours before camp</strong></td>
                                <td><span class="warning">50% refund</span><br><small>Due to preparation costs</small></td>
                                <td>3-5 business days</td>
                            </tr>
                            <tr>
                                <td><strong>Less than 24 hours</strong></td>
                                <td><span class="danger">No refund</span><br><small>Preparation completed</small></td>
                                <td>Not applicable</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="emergency-box">
                    <h4><i class="fas fa-ambulance"></i> Medical Emergency Exception</h4>
                    <p>If you have a medical emergency preventing attendance, contact us immediately with medical documentation for a full refund consideration.</p>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-handshake"></i> 2. Sponsor Refunds</h3>
                
                <div class="sponsor-table">
                    <h4>Sponsor Cancellation Policy</h4>
                    <table class="policy-table">
                        <thead>
                            <tr>
                                <th>Cancellation Time</th>
                                <th>Refund Amount</th>
                                <th>Conditions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Before camp approval</strong></td>
                                <td><span class="success">90% refund</span></td>
                                <td>10% service charge applies</td>
                            </tr>
                            <tr>
                                <td><strong>After camp goes live</strong></td>
                                <td><span class="warning">50% refund</span></td>
                                <td>If no patients registered yet</td>
                            </tr>
                            <tr>
                                <td><strong>Patients already registered</strong></td>
                                <td><span class="danger">No refund</span></td>
                                <td>Commitment to patients</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-user-md"></i> 3. Doctor Cancellations</h3>
                <div class="doctor-policy">
                    <h4>When Doctors Cancel Camps</h4>
                    <ul>
                        <li><strong>Full Refund:</strong> 100% refund to all patients and sponsors</li>
                        <li><strong>No Service Charges:</strong> Our service charges are waived</li>
                        <li><strong>Quick Processing:</strong> Refunds processed within 24-48 hours</li>
                        <li><strong>Alternative Options:</strong> We'll help find similar camps if available</li>
                    </ul>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-exclamation-triangle"></i> 4. Special Circumstances</h3>
                
                <div class="circumstances-grid">
                    <div class="circumstance-item">
                        <h4><i class="fas fa-cloud-rain"></i> Natural Disasters</h4>
                        <p>Full refund if camp cancelled due to natural disasters or extreme weather conditions.</p>
                    </div>
                    
                    <div class="circumstance-item">
                        <h4><i class="fas fa-virus"></i> Health Emergencies</h4>
                        <p>Full refund during pandemic restrictions or public health emergencies.</p>
                    </div>
                    
                    <div class="circumstance-item">
                        <h4><i class="fas fa-ban"></i> Platform Issues</h4>
                        <p>Full refund if technical issues prevent camp participation.</p>
                    </div>
                    
                    <div class="circumstance-item">
                        <h4><i class="fas fa-gavel"></i> Legal Issues</h4>
                        <p>Refund as per legal requirements if camp cancelled due to regulatory issues.</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-clock"></i> 5. Refund Process</h3>
                <div class="process-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Request Refund</h4>
                        <p>Contact our support team via email or phone</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Review Request</h4>
                        <p>We review your request within 24 hours</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Process Refund</h4>
                        <p>Approved refunds processed to original payment method</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h4>Confirmation</h4>
                        <p>You receive email confirmation with refund details</p>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3><i class="fas fa-info-circle"></i> 6. Important Notes</h3>
                <div class="notes-box">
                    <ul>
                        <li><strong>Original Payment Method:</strong> Refunds processed to the original payment method used</li>
                        <li><strong>Bank Processing Time:</strong> Additional 3-7 days may be required by banks</li>
                        <li><strong>Service Charges:</strong> 10% service charges are non-refundable (except doctor cancellations)</li>
                        <li><strong>Free Camps:</strong> No refunds applicable for free camps</li>
                        <li><strong>Partial Services:</strong> No refunds for partially attended camps</li>
                    </ul>
                </div>
            </div>

            <div class="contact-section">
                <h3><i class="fas fa-headset"></i> Refund Support</h3>
                <p>Need help with a refund? Contact our support team:</p>
                <ul>
                    <li><strong>Email:</strong> refunds@freedoctor.in</li>
                    <li><strong>Phone:</strong> +91 1800-123-4567</li>
                    <li><strong>Support Hours:</strong> 9 AM - 9 PM (Mon-Sun)</li>
                    <li><strong>Response Time:</strong> Within 24 hours</li>
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

.policy-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.policy-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem;
    text-align: left;
    font-weight: 600;
}

.policy-table td {
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    vertical-align: top;
}

.policy-table tbody tr:hover {
    background: #f8f9fa;
}

.success {
    color: #28a745;
    font-weight: 600;
}

.warning {
    color: #ffc107;
    font-weight: 600;
}

.danger {
    color: #dc3545;
    font-weight: 600;
}

.emergency-box {
    background: linear-gradient(135deg, #ffebee 0%, #f3e5f5 100%);
    border: 2px solid #e91e63;
    padding: 1.5rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.emergency-box h4 {
    color: #c2185b;
    margin-bottom: 1rem;
}

.doctor-policy {
    background: linear-gradient(135deg, #e8f5e8 0%, #f1f8e9 100%);
    border: 2px solid #4caf50;
    padding: 2rem;
    border-radius: 15px;
    margin: 1rem 0;
}

.circumstances-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.circumstance-item {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
    padding: 1.5rem;
    border-radius: 10px;
    text-align: center;
}

.circumstance-item h4 {
    color: #495057;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.process-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.step {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    padding: 1.5rem;
    border-radius: 15px;
    text-align: center;
    border: 2px solid #667eea;
    position: relative;
}

.step-number {
    background: #667eea;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin: 0 auto 1rem;
}

.step h4 {
    color: #667eea;
    margin-bottom: 0.5rem;
}

.notes-box {
    background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
    border: 2px solid #ff9800;
    padding: 2rem;
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
    
    .policy-table {
        font-size: 0.9rem;
    }
    
    .policy-table th,
    .policy-table td {
        padding: 0.75rem;
    }
    
    .circumstances-grid,
    .process-steps {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
