<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email - FreeDoctor</title>
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
            background: #383F45 !important;
            background-image: linear-gradient(135deg, #383F45 0%, #E7A51B 100%) !important;
            padding: 60px 40px !important;
            text-align: center !important;
            color: white !important;
            position: relative !important;
            overflow: hidden !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .header::before {
            content: '' !important;
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="1" fill="white" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') !important;
            opacity: 0.3 !important;
        }
        
        .logo {
            width: 100px !important;
            height: 100px !important;
            margin: 0 auto 30px !important;
            background: #ffffff !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 36px !important;
            font-weight: bold !important;
            color: #383F45 !important;
            position: relative !important;
            z-index: 1 !important;
            box-shadow: 0 12px 40px rgba(0,0,0,0.2) !important;
            overflow: hidden !important;
            border: 4px solid #F7C873 !important;
        }
        
        .logo img {
            width: 80px !important;
            height: 80px !important;
            object-fit: contain !important;
        }
        
        .header h1 {
            font-size: 36px !important;
            margin-bottom: 15px !important;
            font-weight: 700 !important;
            position: relative !important;
            z-index: 1 !important;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3) !important;
            color: white !important;
        }
        
        .header p {
            opacity: 0.9 !important;
            font-size: 18px !important;
            position: relative !important;
            z-index: 1 !important;
            color: white !important;
        }
        
        .content {
            padding: 50px 40px !important;
            width: 100% !important;
            box-sizing: border-box !important;
            background-color: #ffffff !important;
        }
        
        .welcome-section {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .welcome-section h2 {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 25px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }
        
        .welcome-section p {
            color: var(--text-secondary);
            font-size: 18px;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .verification-card {
            background: linear-gradient(135deg, var(--background-color) 0%, var(--accent-color) 20%);
            border: 3px solid var(--secondary-color);
            border-radius: var(--border-radius);
            padding: 50px 40px;
            text-align: center;
            margin: 50px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px var(--shadow-color);
        }
        
        .verification-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(231, 165, 27, 0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .verification-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            margin: 0 auto 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            position: relative;
            z-index: 1;
            box-shadow: 0 12px 40px rgba(56, 63, 69, 0.3);
        }
        
        .verify-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            color: var(--text-primary);
            text-decoration: none;
            padding: 20px 60px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 18px;
            margin: 30px 0;
            transition: all 0.4s ease;
            box-shadow: 0 12px 40px rgba(231, 165, 27, 0.4);
            position: relative;
            z-index: 1;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid var(--primary-color);
        }
        
        .verify-btn:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 16px 50px rgba(231, 165, 27, 0.6);
        }
        
        .security-note {
            background: var(--accent-color);
            border: 2px solid var(--secondary-color);
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .security-note h3 {
            color: #856404;
            font-size: 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .security-note p {
            color: #856404;
            font-size: 14px;
            margin: 5px 0;
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
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid var(--secondary-color);
        }
        
        .trust-item h4 {
            font-size: 14px;
            color: var(--primary-color);
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .trust-item p {
            font-size: 12px;
            color: var(--text-secondary);
        }
        
        .footer {
            background: #383F45 !important;
            background-image: linear-gradient(135deg, #383F45 0%, #E7A51B 100%) !important;
            color: white !important;
            padding: 50px 40px !important;
            text-align: center !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        .footer-links {
            margin: 25px 0 !important;
        }
        
        .footer-links a {
            color: #F7C873 !important;
            text-decoration: none !important;
            margin: 0 25px !important;
            font-size: 16px !important;
            font-weight: 500 !important;
            transition: color 0.3s ease !important;
        }
        
        .footer-links a:hover {
            color: #ffffff !important;
        }
        
        .social-links {
            margin: 20px 0 !important;
        }
        
        .social-links a {
            display: inline-block !important;
            width: 40px !important;
            height: 40px !important;
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 50% !important;
            margin: 0 10px !important;
            text-decoration: none !important;
            line-height: 40px !important;
        }
        
        .contact-info {
            margin: 20px 0 !important;
            font-size: 14px !important;
            opacity: 0.8 !important;
            color: white !important;
        }
        
        /* Email client specific overrides */
        .welcome-section h2, .welcome-section p {
            color: #383F45 !important;
        }
        
        .verification-card {
            background-color: #f5f5f5 !important;
            border: 3px solid #E7A51B !important;
        }
        
        .verify-btn {
            background: #E7A51B !important;
            background-image: linear-gradient(135deg, #E7A51B, #F7C873) !important;
            color: #383F45 !important;
            border: 2px solid #383F45 !important;
        }
        
        /* Mobile responsive */
        @media (max-width: 600px) {
            .email-wrapper {
                margin: 10px !important;
                border-radius: 15px !important;
            }
            
            .header, .content, .footer {
                padding: 30px 20px !important;
            }
            
            body {
                padding: 10px !important;
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
                <p>Your Trusted Healthcare Partner</p>
            </div>
            
            <!-- Main Content -->
            <div class="content">
                <div class="welcome-section">
                    <h2>🎉 Welcome to FreeDoctor!</h2>
                <p>Hello <strong style="color: #667eea;">{{ $user->name ?? 'Healthcare Professional' }}</strong>,</p>
                <p>Congratulations on joining India's most trusted Medical Camps ! You're now part of a community that's revolutionizing healthcare accessibility across the nation.</p>
                <p style="background: linear-gradient(135deg, #e8f4fd, #f0f8ff); padding: 15px; border-radius: 8px; border-left: 4px solid #667eea;">
                    <strong>🚀 Your journey to better healthcare starts here!</strong><br>
                    Complete your email verification to unlock premium features and connect with top medical professionals.
                </p>
            </div>
            
            <!-- Verification Card -->
            <div class="verification-card">
                <div class="verification-icon">🛡️</div>
                <h3 style="color: #667eea; margin-bottom: 20px; font-size: 24px;">Secure Email Verification</h3>
                <p style="color: #4a5568; margin-bottom: 30px; font-size: 16px;">One final step to activate your premium healthcare account. Click below to verify your email and join thousands of satisfied users.</p>
                <a href="{{ $verificationUrl }}" class="verify-btn">✅ Verify Email Address</a>
                <p style="font-size: 13px; color: #718096; margin-top: 20px; background: rgba(102, 126, 234, 0.1); padding: 10px; border-radius: 6px;">
                    🔒 <strong>Secure Link:</strong> This verification link expires in 60 minutes for your security
                </p>
            </div>
            
            <!-- Security Note -->
            <div class="security-note">
                <h3>🔒 Security Notice</h3>
                <p><strong>This is an automated email from FreeDoctor.</strong></p>
                <p>• If you didn't create an account, please ignore this email</p>
                <p>• Never share this verification link with others</p>
                <p>• Our team will never ask for your password via email</p>
            </div>
            
            <!-- Trust Indicators -->
            <div class="trust-indicators">
                <div class="trust-item">
                    <div class="icon">🛡️</div>
                    <h4>SECURE</h4>
                    <p>256-bit SSL Encryption</p>
                </div>
                <div class="trust-item">
                    <div class="icon">✅</div>
                    <h4>VERIFIED</h4>
                    <p>Government Approved</p>
                </div>
                <div class="trust-item">
                    <div class="icon">🏥</div>
                    <h4>TRUSTED</h4>
                    <p>Healthcare Certified</p>
                </div>
                <div class="trust-item">
                    <div class="icon">👨‍⚕️</div>
                    <h4>PROFESSIONAL</h4>
                    <p>Licensed Doctors</p>
                </div>
            </div>
            
            <div style="text-align: center; margin: 40px 0; padding: 25px; background: linear-gradient(135deg, #e8f5e8, #f0fff4); border-radius: 12px; border: 2px solid #48bb78;">
                <p style="color: #22543d; margin: 0; font-size: 18px;">💬 <strong>Need Assistance?</strong></p>
                <p style="color: #22543d; font-size: 15px; margin: 10px 0;">Our dedicated support team is available 24/7 to help you get started!</p>
                <div style="margin: 15px 0;">
                    <a href="mailto:support@freedoctor.com" style="color: #48bb78; text-decoration: none; margin: 0 15px; font-weight: 600;">📧 support@freedoctor.com</a>
                    <span style="color: #68d391;">|</span>
                    <a href="tel:+919999999999" style="color: #48bb78; text-decoration: none; margin: 0 15px; font-weight: 600;">📞 +91 99999 99999</a>
                </div>
                <p style="color: #22543d; font-size: 13px; margin: 10px 0 0 0;">
                    🌟 <em>Join over 50,000+ satisfied users who trust FreeDoctor for their healthcare needs!</em>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-links">
                <a href="{{ config('app.url') }}">Visit Website</a>
                <a href="{{ config('app.url') }}/privacy">Privacy Policy</a>
                <a href="{{ config('app.url') }}/terms">Terms of Service</a>
                <a href="{{ config('app.url') }}/contact">Contact Us</a>
            </div>
            
            <div class="social-links">
                <a href="#" title="Facebook">f</a>
                <a href="#" title="Twitter">t</a>
                <a href="#" title="LinkedIn">in</a>
                <a href="#" title="Instagram">ig</a>
            </div>
            
            <div class="contact-info">
                <p><strong>FreeDoctor Healthcare Pvt. Ltd.</strong></p>
                <p>123 Healthcare Avenue, Medical District<br>Mumbai, Maharashtra 400001, India</p>
                <p>Email: info@freedoctor.com | Phone: +91 9999999999</p>
                <p>© {{ date('Y') }} FreeDoctor Healthcare. All rights reserved.</p>
            </div>
            
            <p style="font-size: 12px; opacity: 0.7; margin-top: 20px; color: white !important;">
                You received this email because you created an account on FreeDoctor Medical Camps .
                If you did not create an account, please ignore this email.
            </p>
        </div>
        </div>
    </div>
</body>
</html>
