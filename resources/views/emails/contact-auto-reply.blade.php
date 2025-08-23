<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting FreeDoctor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            margin: 20px 0;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .contact-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .contact-info h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .social-links {
            text-align: center;
            margin: 30px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            padding: 10px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .logo {
            font-size: 2em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏥</div>
            <h1>Thank You!</h1>
            <p>We've received your message and will get back to you soon</p>
        </div>

        <div class="content">
            <p>Dear {{ $data['name'] }},</p>
            
            <p>Thank you for contacting <strong>FreeDoctor</strong>. We have successfully received your message regarding "<strong>{{ ucfirst($data['subject']) }}</strong>" and appreciate you taking the time to reach out to us.</p>

            <div class="info-box">
                <h3>✅ What happens next?</h3>
                <ul>
                    <li><strong>Response Time:</strong> We typically respond within 24 hours during business days</li>
                    <li><strong>Our Team:</strong> A dedicated support representative will review your inquiry</li>
                    <li><strong>Follow-up:</strong> We'll contact you via email or phone based on your preference</li>
                </ul>
            </div>

            <p>In the meantime, feel free to explore our services:</p>
            <ul>
                <li>📱 Use our WhatsApp bot for instant health queries</li>
                <li>🔍 Browse our doctor directory</li>
                <li>📅 Book appointments online</li>
                <li>💊 Order prescriptions through our platform</li>
            </ul>

            @if($data['subject'] === 'technical')
            <div class="info-box" style="border-left-color: #ff9800; background: #fff3e0;">
                <h3>🔧 Technical Support</h3>
                <p>Since you've contacted us about a technical issue, here are some quick troubleshooting tips:</p>
                <ul>
                    <li>Clear your browser cache and cookies</li>
                    <li>Try using a different browser or device</li>
                    <li>Check your internet connection</li>
                    <li>Disable browser extensions temporarily</li>
                </ul>
                <p>If the issue persists, our technical team will assist you further.</p>
            </div>
            @endif

            <div class="contact-info">
                <h3>📞 Need Immediate Assistance?</h3>
                <p><strong>Emergency:</strong> For medical emergencies, please call emergency services immediately.</p>
                <p><strong>Phone:</strong> +91 77410 44366 (Mon-Fri, 9 AM - 6 PM)</p>
                <p><strong>WhatsApp:</strong> <a href="https://wa.me/917741044366">Chat with us instantly</a></p>
                <p><strong>Email:</strong> info@freedoctor.world</p>
            </div>
        </div>

        <div class="social-links">
            <a href="#" style="background: #3b5998;">📘 Facebook</a>
            <a href="#" style="background: #1da1f2;">🐦 Twitter</a>
            <a href="#" style="background: #e4405f;">📷 Instagram</a>
            <a href="#" style="background: #25d366;">💬 WhatsApp</a>
        </div>

        <div class="footer">
            <p><strong>FreeDoctor</strong> - Your Health, Our Priority</p>
            <p>This is an automated response. Please do not reply to this email.</p>
            <p style="font-size: 12px; color: #999;">
                If you did not submit this contact form, please ignore this email or contact us immediately.
            </p>
        </div>
    </div>
</body>
</html>
