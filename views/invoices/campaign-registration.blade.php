<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Registration Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .logo-section {
            flex: 1;
        }
        
        .logo {
            width: 180px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .company-info {
            color: #666;
            font-size: 12px;
        }
        
        .invoice-info {
            text-align: right;
            flex: 1;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .invoice-date {
            font-size: 12px;
            color: #888;
        }
        
        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .billing-info {
            flex: 1;
            margin-right: 30px;
        }
        
        .billing-title {
            font-size: 16px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .billing-details {
            font-size: 13px;
            line-height: 1.8;
        }
        
        .campaign-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }
        
        .campaign-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin-bottom: 15px;
        }
        
        .campaign-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #cbd5e1;
        }
        
        .detail-label {
            font-weight: 600;
            color: #475569;
        }
        
        .detail-value {
            color: #1e293b;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .items-table th {
            background: #2563eb;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .items-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .amount-column {
            text-align: right;
            font-weight: 600;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-bottom: 30px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .total-row.final {
            background: #2563eb;
            color: white;
            padding: 15px;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }
        
        .payment-info {
            background: #fef3c7;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 30px;
        }
        
        .payment-title {
            font-weight: bold;
            color: #92400e;
            margin-bottom: 10px;
        }
        
        .qr-section {
            text-align: center;
            background: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            border: 2px dashed #0ea5e9;
            margin-bottom: 30px;
        }
        
        .qr-title {
            font-size: 16px;
            font-weight: bold;
            color: #0c4a6e;
            margin-bottom: 15px;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: #fff;
            border: 2px solid #0ea5e9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #0c4a6e;
        }
        
        .footer {
            text-align: center;
            padding: 20px 0;
            border-top: 2px solid #e5e7eb;
            color: #666;
            font-size: 12px;
        }
        
        .footer-logo {
            width: 100px;
            margin-bottom: 10px;
        }
        
        .terms {
            font-size: 11px;
            color: #888;
            margin-top: 20px;
            line-height: 1.5;
        }
        
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-section">
                <div style="width: 180px; height: 60px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: bold; font-size: 18px;">
                    FreeDoctor
                </div>
                <div class="company-info">
                    Healthcare Technology Solutions<br>
                    support@freedoctor.com | +91-9876543210<br>
                    www.freedoctor.com
                </div>
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number">{{ $invoiceNumber }}</div>
                <div class="invoice-date">
                    Date: {{ $generatedDate->format('d M Y') }}<br>
                    Due: {{ $dueDate->format('d M Y') }}
                </div>
            </div>
        </div>

        <!-- Billing Information -->
        <div class="billing-section">
            <div class="billing-info">
                <div class="billing-title">Bill To:</div>
                <div class="billing-details">
                    <strong>{{ $registration->patient_name }}</strong><br>
                    Age: {{ $registration->age ?? 'N/A' }}<br>
                    Phone: {{ $registration->phone }}<br>
                    Email: {{ $registration->email ?? 'N/A' }}<br>
                    @if($registration->address)
                    Address: {{ $registration->address }}
                    @endif
                </div>
            </div>
            <div class="billing-info">
                <div class="billing-title">Registration Details:</div>
                <div class="billing-details">
                    <strong>{{ $registrationNumber }}</strong><br>
                    Registered: {{ $registration->created_at->format('d M Y H:i') }}<br>
                    Status: <span style="color: #059669; font-weight: bold;">Confirmed</span><br>
                    Payment: <span style="color: #059669; font-weight: bold;">Paid</span>
                </div>
            </div>
        </div>

        <!-- Campaign Information -->
        <div class="campaign-section">
            <div class="campaign-title">{{ $campaign->title }}</div>
            <div class="campaign-details">
                <div class="detail-item">
                    <span class="detail-label">Doctor:</span>
                    <span class="detail-value">{{ $campaign->doctor->name ?? 'Dr. ' . $campaign->doctor_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Specialty:</span>
                    <span class="detail-value">{{ $campaign->specialty ?? 'General Medicine' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($campaign->campaign_date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($campaign->start_time)->format('H:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $campaign->location }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">
                        {{ \Carbon\Carbon::parse($campaign->start_time)->format('H:i A') }} - 
                        {{ \Carbon\Carbon::parse($campaign->end_time)->format('H:i A') }}
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">{{ $campaign->category->name ?? 'General' }}</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 80px;">Qty</th>
                    <th style="width: 120px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Campaign Registration Fee</strong><br>
                        <small style="color: #666;">{{ $campaign->title }}</small>
                    </td>
                    <td>1</td>
                    <td class="amount-column">₹{{ number_format($campaign->registration_fee ?? 0, 2) }}</td>
                </tr>
                @php
                    $baseFee = $campaign->registration_fee ?? 0;
                    $serviceFee = $baseFee * 0.02;
                    $gst = ($baseFee + $serviceFee) * 0.18;
                    $totalAmount = $baseFee + $serviceFee + $gst;
                @endphp
                <tr>
                    <td>
                        <strong>Service Fee</strong><br>
                        <small style="color: #666;">Platform service charges (2%)</small>
                    </td>
                    <td>1</td>
                    <td class="amount-column">₹{{ number_format($serviceFee, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        <strong>GST</strong><br>
                        <small style="color: #666;">Goods and Services Tax (18%)</small>
                    </td>
                    <td>1</td>
                    <td class="amount-column">₹{{ number_format($gst, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($baseFee, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Service Fee:</span>
                <span>₹{{ number_format($serviceFee, 2) }}</span>
            </div>
            <div class="total-row">
                <span>GST (18%):</span>
                <span>₹{{ number_format($gst, 2) }}</span>
            </div>
            <div class="total-row final">
                <span>Total Amount:</span>
                <span>₹{{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Payment Information -->
        <div class="payment-info">
            <div class="payment-title">Payment Information</div>
            <div>
                <strong>Payment Method:</strong> {{ $registration->payment_method ?? 'Online Payment' }}<br>
                <strong>Transaction ID:</strong> {{ $registration->transaction_id ?? 'TXN' . str_pad($registration->id, 8, '0', STR_PAD_LEFT) }}<br>
                <strong>Payment Status:</strong> <span style="color: #059669; font-weight: bold;">Successful</span><br>
                <strong>Payment Date:</strong> {{ $registration->created_at->format('d M Y H:i A') }}
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-title">Quick Check-in QR Code</div>
            <div class="qr-code">
                QR CODE<br>
                {{ $registrationNumber }}
            </div>
            <div style="margin-top: 10px; font-size: 12px; color: #0c4a6e;">
                Show this QR code at the venue for quick check-in
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div style="width: 100px; height: 40px; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: white; display: flex; align-items: center; justify-content: center; border-radius: 5px; font-weight: bold; margin: 0 auto 10px; font-size: 14px;">
                FreeDoctor
            </div>
            <div>
                Thank you for choosing FreeDoctor!<br>
                For support, contact us at support@freedoctor.com or +91-9876543210
            </div>
            
            <div class="terms">
                <strong>Terms & Conditions:</strong><br>
                • Registration is non-transferable and non-refundable unless the campaign is cancelled by the organizer.<br>
                • Please arrive 15 minutes before the scheduled time with a valid ID proof.<br>
                • This invoice serves as your registration confirmation and entry pass.<br>
                • For any queries regarding this registration, please contact our support team.
            </div>
        </div>
    </div>
</body>
</html>
