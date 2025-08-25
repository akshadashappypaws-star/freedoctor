<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .info-table th,
        .info-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .info-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 30%;
        }
        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .priority {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .priority.general { background: #e3f2fd; color: #1976d2; }
        .priority.technical { background: #fff3e0; color: #f57c00; }
        .priority.billing { background: #e8f5e8; color: #388e3c; }
        .priority.partnership { background: #f3e5f5; color: #7b1fa2; }
        .priority.feedback { background: #e1f5fe; color: #0277bd; }
        .priority.other { background: #fafafa; color: #616161; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Contact Form Submission</h1>
            <p>Someone has contacted FreeDoctor through the website</p>
        </div>

        <table class="info-table">
            <tr>
                <th>Name:</th>
                <td>{{ $data['name'] }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></td>
            </tr>
            @if(!empty($data['phone']))
            <tr>
                <th>Phone:</th>
                <td><a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a></td>
            </tr>
            @endif
            <tr>
                <th>Subject:</th>
                <td>
                    <span class="priority {{ $data['subject'] }}">{{ ucfirst($data['subject']) }}</span>
                </td>
            </tr>
            <tr>
                <th>Submitted:</th>
                <td>{{ now()->format('M d, Y \a\t g:i A') }}</td>
            </tr>
        </table>

        <div class="message-box">
            <h3>📝 Message:</h3>
            <p>{{ nl2br(e($data['message'])) }}</p>
        </div>

        <div style="background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #2e7d32;">📋 Quick Actions:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Reply to: <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a></li>
                @if(!empty($data['phone']))
                <li>Call: <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a></li>
                @endif
                <li>Add to CRM or customer database</li>
                <li>Set follow-up reminder</li>
            </ul>
        </div>

        <div class="footer">
            <p>This email was sent from the FreeDoctor contact form.</p>
            <p>Please respond to the customer within 24 hours for the best experience.</p>
        </div>
    </div>
</body>
</html>
