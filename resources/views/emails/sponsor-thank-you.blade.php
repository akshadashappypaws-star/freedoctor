<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Sponsorship</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .highlight-name {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            font-size: 24px;
        }
        .amount {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
            margin: 20px 0;
        }
        .campaign-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .campaign-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin: 15px 0;
        }
        .impact-section {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .stat {
            text-align: center;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 18px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            margin: 15px 0;
        }
        @media (max-width: 600px) {
            .stats {
                flex-direction: column;
            }
            .container {
                margin: 10px;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🙏 Thank You for Your Generosity!</h1>
            <p>Your sponsorship is changing lives</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Dear <span class="highlight-name">{{ $sponsor->name }}</span>,</h2>
            
            <p>We are overwhelmed with gratitude for your incredible generosity! Your sponsorship will directly impact the lives of those who need healthcare the most.</p>

            <!-- Amount -->
            <div style="text-align: center;">
                <div class="amount">₹{{ number_format($sponsor->amount, 2) }}</div>
                <p><em>Your generous contribution</em></p>
            </div>

            <!-- Campaign Details -->
            <div class="campaign-details">
                <h3>📋 Campaign Details</h3>
                @if($sponsor->campaign->images && count($sponsor->campaign->images) > 0)
                    <img src="{{ asset('storage/' . $sponsor->campaign->images[0]) }}" alt="{{ $sponsor->campaign->title }}" class="campaign-image">
                @endif
                <h4>{{ $sponsor->campaign->title }}</h4>
                <p><strong>📍 Location:</strong> {{ $sponsor->campaign->location }}</p>
                <p><strong>👨‍⚕️ Doctor:</strong> Dr. {{ $sponsor->campaign->doctor->doctor_name }}</p>
                <p><strong>📅 Duration:</strong> 
                    {{ \Carbon\Carbon::parse($sponsor->campaign->start_date)->format('M j, Y') }} - 
                    {{ \Carbon\Carbon::parse($sponsor->campaign->end_date)->format('M j, Y') }}
                </p>
                <p><strong>📝 Description:</strong> {{ $sponsor->campaign->description }}</p>
            </div>

            <!-- Your Impact -->
            <div class="impact-section">
                <h3>🌟 Your Impact</h3>
                <p>Here's how your ₹{{ number_format($sponsor->amount, 2) }} sponsorship will make a difference:</p>
                
                <div class="stats">
                    <div class="stat">
                        <div style="font-size: 30px; margin-bottom: 5px;">{{ floor($sponsor->amount / 500) }}+</div>
                        <div>Patients Treated</div>
                    </div>
                    <div class="stat">
                        <div style="font-size: 30px; margin-bottom: 5px;">❤️</div>
                        <div>Lives Touched</div>
                    </div>
                    <div class="stat">
                        <div style="font-size: 30px; margin-bottom: 5px;">👨‍👩‍👧‍👦</div>
                        <div>Families Helped</div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div style="background: #e9ecef; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4>💳 Payment Confirmation</h4>
                <p><strong>Payment Status:</strong> <span style="color: #28a745; font-weight: bold;">{{ $sponsor->payment_status_label }}</span></p>
                @if($sponsor->payment_id)
                    <p><strong>Payment ID:</strong> <code style="background: #f8f9fa; padding: 2px 6px; border-radius: 3px;">{{ $sponsor->payment_id }}</code></p>
                @endif
                <p><strong>Date:</strong> {{ $sponsor->payment_date ? $sponsor->payment_date->format('M j, Y g:i A') : now()->format('M j, Y g:i A') }}</p>
            </div>

            <!-- Message -->
            @if($sponsor->message)
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin: 20px 0;">
                <h4>💬 Your Message</h4>
                <p style="font-style: italic;">"{{ $sponsor->message }}"</p>
            </div>
            @endif

            <!-- What's Next -->
            <div style="margin: 30px 0;">
                <h3>🚀 What's Next?</h3>
                <ul style="line-height: 1.8;">
                    <li>📧 You'll receive regular updates about the campaign's progress</li>
                    <li>📊 We'll share impact reports showing how your funds are used</li>
                    <li>🏆 Your contribution will be acknowledged in our campaign reports</li>
                    <li>🤝 You're now part of our community of healthcare champions!</li>
                </ul>
            </div>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('user.dashboard') }}" class="btn">Visit Our Platform</a>
                <br>
                <a href="{{ route('sponsor.form') }}" class="btn" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">Sponsor Another Campaign</a>
            </div>

            <div style="background: #f0f0f0; padding: 20px; border-radius: 8px; text-align: center; margin: 30px 0;">
                <h4>📞 Need Help?</h4>
                <p>If you have any questions about your sponsorship or our campaigns, please don't hesitate to contact us:</p>
                <p>
                    📧 Email: <a href="mailto:support@freedoctor.com" style="color: #667eea;">support@freedoctor.com</a><br>
                    📱 Phone: +91 98765 43210
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <h3>FreedDoctor</h3>
            <p>Healthcare for Everyone 🏥❤️</p>
            
            <div class="social-links">
                <a href="#" title="Facebook">📘</a>
                <a href="#" title="Twitter">🐦</a>
                <a href="#" title="Instagram">📷</a>
                <a href="#" title="LinkedIn">💼</a>
            </div>
            
            <p style="font-size: 12px; opacity: 0.8; margin-top: 20px;">
                This email was sent to confirm your sponsorship. Please keep this email for your records.<br>
                © {{ date('Y') }} FreedDoctor. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
