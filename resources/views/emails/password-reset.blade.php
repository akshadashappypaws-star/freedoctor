<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - FreeDoctor</title>
    <style>
        :root {
            --primary-color: #383F45;
            --secondary-color: #E7A51B;
            --background-color: #f5f5f5;
            --surface-color: #ffffff;
            --text-primary: #212121;
            --text-secondary: #686868;
            --shadow-color: rgba(0, 0, 0, 0.12);
            --accent-color: #F7C873;
            --success-color: #4CAF50;
            --danger-color: #E53935;
            --border-radius: 16px;
        }

        /* Email client compatibility resets */
        * {
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
        }
        
        /* Force background colors for email clients */
        body, html, table, td, div {
            background-color: #383F45 !important;
            color: #212121 !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, Arial, sans-serif !important;
        }
        
        body {
            line-height: 1.6 !important;
            min-height: 100vh !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            background: #383F45 !important;
            background-image: linear-gradient(135deg, #383F45 0%, #E7A51B 100%) !important;
        }
        
        /* Main email wrapper */
        .email-wrapper {
            width: 100% !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            background-color: #ffffff !important;
            border-radius: 20px !important;
            overflow: hidden !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
        }
        
        .email-container {
            width: 100% !important;
            background-color: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 60px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            width: 100%;
            box-sizing: border-box;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: var(--surface-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 12px 40px var(--shadow-color);
            overflow: hidden;
            border: 4px solid var(--accent-color);
        }
        
        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .header p {
            opacity: 0.9;
            font-size: 18px;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .alert-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .alert-section h2 {
            color: #667eea;
            font-size: 28px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .alert-section p {
            color: #4a5568;
            font-size: 17px;
            line-height: 1.7;
            margin-bottom: 15px;
        }
        
        .reset-card {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #f87171;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            margin: 40px 0;
            position: relative;
            overflow: hidden;
        }
        
        .reset-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(248, 113, 113, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .reset-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f87171, #ef4444);
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 32px rgba(248, 113, 113, 0.3);
        }
        
        .reset-btn {
            display: inline-block;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        
        .reset-btn:hover {
            background: linear-gradient(135deg, #c82333, #dc3545);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        
        .security-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .security-warning h3 {
            color: #856404;
            font-size: 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .security-warning p {
            color: #856404;
            font-size: 14px;
            margin: 5px 0;
        }
        
        .request-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .request-details h4 {
            color: #2C2A4C;
            margin-bottom: 15px;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
            font-size: 14px;
        }
        
        .detail-label {
            color: #6c757d;
            font-weight: 500;
        }
        
        .detail-value {
            color: #2C2A4C;
            font-weight: 600;
        }
        
        .trust-indicators {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .trust-item {
            text-align: center;
            flex: 1;
        }
        
        .trust-item .icon {
            width: 40px;
            height: 40px;
            background: #2C2A4C;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        
        .trust-item h4 {
            font-size: 12px;
            color: #2C2A4C;
            margin-bottom: 5px;
        }
        
        .trust-item p {
            font-size: 10px;
            color: #6c757d;
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
        
        .contact-info {
            margin: 20px 0;
            font-size: 14px;
            opacity: 0.8;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .trust-indicators {
                flex-direction: column;
                gap: 20px;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body style="background-color: #383F45 !important; background-image: linear-gradient(135deg, #383F45 0%, #E7A51B 100%) !important; margin: 0 !important; padding: 20px !important;">
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header with Logo -->
            <div class="header">
                <div class="logo">
                    <img src="{{ asset('storage/PngVectordeisgn.png') }}" alt="FreeDoctor Logo" style="width: 70px; height: 70px; object-fit: contain;" />
                </div>
                <h1>FreeDoctor Healthcare</h1>
                <p>Account Security Alert</p>
            </div>
            
            <!-- Main Content -->
            <div class="content">
                <div class="alert-section">
                <h2>Password Reset Request</h2>
                <p>Hello <strong>{{ $user->name ?? 'User' }}</strong>,</p>
                <p>We received a request to reset your password for your FreeDoctor account. If you made this request, click the button below to set a new password.</p>
            </div>
            
            <!-- Reset Card -->
            <div class="reset-card">
                <div class="reset-icon">🔑</div>
                <h3 style="color: #dc3545; margin-bottom: 15px;">Reset Your Password</h3>
                <p style="color: #6c757d; margin-bottom: 25px;">Click the button below to create a new password for your account.</p>
                <a href="{{ $resetUrl }}" class="reset-btn">Reset Password</a>
                <p style="font-size: 12px; color: #6c757d; margin-top: 15px;">This link will expire in 60 minutes</p>
            </div>
            
            <!-- Request Details -->
            <div class="request-details">
                <h4>Request Details</h4>
                <div class="detail-row">
                    <span class="detail-label">Requested Time:</span>
                    <span class="detail-value">{{ now()->format('M d, Y at h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">IP Address:</span>
                    <span class="detail-value">{{ request()->ip() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Device:</span>
                    <span class="detail-value">{{ request()->userAgent() }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Email:</span>
                    <span class="detail-value">{{ $user->email ?? 'Not provided' }}</span>
                </div>
            </div>
            
            <!-- Security Warning -->
            <div class="security-warning">
                <h3>🚨 Important Security Notice</h3>
                <p><strong>If you didn't request this password reset:</strong></p>
                <p>• Your account may be at risk - please contact our support team immediately</p>
                <p>• Do not click the reset button above</p>
                <p>• Consider changing your password if you suspect unauthorized access</p>
                <p>• Enable two-factor authentication for added security</p>
            </div>
            
            <!-- Trust Indicators -->
            <div class="trust-indicators">
                <div class="trust-item">
                    <div class="icon">🛡️</div>
                    <h4>SECURE</h4>
                    <p>Bank-Level Security</p>
                </div>
                <div class="trust-item">
                    <div class="icon">🔒</div>
                    <h4>ENCRYPTED</h4>
                    <p>End-to-End Protection</p>
                </div>
                <div class="trust-item">
                    <div class="icon">⏰</div>
                    <h4>TIME-LIMITED</h4>
                    <p>Expires in 60 Minutes</p>
                </div>
                <div class="trust-item">
                    <div class="icon">👥</div>
                    <h4>24/7 SUPPORT</h4>
                    <p>Expert Assistance</p>
                </div>
            </div>
            
            <div style="text-align: center; margin: 30px 0; padding: 20px; background: #e8f5e8; border-radius: 8px;">
                <p style="color: #155724; margin: 0;"><strong>Need Help or Suspicious Activity?</strong></p>
                <p style="color: #155724; font-size: 14px; margin: 5px 0;">Contact our security team immediately if you didn't request this.</p>
                <p style="color: #155724; font-size: 14px; margin: 0;">Email: security@freedoctor.com | Phone: +91 9999999999</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ config('app.url') }}">Visit Website</a>
                <a href="{{ config('app.url') }}/security">Security Center</a>
                <a href="{{ config('app.url') }}/contact">Contact Support</a>
                <a href="{{ config('app.url') }}/help">Help Center</a>
            </div>
            
            <div class="contact-info">
                <p><strong>FreeDoctor Healthcare Pvt. Ltd.</strong></p>
                <p>123 Healthcare Avenue, Medical District<br>Mumbai, Maharashtra 400001, India</p>
                <p>Email: info@freedoctor.com | Phone: +91 9999999999</p>
                <p>© {{ date('Y') }} FreeDoctor Healthcare. All rights reserved.</p>
            </div>
            
            <p style="font-size: 12px; opacity: 0.7; margin-top: 20px; color: white !important;">
                This password reset was requested from IP: {{ request()->ip() }}<br>
                If you didn't request this reset, please contact our security team immediately.
            </p>
        </div>
        </div>
    </div>
</body>
</html>
