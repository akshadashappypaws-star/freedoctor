<!DOCTYPE html>
<html>
<head>
    <title>Doctor Payout Receipt #{{ $payment->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #333; }
        .receipt-title { font-size: 20px; margin-top: 10px; }
        .receipt-info { margin-bottom: 30px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .label { font-weight: bold; }
        .amount { font-size: 24px; font-weight: bold; color: #28a745; text-align: center; padding: 20px; border: 2px solid #28a745; margin: 20px 0; }
        .details-section { margin-bottom: 30px; }
        .details-title { font-size: 16px; font-weight: bold; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px; }
        .footer { text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ccc; color: #666; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Receipt</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 10px;">Close</button>
    </div>

    <div class="header">
        <div class="logo">FreeDoctor</div>
        <div class="receipt-title">Doctor Payout Receipt</div>
    </div>

    <div class="receipt-info">
        <div class="info-row">
            <span class="label">Receipt Number:</span>
            <span>#{{ $payment->id }}</span>
        </div>
        <div class="info-row">
            <span class="label">Date:</span>
            <span>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i:s') : $payment->created_at->format('M d, Y H:i:s') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span style="text-transform: capitalize;">{{ $payment->payment_status }}</span>
        </div>
        @if($payment->payment_id)
        <div class="info-row">
            <span class="label">Payment ID:</span>
            <span>{{ $payment->payment_id }}</span>
        </div>
        @endif
    </div>

    <div class="amount">
        ₹{{ number_format($payment->amount, 2) }}
    </div>

    @if($payment->doctor)
    <div class="details-section">
        <div class="details-title">Doctor Information</div>
        <div class="info-row">
            <span class="label">Name:</span>
            <span>Dr. {{ $payment->doctor->doctor_name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span>{{ $payment->doctor->email }}</span>
        </div>
        @if($payment->doctor->phone)
        <div class="info-row">
            <span class="label">Phone:</span>
            <span>{{ $payment->doctor->phone }}</span>
        </div>
        @endif
        @if($payment->doctor->specialty)
        <div class="info-row">
            <span class="label">Specialty:</span>
            <span>{{ $payment->doctor->specialty->name }}</span>
        </div>
        @endif
    </div>
    @endif

    @if($payment->payment_details)
    @php
        $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : json_decode($payment->payment_details, true);
    @endphp
    @if($paymentDetails && isset($paymentDetails['account_holder_name']))
    <div class="details-section">
        <div class="details-title">Bank Details</div>
        <div class="info-row">
            <span class="label">Account Holder:</span>
            <span>{{ $paymentDetails['account_holder_name'] }}</span>
        </div>
        @if(isset($paymentDetails['bank_name']))
        <div class="info-row">
            <span class="label">Bank:</span>
            <span>{{ $paymentDetails['bank_name'] }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['account_number']))
        <div class="info-row">
            <span class="label">Account Number:</span>
            <span>****{{ substr($paymentDetails['account_number'], -4) }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['ifsc_code']))
        <div class="info-row">
            <span class="label">IFSC Code:</span>
            <span>{{ $paymentDetails['ifsc_code'] }}</span>
        </div>
        @endif
    </div>
    @endif

    @if(isset($paymentDetails['razorpay_payout_id']) || isset($paymentDetails['utr']))
    <div class="details-section">
        <div class="details-title">Transaction Details</div>
        @if(isset($paymentDetails['razorpay_payout_id']))
        <div class="info-row">
            <span class="label">Razorpay Payout ID:</span>
            <span>{{ $paymentDetails['razorpay_payout_id'] }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['utr']))
        <div class="info-row">
            <span class="label">UTR Number:</span>
            <span>{{ $paymentDetails['utr'] }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['processed_at']))
        <div class="info-row">
            <span class="label">Processed At:</span>
            <span>{{ $paymentDetails['processed_at'] }}</span>
        </div>
        @endif
    </div>
    @endif
    @endif

    @if($payment->description)
    <div class="details-section">
        <div class="details-title">Description</div>
        <p>{{ $payment->description }}</p>
    </div>
    @endif

    <div class="footer">
        <p>This is a computer-generated receipt. No signature required.</p>
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
        <p>FreeDoctor - Connecting Healthcare with Communities</p>
    </div>
</body>
</html>
