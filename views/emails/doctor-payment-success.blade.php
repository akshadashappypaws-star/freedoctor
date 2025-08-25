<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful - FreeDoctor</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #10B981 0%, #3B82F6 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #dee2e6; }
        .success-icon { width: 60px; height: 60px; background-color: rgba(255,255,255,0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; }
        .payment-details { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #dee2e6; }
        .detail-row:last-child { border-bottom: none; }
        .status-badge { background-color: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .next-steps { background-color: #e3f2fd; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #2196f3; }
        .step { margin: 15px 0; padding-left: 20px; }
        .step-number { background-color: #2196f3; color: white; width: 20px; height: 20px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; margin-right: 10px; }
        .button { background: linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; font-weight: bold; margin-top: 20px; }
        .social-links { margin-top: 20px; }
        .social-links a { color: #6c757d; text-decoration: none; margin: 0 10px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">
                ✓
            </div>
            <h1 style="margin: 0; font-size: 28px;">Payment Successful!</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Welcome to FreeDoctor Platform</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #2c3e50;">Dear Dr. {{ $payment->doctor->doctor_name }},</h2>
            
            <p>Congratulations! Your payment has been successfully processed and your doctor registration is now complete.</p>
            
            <p>Thank you for joining our mission to provide free medical care to those in need. Your contribution will help us build a stronger healthcare community.</p>

            <!-- Payment Details -->
            <div class="payment-details">
                <h3 style="margin-top: 0; color: #2c3e50;">Payment Details</h3>
                <div class="detail-row">
                    <span><strong>Amount Paid:</strong></span>
                    <span style="color: #10B981; font-weight: bold;">₹{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span><strong>Payment ID:</strong></span>
                    <span style="font-family: monospace;">{{ $payment->payment_id ?? 'Processing...' }}</span>
                </div>
                <div class="detail-row">
                    <span><strong>Receipt Number:</strong></span>
                    <span style="font-family: monospace;">{{ $payment->receipt_number }}</span>
                </div>
                <div class="detail-row">
                    <span><strong>Payment Date:</strong></span>
                    <span>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : 'Just now' }}</span>
                </div>
                <div class="detail-row">
                    <span><strong>Status:</strong></span>
                    <span class="status-badge">{{ ucfirst($payment->payment_status) }}</span>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3 style="margin-top: 0; color: #1976d2;">What Happens Next?</h3>
                
                <div class="step">
                    <span class="step-number">1</span>
                    <strong>Admin Review</strong><br>
                    <small>Our team will review your profile and payment within 24 hours.</small>
                </div>
                
                <div class="step">
                    <span class="step-number">2</span>
                    <strong>Account Activation</strong><br>
                    <small>You'll receive another email once your account is approved and activated.</small>
                </div>
                
                <div class="step">
                    <span class="step-number">3</span>
                    <strong>Access Dashboard</strong><br>
                    <small>Start creating medical camps and helping patients in your area.</small>
                </div>
            </div>

            <!-- Benefits Reminder -->
            <div style="background-color: #f0f9ff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #0ea5e9;">
                <h3 style="margin-top: 0; color: #0c4a6e;">Your Registration Benefits</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Verified doctor profile with trust badge</li>
                    <li>Create and manage unlimited medical camps</li>
                    <li>Access to patient registration analytics</li>
                    <li>Priority customer support</li>
                    <li>Campaign sponsor tracking dashboard</li>
                </ul>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('doctor.login') }}" class="button">Login to Your Account</a>
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance, please don't hesitate to contact our support team at <a href="mailto:support@freedoctor.com" style="color: #3B82F6;">support@freedoctor.com</a> or call us at +91 12345 67890.</p>

            <p>Thank you for being part of the FreeDoctor community!</p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The FreeDoctor Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0; color: #6c757d; font-size: 14px;">
                © {{ date('Y') }} FreeDoctor. All rights reserved.
            </p>
            
            <div class="social-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Support</a>
            </div>
            
            <p style="margin: 15px 0 0 0; color: #9ca3af; font-size: 12px;">
                This email was sent to {{ $payment->doctor->email }}.<br>
                If you didn't request this, please contact our support team immediately.
            </p>
        </div>
    </div>
</body>
</html>
