@extends('user.master')

@section('title', 'Shipping Policy - FreeDoctor')

@push('styles')
<style>
.legal-page {
    padding: 2rem 0;
    background: #f8f9fa;
}

.legal-content {
    background: white;
    padding: 3rem;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto;
}

.legal-header {
    text-align: center;
    margin-bottom: 3rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e9ecef;
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
}

.legal-section {
    margin-bottom: 2.5rem;
}

.legal-section h2 {
    color: #667eea;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.legal-section h3 {
    color: #2c3e50;
    font-size: 1.2rem;
    font-weight: 600;
    margin: 1.5rem 0 0.75rem;
}

.legal-section p {
    color: #495057;
    line-height: 1.7;
    margin-bottom: 1rem;
}

.legal-section ul, .legal-section ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.legal-section li {
    color: #495057;
    line-height: 1.6;
    margin-bottom: 0.5rem;
}

.highlight-box {
    background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 2rem 0;
}

.highlight-box h3 {
    color: white;
    margin-bottom: 1rem;
}

.info-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 2rem 0;
}

.info-box h3 {
    color: white;
    margin-bottom: 1rem;
}

.warning-box {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 2rem 0;
}

.warning-box h3 {
    color: white;
    margin-bottom: 1rem;
}

.contact-info {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    border-left: 4px solid #667eea;
    margin: 2rem 0;
}

.shipping-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.shipping-table th,
.shipping-table td {
    border: 1px solid #dee2e6;
    padding: 1rem;
    text-align: left;
}

.shipping-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.shipping-table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.timeline-box {
    background: #e3f2fd;
    border-left: 4px solid #2196F3;
    padding: 1.5rem;
    margin: 1rem 0;
    border-radius: 0 8px 8px 0;
}

@media (max-width: 768px) {
    .legal-content {
        padding: 2rem 1.5rem;
        margin: 0 1rem;
    }
    
    .legal-header h1 {
        font-size: 2rem;
    }
    
    .shipping-table {
        font-size: 0.9rem;
    }
    
    .shipping-table th,
    .shipping-table td {
        padding: 0.75rem 0.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="legal-page">
    <div class="container">
        <div class="legal-content">
            <div class="legal-header">
                <h1><i class="fas fa-shipping-fast me-3"></i>Shipping & Delivery Policy</h1>
                <p class="last-updated">Last Updated: {{ date('F j, Y') }}</p>
            </div>

            <div class="legal-section">
                <h2>1. Introduction</h2>
                <p>This Shipping and Delivery Policy outlines how FreeDoctor Healthcare Private Limited handles the delivery of physical items, medical documents, and healthcare-related materials. While we primarily operate as a healthcare service platform, certain situations require physical delivery of items to patients or healthcare providers.</p>
                
                <div class="info-box">
                    <h3><i class="fas fa-info-circle me-2"></i>Service-Based Platform</h3>
                    <p>FreeDoctor is primarily a healthcare service platform. Most of our offerings are services (medical consultations, campaign registrations) rather than physical products. This policy covers the limited circumstances where physical items need to be shipped.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>2. Items Subject to Shipping</h2>
                <h3>2.1 Medical Documents and Reports</h3>
                <ul>
                    <li><strong>Medical Reports:</strong> Lab test results, diagnostic reports, medical certificates</li>
                    <li><strong>Prescription Copies:</strong> Verified prescription documents</li>
                    <li><strong>Medical Records:</strong> Patient history files, treatment summaries</li>
                    <li><strong>Insurance Documents:</strong> Claim forms, pre-authorization papers</li>
                    <li><strong>Referral Letters:</strong> Doctor-to-doctor referral communications</li>
                </ul>

                <h3>2.2 Medical Supplies and Equipment</h3>
                <ul>
                    <li><strong>Prescribed Medications:</strong> Medicines prescribed during campaigns (where applicable)</li>
                    <li><strong>Medical Devices:</strong> Basic monitoring equipment, mobility aids</li>
                    <li><strong>First Aid Supplies:</strong> Basic medical supplies for campaign attendees</li>
                    <li><strong>Assistive Devices:</strong> Specialized equipment prescribed by doctors</li>
                </ul>

                <h3>2.3 Campaign Materials</h3>
                <ul>
                    <li><strong>Registration Kits:</strong> Physical registration materials for large campaigns</li>
                    <li><strong>Health Information Packets:</strong> Educational materials and brochures</li>
                    <li><strong>Identification Materials:</strong> Patient ID cards, appointment cards</li>
                    <li><strong>Follow-up Care Packages:</strong> Post-treatment care instructions and supplies</li>
                </ul>

                <div class="warning-box">
                    <h3><i class="fas fa-pills me-2"></i>Medication Delivery Notice</h3>
                    <p>FreeDoctor partners with licensed pharmacies for medication delivery. We do not stock or distribute medications directly. All prescriptions are fulfilled by registered pharmaceutical partners in compliance with Drugs and Cosmetics Act, 1940.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>3. Shipping Coverage Areas</h2>
                <h3>3.1 Delivery Locations</h3>
                
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th>Zone</th>
                            <th>Coverage Areas</th>
                            <th>Delivery Time</th>
                            <th>Shipping Charges</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Zone A</strong></td>
                            <td>Metro Cities (Delhi, Mumbai, Chennai, Bangalore, Hyderabad, Kolkata)</td>
                            <td>24-48 hours</td>
                            <td>Free for orders above ₹500</td>
                        </tr>
                        <tr>
                            <td><strong>Zone B</strong></td>
                            <td>Tier 1 Cities (Pune, Ahmedabad, Jaipur, Lucknow, Kanpur, Nagpur)</td>
                            <td>2-3 business days</td>
                            <td>₹50 standard shipping</td>
                        </tr>
                        <tr>
                            <td><strong>Zone C</strong></td>
                            <td>Tier 2 Cities and District Headquarters</td>
                            <td>3-5 business days</td>
                            <td>₹75 standard shipping</td>
                        </tr>
                        <tr>
                            <td><strong>Zone D</strong></td>
                            <td>Remote Areas and Rural Locations</td>
                            <td>5-7 business days</td>
                            <td>₹100 + courier charges</td>
                        </tr>
                    </tbody>
                </table>

                <h3>3.2 Service Limitations</h3>
                <ul>
                    <li><strong>Kashmir Region:</strong> Extended delivery times due to geographic constraints</li>
                    <li><strong>North-East States:</strong> Additional 2-3 days for delivery</li>
                    <li><strong>Island Territories:</strong> Special arrangements required, additional charges apply</li>
                    <li><strong>Border Areas:</strong> Security clearances may cause delays</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>4. Shipping Methods and Partners</h2>
                <h3>4.1 Delivery Partners</h3>
                <p>We work with trusted logistics partners to ensure safe delivery:</p>
                <ul>
                    <li><strong>Express Delivery:</strong> Blue Dart, FedEx for urgent medical documents</li>
                    <li><strong>Standard Delivery:</strong> India Post, Delhivery, Ekart for regular shipments</li>
                    <li><strong>Medical Couriers:</strong> Specialized medical logistics for sensitive items</li>
                    <li><strong>Local Delivery:</strong> Partner healthcare facilities for same-day delivery</li>
                </ul>

                <h3>4.2 Shipping Methods</h3>
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Description</th>
                            <th>Delivery Time</th>
                            <th>Tracking</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Express</strong></td>
                            <td>Same day / Next day delivery</td>
                            <td>6-24 hours</td>
                            <td>Real-time tracking</td>
                            <td>₹200-500</td>
                        </tr>
                        <tr>
                            <td><strong>Priority</strong></td>
                            <td>Expedited delivery with insurance</td>
                            <td>24-48 hours</td>
                            <td>SMS + Email updates</td>
                            <td>₹100-200</td>
                        </tr>
                        <tr>
                            <td><strong>Standard</strong></td>
                            <td>Regular delivery service</td>
                            <td>3-5 business days</td>
                            <td>Basic tracking</td>
                            <td>₹50-100</td>
                        </tr>
                        <tr>
                            <td><strong>Economy</strong></td>
                            <td>Cost-effective option</td>
                            <td>5-7 business days</td>
                            <td>Limited tracking</td>
                            <td>₹25-50</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="legal-section">
                <h2>5. Processing and Dispatch Timeline</h2>
                <h3>5.1 Order Processing</h3>
                
                <div class="timeline-box">
                    <h4><i class="fas fa-clock me-2"></i>Standard Processing Timeline</h4>
                    <ol>
                        <li><strong>Order Confirmation:</strong> Immediate email confirmation upon order</li>
                        <li><strong>Document Preparation:</strong> 4-6 hours for medical documents</li>
                        <li><strong>Quality Check:</strong> 2-4 hours for verification and packaging</li>
                        <li><strong>Dispatch:</strong> Within 24 hours for non-urgent items</li>
                        <li><strong>Tracking Notification:</strong> SMS/Email with tracking details</li>
                    </ol>
                </div>

                <h3>5.2 Priority Processing</h3>
                <ul>
                    <li><strong>Medical Emergencies:</strong> Same-day processing and dispatch</li>
                    <li><strong>Urgent Prescriptions:</strong> 2-4 hour processing</li>
                    <li><strong>Time-Sensitive Reports:</strong> Express processing available</li>
                    <li><strong>Campaign Materials:</strong> 24-48 hour processing for bulk orders</li>
                </ul>

                <h3>5.3 Processing Delays</h3>
                <p>Processing may be delayed due to:</p>
                <ul>
                    <li>Incomplete or incorrect shipping information</li>
                    <li>Medical document verification requirements</li>
                    <li>Prescription approval processes</li>
                    <li>High volume periods or holidays</li>
                    <li>Quality control requirements for medical items</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>6. Shipping Charges and Payment</h2>
                <h3>6.1 Shipping Cost Calculation</h3>
                <p>Shipping charges are calculated based on:</p>
                <ul>
                    <li><strong>Distance:</strong> Delivery zone and geographic location</li>
                    <li><strong>Weight and Size:</strong> Package dimensions and weight</li>
                    <li><strong>Delivery Speed:</strong> Express, priority, or standard delivery</li>
                    <li><strong>Special Handling:</strong> Temperature-controlled or fragile items</li>
                    <li><strong>Insurance:</strong> Optional insurance for valuable items</li>
                </ul>

                <h3>6.2 Free Shipping Eligibility</h3>
                <ul>
                    <li><strong>Medical Documents:</strong> Free shipping for all diagnostic reports</li>
                    <li><strong>Campaign Materials:</strong> Free shipping for registered campaign participants</li>
                    <li><strong>Minimum Order Value:</strong> Free shipping on orders above ₹500</li>
                    <li><strong>Emergency Situations:</strong> No shipping charges for medical emergencies</li>
                    <li><strong>Bulk Orders:</strong> Free shipping for healthcare facilities ordering in bulk</li>
                </ul>

                <div class="highlight-box">
                    <h3><i class="fas fa-gift me-2"></i>Free Shipping Benefits</h3>
                    <p>We believe healthcare should be accessible. That's why we offer free shipping for essential medical documents, emergency supplies, and campaign-related materials to reduce financial barriers to healthcare access.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>7. Packaging and Handling</h2>
                <h3>7.1 Medical Document Packaging</h3>
                <ul>
                    <li><strong>Confidential Packaging:</strong> Sealed, tamper-evident envelopes</li>
                    <li><strong>Waterproof Protection:</strong> Documents sealed in plastic sleeves</li>
                    <li><strong>Identification Labels:</strong> Clear recipient and sender information</li>
                    <li><strong>Priority Marking:</strong> Medical document identification on packages</li>
                </ul>

                <h3>7.2 Medical Equipment Packaging</h3>
                <ul>
                    <li><strong>Protective Packaging:</strong> Bubble wrap, foam inserts for fragile items</li>
                    <li><strong>Temperature Control:</strong> Insulated packaging for temperature-sensitive items</li>
                    <li><strong>Sterile Packaging:</strong> Maintained sterility for medical devices</li>
                    <li><strong>Handling Instructions:</strong> Clear instructions for fragile or sensitive items</li>
                </ul>

                <h3>7.3 Medication Packaging</h3>
                <ul>
                    <li><strong>Pharmacy Standards:</strong> Packaging compliant with pharmaceutical regulations</li>
                    <li><strong>Temperature Maintenance:</strong> Cold chain management for temperature-sensitive drugs</li>
                    <li><strong>Tamper-Proof Sealing:</strong> Secure sealing to prevent tampering</li>
                    <li><strong>Dosage Instructions:</strong> Clear labeling with dosage and administration instructions</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>8. Tracking and Communication</h2>
                <h3>8.1 Tracking Services</h3>
                <ul>
                    <li><strong>Real-Time Tracking:</strong> Live location updates for express shipments</li>
                    <li><strong>SMS Notifications:</strong> Key milestone updates via SMS</li>
                    <li><strong>Email Updates:</strong> Detailed tracking information via email</li>
                    <li><strong>Mobile App Tracking:</strong> Track shipments through FreeDoctor app</li>
                    <li><strong>Customer Support:</strong> Dedicated support for tracking inquiries</li>
                </ul>

                <h3>8.2 Delivery Notifications</h3>
                <ul>
                    <li><strong>Dispatch Notification:</strong> Confirmation when package leaves our facility</li>
                    <li><strong>In-Transit Updates:</strong> Regular updates on package movement</li>
                    <li><strong>Out for Delivery:</strong> Notification when package is out for delivery</li>
                    <li><strong>Delivery Confirmation:</strong> Confirmation with recipient signature/OTP</li>
                    <li><strong>Delay Notifications:</strong> Immediate alerts for any delays</li>
                </ul>

                <div class="info-box">
                    <h3><i class="fas fa-mobile-alt me-2"></i>Stay Updated</h3>
                    <p>Track your shipments through multiple channels - SMS, email, mobile app, or website. We ensure you're always informed about your package status, especially for time-sensitive medical items.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>9. Delivery Process and Requirements</h2>
                <h3>9.1 Delivery Verification</h3>
                <ul>
                    <li><strong>Identity Verification:</strong> Recipient ID verification for medical documents</li>
                    <li><strong>Signature Confirmation:</strong> Signature required for valuable items</li>
                    <li><strong>OTP Verification:</strong> One-time password for secure delivery</li>
                    <li><strong>Age Verification:</strong> Age verification for certain medical products</li>
                    <li><strong>Address Confirmation:</strong> Verification of delivery address accuracy</li>
                </ul>

                <h3>9.2 Delivery Attempts</h3>
                <ul>
                    <li><strong>First Attempt:</strong> Standard delivery to provided address</li>
                    <li><strong>Second Attempt:</strong> Reattempt delivery next business day</li>
                    <li><strong>Third Attempt:</strong> Final delivery attempt with customer coordination</li>
                    <li><strong>Local Hub Storage:</strong> Package held at local facility for pickup</li>
                    <li><strong>Return Process:</strong> Return to sender after failed delivery attempts</li>
                </ul>

                <h3>9.3 Special Delivery Instructions</h3>
                <ul>
                    <li><strong>Safe Drop:</strong> Secure location delivery for unattended addresses</li>
                    <li><strong>Neighbor Delivery:</strong> Delivery to trusted neighbors (with consent)</li>
                    <li><strong>Office Delivery:</strong> Workplace delivery during business hours</li>
                    <li><strong>Flexible Timing:</strong> Scheduled delivery for specific time windows</li>
                    <li><strong>Contactless Delivery:</strong> No-contact delivery options available</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>10. International Shipping</h2>
                <h3>10.1 Limited International Services</h3>
                <p>FreeDoctor offers limited international shipping for:</p>
                <ul>
                    <li><strong>Medical Reports:</strong> Digital copies via secure email (preferred method)</li>
                    <li><strong>Medical Certificates:</strong> For patients traveling abroad</li>
                    <li><strong>Insurance Documents:</strong> For international insurance claims</li>
                    <li><strong>Referral Letters:</strong> For treatment abroad</li>
                </ul>

                <h3>10.2 International Shipping Restrictions</h3>
                <ul>
                    <li><strong>No Medications:</strong> Prescription drugs cannot be shipped internationally</li>
                    <li><strong>Medical Devices:</strong> Subject to destination country regulations</li>
                    <li><strong>Controlled Substances:</strong> Strictly prohibited for international shipping</li>
                    <li><strong>Customs Clearance:</strong> Recipient responsible for customs duties and clearance</li>
                </ul>

                <h3>10.3 International Delivery Timeline</h3>
                <table class="shipping-table">
                    <thead>
                        <tr>
                            <th>Destination</th>
                            <th>Delivery Time</th>
                            <th>Shipping Cost</th>
                            <th>Tracking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SAARC Countries</td>
                            <td>5-7 business days</td>
                            <td>₹500-800</td>
                            <td>Full tracking</td>
                        </tr>
                        <tr>
                            <td>USA, Canada, UK</td>
                            <td>7-10 business days</td>
                            <td>₹800-1200</td>
                            <td>Full tracking</td>
                        </tr>
                        <tr>
                            <td>Europe, Australia</td>
                            <td>8-12 business days</td>
                            <td>₹1000-1500</td>
                            <td>Full tracking</td>
                        </tr>
                        <tr>
                            <td>Other Countries</td>
                            <td>10-15 business days</td>
                            <td>₹1200-2000</td>
                            <td>Limited tracking</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="legal-section">
                <h2>11. Damaged or Lost Shipments</h2>
                <h3>11.1 Damage Claims</h3>
                <p>If your shipment arrives damaged:</p>
                <ul>
                    <li><strong>Immediate Inspection:</strong> Check package condition upon delivery</li>
                    <li><strong>Photo Documentation:</strong> Take photos of damage before opening</li>
                    <li><strong>Report Within 24 Hours:</strong> Contact customer support immediately</li>
                    <li><strong>Keep Packaging:</strong> Retain all packaging materials for inspection</li>
                    <li><strong>Replacement Process:</strong> Free replacement for damaged medical items</li>
                </ul>

                <h3>11.2 Lost Shipment Claims</h3>
                <ul>
                    <li><strong>Tracking Investigation:</strong> Comprehensive tracking history review</li>
                    <li><strong>Courier Investigation:</strong> Coordination with delivery partner</li>
                    <li><strong>48-Hour Resolution:</strong> Most lost shipment claims resolved within 48 hours</li>
                    <li><strong>Replacement Shipping:</strong> Free replacement and expedited shipping</li>
                    <li><strong>Compensation:</strong> Full refund or replacement for lost items</li>
                </ul>

                <div class="warning-box">
                    <h3><i class="fas fa-shield-alt me-2"></i>Insurance Coverage</h3>
                    <p>High-value medical equipment and sensitive documents are automatically insured. Additional insurance available for valuable shipments. Claims processed within 7 business days of confirmation.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>12. Returns and Exchanges</h2>
                <h3>12.1 Return Policy</h3>
                <ul>
                    <li><strong>Medical Documents:</strong> Returns not applicable for personalized medical reports</li>
                    <li><strong>Medical Equipment:</strong> 7-day return policy for unused, unopened items</li>
                    <li><strong>Medications:</strong> No returns accepted due to safety regulations</li>
                    <li><strong>Campaign Materials:</strong> Returns accepted if campaign is cancelled</li>
                </ul>

                <h3>12.2 Exchange Process</h3>
                <ul>
                    <li><strong>Defective Items:</strong> Free exchange for manufacturing defects</li>
                    <li><strong>Wrong Shipment:</strong> Immediate exchange for incorrectly shipped items</li>
                    <li><strong>Size/Specification Issues:</strong> Exchange available for medical equipment</li>
                    <li><strong>Return Shipping:</strong> Prepaid return labels provided for eligible returns</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>13. Special Handling Services</h2>
                <h3>13.1 Temperature-Controlled Shipping</h3>
                <ul>
                    <li><strong>Cold Chain:</strong> 2-8°C temperature maintenance for vaccines and biologics</li>
                    <li><strong>Frozen Shipping:</strong> -20°C shipping for specialized medical samples</li>
                    <li><strong>Room Temperature:</strong> Stable temperature for most medications</li>
                    <li><strong>Temperature Monitoring:</strong> Real-time temperature tracking and alerts</li>
                </ul>

                <h3>13.2 Hazardous Material Shipping</h3>
                <ul>
                    <li><strong>Medical Samples:</strong> Compliance with IATA dangerous goods regulations</li>
                    <li><strong>Chemical Reagents:</strong> Specialized handling for laboratory chemicals</li>
                    <li><strong>Radioactive Materials:</strong> Licensed transport for nuclear medicine</li>
                    <li><strong>Infectious Substances:</strong> Category A and B infectious material transport</li>
                </ul>

                <h3>13.3 Urgent Medical Delivery</h3>
                <ul>
                    <li><strong>Same-Day Delivery:</strong> Emergency delivery within city limits</li>
                    <li><strong>Medical Courier:</strong> Dedicated medical courier for critical items</li>
                    <li><strong>Hospital Direct:</strong> Direct delivery to hospital or clinic</li>
                    <li><strong>Emergency Coordination:</strong> 24/7 emergency delivery coordination</li>
                </ul>
            </div>

            <div class="contact-info">
                <h3><i class="fas fa-shipping-fast me-2"></i>Shipping Support Contact</h3>
                <p><strong>FreeDoctor Shipping Department</strong></p>
                <p><strong>FreeDoctor Healthcare Private Limited</strong></p>
                <p>Email: shipping@freedoctor.in</p>
                <p>Phone: +91 1800-123-4567 (Press 3 for Shipping)</p>
                <p>WhatsApp: +91 9876543210</p>
                <p>Address: [Your Complete Business Address]</p>
                <p>Support Hours: Monday to Saturday, 8:00 AM - 8:00 PM IST</p>
                <p>Emergency Shipping: 24/7 support for medical emergencies</p>
            </div>

            <div class="legal-section">
                <h2>14. Policy Updates and Changes</h2>
                <p>This Shipping Policy may be updated to reflect:</p>
                <ul>
                    <li>Changes in logistics partnerships</li>
                    <li>New delivery options and services</li>
                    <li>Regulatory compliance requirements</li>
                    <li>Seasonal or operational adjustments</li>
                    <li>Technology improvements and capabilities</li>
                </ul>
                
                <p>Updates will be communicated through email notifications and website announcements. Continued use of our shipping services constitutes acceptance of policy changes.</p>
            </div>

            <div class="legal-section">
                <p><em>This Shipping and Delivery Policy is effective as of {{ date('F j, Y') }} and forms part of our Terms and Conditions. For questions about shipping, tracking, or delivery, please contact our shipping support team.</em></p>
            </div>
        </div>
    </div>
</div>

@include('user.partials.footer')
@endsection
