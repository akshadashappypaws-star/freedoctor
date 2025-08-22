<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Registration Confirmation - FreeDoctor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            color: #28a745;
            position: relative;
            z-index: 1;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        
        .header p {
            opacity: 0.9;
            font-size: 16px;
            position: relative;
            z-index: 1;
        }
        
        .success-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            display: inline-block;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .confirmation-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .confirmation-section h2 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .confirmation-section p {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
        }
        
        .success-card {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .success-icon {
            width: 60px;
            height: 60px;
            background: #28a745;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .registration-number {
            background: #fff;
            border: 2px dashed #28a745;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .registration-number strong {
            color: #28a745;
            font-size: 18px;
            letter-spacing: 1px;
        }
        
        /* Invoice Styles */
        .invoice-section {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin: 30px 0;
            overflow: hidden;
        }
        
        .invoice-header {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .invoice-header h3 {
            color: #2C2A4C;
            margin-bottom: 5px;
        }
        
        .invoice-details {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 15px;
        }
        
        .invoice-left, .invoice-right {
            flex: 1;
        }
        
        .invoice-right {
            text-align: right;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .campaign-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .detail-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .detail-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 14px;
            color: #2C2A4C;
            font-weight: 600;
        }
        
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .payment-table th,
        .payment-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .payment-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2C2A4C;
        }
        
        .payment-total {
            background: #28a745;
            color: white;
            padding: 15px 20px;
            margin-top: 20px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .patient-details {
            background: #e8f4f8;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .instructions-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .instructions-section h3 {
            color: #856404;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .instructions-list {
            list-style: none;
            padding: 0;
        }
        
        .instructions-list li {
            color: #856404;
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
        }
        
        .instructions-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }
        
        .qr-section {
            text-align: center;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            background: #fff;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #6c757d;
        }
        
        .contact-section {
            background: #e8f5e8;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        
        .footer {
            background: #2C2A4C;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer-links {
            margin: 20px 0;
        }
        
        .footer-links a {
            color: #ffc107;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .invoice-details {
                flex-direction: column;
                gap: 20px;
            }
            
            .invoice-right {
                text-align: left;
            }
            
            .detail-grid {
                grid-template-columns: 1fr;
            }
            
            .payment-total {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo">FD</div>
            <h1>FreeDoctor Healthcare</h1>
            <p>Campaign Registration Confirmed</p>
            <div class="success-badge">✓ Successfully Registered</div>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="confirmation-section">
                <h2>Registration Successful!</h2>
                <p>Dear <strong>{{ $registration->name ?? 'Patient' }}</strong>,</p>
                <p>Congratulations! Your registration for the medical campaign has been successfully confirmed. Below are your registration details and payment receipt.</p>
            </div>
            
            <!-- Success Card -->
            <div class="success-card">
                <div class="success-icon">✓</div>
                <h3 style="color: #155724; margin-bottom: 15px;">Registration Confirmed</h3>
                <p style="color: #155724; margin-bottom: 20px;">You are now registered for the campaign: <strong>{{ $campaign->title ?? 'Medical Campaign' }}</strong></p>
                
                <div class="registration-number">
                    <p style="margin: 0; color: #6c757d; font-size: 12px;">Registration Number</p>
                    <strong>{{ $registration->registration_number ?? 'REG' . str_pad($registration->id ?? '001', 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
            </div>
            
            <!-- Invoice Section -->
            <div class="invoice-section">
                <div class="invoice-header">
                    <div class="invoice-details">
                        <div class="invoice-left">
                            <h3>INVOICE</h3>
                            <p style="color: #6c757d; margin: 0;">Registration Receipt</p>
                        </div>
                        <div class="invoice-right">
                            <p style="color: #2C2A4C; font-weight: 600; margin: 0;">Invoice #: INV-{{ str_pad($registration->id ?? '001', 6, '0', STR_PAD_LEFT) }}</p>
                            <p style="color: #6c757d; font-size: 14px; margin: 5px 0 0 0;">Date: {{ now()->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="invoice-body">
                    <!-- Campaign Details -->
                    <div class="campaign-details">
                        <h4 style="color: #2C2A4C; margin-bottom: 15px;">📋 Campaign Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Campaign Name</div>
                                <div class="detail-value">{{ $campaign->title ?? 'Medical Campaign' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Campaign Date</div>
                                <div class="detail-value">{{ isset($campaign->start_date) ? \Carbon\Carbon::parse($campaign->start_date)->format('M d, Y') : 'TBA' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Location</div>
                                <div class="detail-value">{{ $campaign->location ?? 'To be announced' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Doctor</div>
                                <div class="detail-value">{{ $campaign->doctor->doctor_name ?? 'TBA' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Specialty</div>
                                <div class="detail-value">{{ $campaign->doctor->specialty->name ?? 'General Medicine' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Time</div>
                                <div class="detail-value">
                                    @if(isset($campaign->start_time) && isset($campaign->end_time))
                                        {{ \Carbon\Carbon::parse($campaign->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($campaign->end_time)->format('g:i A') }}
                                    @else
                                        To be announced
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Patient Details -->
                    <div class="patient-details">
                        <h4 style="color: #2C2A4C; margin-bottom: 15px;">👤 Patient Information</h4>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">Full Name</div>
                                <div class="detail-value">{{ $registration->name ?? 'Not provided' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Email</div>
                                <div class="detail-value">{{ $registration->email ?? 'Not provided' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value">{{ $registration->phone_number ?? 'Not provided' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Age</div>
                                <div class="detail-value">{{ $registration->age ?? 'Not provided' }} years</div>
                            </div>
                        </div>
                        @if(isset($registration->address))
                        <div class="detail-item" style="margin-top: 15px;">
                            <div class="detail-label">Address</div>
                            <div class="detail-value">{{ $registration->address }}</div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Payment Details -->
                    <h4 style="color: #2C2A4C; margin: 20px 0 15px 0;">💳 Payment Details</h4>
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Campaign Registration Fee</td>
                                <td>1</td>
                                <td>₹{{ number_format($registration->amount ?? 0) }}</td>
                                <td>₹{{ number_format($registration->amount ?? 0) }}</td>
                            </tr>
                            @if(isset($registration->taxes) && $registration->taxes > 0)
                            <tr>
                                <td>Taxes & Fees</td>
                                <td>1</td>
                                <td>₹{{ number_format($registration->taxes) }}</td>
                                <td>₹{{ number_format($registration->taxes) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                    <div class="payment-total">
                        <span><strong>Total Amount Paid</strong></span>
                        <span><strong>₹{{ number_format(($registration->amount ?? 0) + ($registration->taxes ?? 0)) }}</strong></span>
                    </div>
                    
                    @if(isset($registration->payment_id))
                    <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 6px;">
                        <div style="display: flex; justify-content: space-between; font-size: 14px;">
                            <span>Payment ID:</span>
                            <span style="font-weight: 600;">{{ $registration->payment_id }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; margin-top: 5px;">
                            <span>Payment Status:</span>
                            <span style="color: #28a745; font-weight: 600;">✓ Confirmed</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; margin-top: 5px;">
                            <span>Payment Date:</span>
                            <span style="font-weight: 600;">{{ isset($registration->created_at) ? $registration->created_at->format('M d, Y h:i A') : now()->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Instructions Section -->
            <div class="instructions-section">
                <h3>📋 Important Instructions</h3>
                <ul class="instructions-list">
                    <li>Arrive at the venue 30 minutes before your scheduled time</li>
                    <li>Bring a valid photo ID (Aadhaar Card, PAN Card, or Driving License)</li>
                    <li>Carry this email confirmation (printed or digital copy)</li>
                    <li>Fasting may be required for certain tests - we will contact you if needed</li>
                    <li>Wear comfortable, loose-fitting clothing</li>
                    <li>Bring your medical history and current medication list</li>
                    <li>Follow any specific instructions provided by the medical team</li>
                </ul>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h4 style="color: #2C2A4C; margin-bottom: 15px;">📱 Quick Check-in</h4>
                <div class="qr-code">
                    QR Code<br>
                    ({{ $registration->registration_number ?? 'REG001' }})
                </div>
                <p style="color: #6c757d; font-size: 14px; margin: 0;">Show this QR code at the venue for quick check-in</p>
            </div>
            
            <!-- Contact Section -->
            <div class="contact-section">
                <h4 style="color: #155724; margin-bottom: 15px;">Need Help? We're Here for You!</h4>
                <p style="color: #155724; margin-bottom: 10px;"><strong>Customer Support</strong></p>
                <p style="color: #155724; font-size: 14px; margin: 5px 0;">📞 Phone: +91 9999999999 (24/7 Support)</p>
                <p style="color: #155724; font-size: 14px; margin: 5px 0;">📧 Email: support@freedoctor.com</p>
                <p style="color: #155724; font-size: 14px; margin: 5px 0;">💬 WhatsApp: +91 9999999999</p>
                <p style="color: #155724; font-size: 12px; margin-top: 15px;">
                    For any questions about your registration or the campaign, feel free to contact us anytime.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ config('app.url') }}">Visit Website</a>
                <a href="{{ config('app.url') }}/campaigns">View Campaigns</a>
                <a href="{{ config('app.url') }}/profile">My Profile</a>
                <a href="{{ config('app.url') }}/contact">Contact Us</a>
            </div>
            
            <div style="margin: 20px 0; font-size: 14px; opacity: 0.8;">
                <p><strong>FreeDoctor Healthcare Pvt. Ltd.</strong></p>
                <p>123 Healthcare Avenue, Medical District<br>Mumbai, Maharashtra 400001, India</p>
                <p>Email: info@freedoctor.com | Phone: +91 9999999999</p>
                <p>© {{ date('Y') }} FreeDoctor Healthcare. All rights reserved.</p>
            </div>
            
            <p style="font-size: 12px; opacity: 0.7; margin-top: 20px;">
                This is an automated confirmation email for your campaign registration.<br>
                Please save this email for your records and bring it to the campaign venue.
            </p>
        </div>
    </div>
</body>
</html>
