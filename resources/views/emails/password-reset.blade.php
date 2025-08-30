<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - FreeDoctor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        
        .content h2 {
            color: #dc3545;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .content p {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            margin: 30px 0;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(220, 53, 69, 0.4);
            color: white !important;
            text-decoration: none;
        }
        
        .security-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .security-note h4 {
            color: #856404;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .security-note p {
            color: #856404;
            font-size: 14px;
            margin: 5px 0;
            text-align: left;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px 20px;
            text-align: center;
        }
        
        .contact-info {
            margin-bottom: 20px;
        }
        
        .contact-info h5 {
            color: #333;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .contact-details {
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }
        
        .contact-item i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .contact-item a {
            color: #667eea;
            text-decoration: none;
        }
        
        .contact-item a:hover {
            text-decoration: underline;
        }
        
        .footer-text {
            font-size: 12px;
            color: #999;
            margin-top: 20px;
            padding-top: 20px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .content h2 {
                font-size: 20px;
            }
            
            .reset-button {
                padding: 14px 30px;
                font-size: 16px;
            }
            
            .contact-details {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>FreeDoctor</h1>
            <p>Password Reset Request</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <h2>Reset Your Password</h2>
            <p>Hello <strong>{{ $user->name ?? 'User' }}</strong>,</p>
            <p>We received a request to reset your password for your FreeDoctor account. Click the button below to set a new password.</p>
            
            <a href="{{ $resetUrl }}" class="reset-button">Reset Password</a>
            
            <div class="security-note">
                <h4>🔒 Security Information</h4>
                <p>• This password reset link expires in 60 minutes for your security</p>
                <p>• If you didn't request this reset, please ignore this email</p>
                <p>• Never share this reset link with others</p>
                <p>• Contact support immediately if you suspect unauthorized access</p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="contact-info">
                <h5>Need Help?</h5>
                <div class="contact-details">
                    <div class="contact-item">
                        <i class="📧"></i>
                        <a href="mailto:info@freedoctor.world">info@freedoctor.world</a>
                    </div>
                    <div class="contact-item">
                        <i class="📞"></i>
                        <a href="tel:+917741044366">+91 77410 44366</a>
                    </div>
                    <div class="contact-item">
                        <i class="💬"></i>
                        <a href="https://wa.me/917741044366" target="_blank">WhatsApp</a>
                    </div>
                </div>
            </div>
            
            <div class="footer-text">
                <p><strong>FreeDoctor Healthcare Pvt. Ltd.</strong></p>
                <p>© {{ date('Y') }} FreeDoctor. All rights reserved.</p>
                <p>You received this email because a password reset was requested for your account.</p>
            </div>
        </div>
    </div>
</body>
</html>
